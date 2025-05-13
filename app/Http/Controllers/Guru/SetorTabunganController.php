<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Guru;
use App\Models\Rombel;
use App\Models\Siswa;
use App\Models\TahunPelajaran;
use App\Models\TransaksiTabungan;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class SetorTabunganController extends Controller
{
    public function data(Request $request)
    {
        $userId = Auth::id();

        $guru = Guru::where('user_id', $userId)->first();
        if (!$guru) {
            return response()->json(['error' => 'Guru tidak ditemukan.'], 404);
        }

        $tapel = TahunPelajaran::aktif()->first();
        if (!$tapel) {
            return response()->json(['error' => 'Tahun pelajaran aktif tidak ditemukan.'], 404);
        }

        $rombel = Rombel::with('walikelas')
            ->where('tahun_pelajaran_id', $tapel->id)
            ->whereHas('walikelas', function ($q) use ($guru) {
                $q->where('wali_kelas_id', $guru->id);
            })
            ->first();

        if (!$rombel) {
            return response()->json(['error' => 'Rombel tidak ditemukan untuk guru ini.'], 404);
        }

        // Menambahkan filter berdasarkan ID dan tanggal jika ada di request
        $data = DB::table('transaksi_tabungans')
            ->join('siswas', 'transaksi_tabungans.siswa_id', '=', 'siswas.id')
            ->join('siswa_rombel', 'siswas.id', '=', 'siswa_rombel.siswa_id')
            ->where('siswa_rombel.rombel_id', $rombel->id)
            ->where('transaksi_tabungans.jenis_transaksi', 'setor')
            ->when($request->has('id'), function ($query) use ($request) {
                return $query->where('transaksi_tabungans.id', $request->input('id'));
            })
            ->when($request->has('tanggal'), function ($query) use ($request) {
                return $query->whereDate('transaksi_tabungans.tanggal_transaksi', $request->input('tanggal'));
            })
            ->orderBy('transaksi_tabungans.tanggal_transaksi', 'desc')
            ->select(
                'transaksi_tabungans.id',
                'transaksi_tabungans.tanggal_transaksi',
                'siswas.nama_lengkap',
                'siswas.nis',
                'transaksi_tabungans.keterangan',
                'transaksi_tabungans.jumlah'
            );

        return datatables($data)
            ->addIndexColumn()
            ->addColumn('jumlah', fn($row) => format_uang($row->jumlah)) // Menampilkan jumlah tanpa di-group
            ->addColumn('aksi', function ($row) {
                // <button onclick="downloadPDF(`' . route('setor.downloadPDF', $row->id) . '`)" class="btn btn-primary btn-sm">
                //     <i class="fa fa-file-pdf"></i> Download
                // </button>
                return '
                    <button onclick="deleteData(`' . route('setor.destroy', $row->id) . '`, `' . $row->nama_lengkap . '`, `' . $row->tanggal_transaksi . '`, `' . $row->jumlah . '`)" class="btn btn-danger btn-sm">
                        <i class="fa fa-trash"></i> Hapus
                    </button>
                ';
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }


    public function getSiswa(Request $request)
    {
        $userId = Auth::id();

        $guru = Guru::where('user_id', $userId)->first();
        if (!$guru) {
            return response()->json([], 200); // Kosongkan jika tidak ditemukan
        }

        $tapel = TahunPelajaran::aktif()->first();
        if (!$tapel) {
            return response()->json([], 200);
        }

        $rombel = Rombel::with('walikelas')
            ->where('tahun_pelajaran_id', $tapel->id)
            ->whereHas('walikelas', function ($q) use ($guru) {
                $q->where('wali_kelas_id', $guru->id);
            })
            ->first();

        if (!$rombel) {
            return response()->json([], 200);
        }

        $search = $request->get('q');

        $siswa = Siswa::whereHas('siswa_rombel', function ($q) use ($rombel) {
            $q->where('rombel_id', $rombel->id);
        })
            ->when($search, function ($query, $search) {
                $query->where('nama_lengkap', 'like', "%{$search}%")
                    ->orWhere('nis', 'like', "%{$search}%")
                    ->orWhere('nisn', 'like', "%{$search}%");
            })
            ->select('id', 'nama_lengkap', 'nis', 'nisn')
            ->limit(20)
            ->get();

        $formatted = $siswa->map(function ($s) {
            return [
                'id' => $s->id,
                'text' => "{$s->nama_lengkap} ({$s->nisn})"
            ];
        });

        return response()->json($formatted);
    }

    public function index()
    {
        return view('guru.tabungan.setor.index');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'peserta_didik_id' => 'required',
            'tanggal' => 'required',
            'jumlah' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => 'error',
                'errors'  => $validator->errors(),
                'message' => 'Maaf, inputan yang Anda masukkan salah. Silakan periksa kembali dan coba lagi.',
            ], 422);
        }

        // Generate nomor invoice
        $prefix = 'INV';
        $date = date('Ymd');
        $last = TransaksiTabungan::where('jenis_transaksi', 'setor')
            ->whereDate('created_at', now()->toDateString())->count() + 1;
        $nomor_invoice = $prefix . '-' . $date . '-' . str_pad($last, 4, '0', STR_PAD_LEFT);

        // Data transaksi setor
        $data = [
            'siswa_id' => $request->peserta_didik_id,
            'user_id'  => Auth::user()->id,
            'jenis_transaksi' => 'setor',
            'jumlah' => str_replace('.', '', $request->jumlah),  // Hapus titik agar menjadi angka
            'keterangan' => $request->keterangan,
            'tanggal_transaksi' => $request->tanggal,
            'invoice' => $nomor_invoice,
        ];

        // Simpan transaksi setor
        $transaksi = TransaksiTabungan::create($data);

        // Ambil siswa berdasarkan peserta_didik_id
        $siswa = Siswa::find($request->peserta_didik_id);

        if ($siswa) {
            // Tambahkan jumlah setor ke saldo siswa
            $siswa->saldo = $transaksi->jumlah + $siswa->saldo;  // Gunakan $transaksi->jumlah yang sudah disimpan
            $siswa->save();
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Data berhasil disimpan dan saldo siswa diperbarui'
        ], 200);
    }

    public function PDF1($id)
    {
        $tabungan = TransaksiTabungan::where('id', $id)->first();

        $siswa = Siswa::with(['siswa_rombel'])
            ->where('id', $tabungan->siswa_id)->first();
        $userId = Auth::user()->id;
        $guru = Guru::where('user_id', $userId)->first();

        return view('guru.tabungan.setor.cetak_bukti_setor', compact('siswa', 'guru', 'tabungan'));
    }

    public function PDF($id)
    {
        $tabungan = TransaksiTabungan::where('id', $id)->first();
        // Ambil data yang diperlukan
        $siswa = Siswa::with(['siswa_rombel'])
            ->where('id', $tabungan->siswa_id)->first();
        $userId = Auth::user()->id;
        $guru = Guru::where('user_id', $userId)->first();

        // Generate PDF
        $pdf = Pdf::loadView('guru.tabungan.setor.cetak_bukti_setor', compact('siswa', 'guru', 'tabungan'));

        // Atur ukuran kertas dan orientasi (F4)
        $pdf->setPaper('A4', 'landscape');
        return $pdf->stream('tanda_bukti_setor_tunai.pdf');

        // Download PDF
        // return $pdf->download('tanda_bukti_setor_tunai.pdf');
    }

    // hapus data
    public function destroy($id)
    {
        $transaksi = TransaksiTabungan::findOrFail($id);

        // Ambil siswa terkait
        $siswa = Siswa::where('id', $transaksi->siswa_id)->first();

        if ($siswa) {
            // Kembalikan saldo
            $siswa->saldo -= $transaksi->jumlah;
            $siswa->save();
        }

        // Hapus transaksi
        $transaksi->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Data berhasil dihapus'
        ], 200);
    }
}
