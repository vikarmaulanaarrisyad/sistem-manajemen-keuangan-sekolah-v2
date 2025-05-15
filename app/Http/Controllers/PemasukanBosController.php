<?php

namespace App\Http\Controllers;

use App\Models\PemasukanBos;
use App\Models\TahunPelajaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PemasukanBosController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('bendahara.pemasukan.index');
    }

    public function data()
    {
        $query = PemasukanBos::orderBy('id', 'DESC');

        return datatables($query)
            ->addIndexColumn()
            ->editColumn('jumlah', function ($q) {
                return format_uang($q->jumlah);
            })
            ->editColumn('status', function ($q) {
                $icon = $q->status ? 'fa-toggle-on text-success' : 'fa-toggle-off text-danger';
                return '
                <button onclick="updateStatus(' . $q->id . ')" class="status-toggle btn-link" id="' . $q->id . '">
                    <i class="fas ' . $icon . ' fa-lg"></i>
                </button>
            ';
            })
            ->addColumn('aksi', function ($q) {
                return '
                <button onclick="deleteData(`' . route('pemasukan.destroy', $q->id) . '`, `' . $q->sumber_dana . '`, `' . $q->tanggal_terima . '`, `' . $q->jumlah . '`)" class="btn btn-danger btn-sm">
                    <i class="fa fa-trash"></i> Hapus
                </button>
            ';
            })
            ->escapeColumns([])
            ->make(true);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_sumber' => 'required',
            'tanggal_terima' => 'required',
            'jumlah' => 'required|regex:/^[0-9.]+$/',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => 'error',
                'errors'  => $validator->errors(),
                'message' => 'Maaf, inputan yang Anda masukkan salah. Silakan periksa kembali dan coba lagi.',
            ], 422);
        }

        $tahunPelajaran = TahunPelajaran::aktif()->first();
        $jumlah = str_replace('.', '', $request->jumlah);

        $data = [
            'nama_sumber' => $request->nama_sumber,
            'tanggal_terima' => $request->tanggal_terima,
            'jumlah' => $jumlah,
            'tahun_pelajaran_id' => $tahunPelajaran->id,
            'keterangan' => $request->keterangan,
            'status' => 0,
        ];

        PemasukanBos::create($data);

        return response()->json([
            'status' => 'success',
            'message' => 'Data berhasil disimpan'
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(PemasukanBos $pemasukanBos)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PemasukanBos $pemasukanBos)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PemasukanBos $pemasukanBos)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $pemasukan = PemasukanBos::findOrfail($id);
        // Cek jika status == 1, maka tolak penghapusan
        if ($pemasukan->status == '1') {
            return response()->json([
                'status' => 'error',
                'message' => 'Data tidak bisa dihapus karena status telah dikunci.'
            ], 403); // 403 Forbidden
        }

        $pemasukan->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Data berhasil dihapus'
        ], 200);
    }

    public function updateStatus($id)
    {
        $pemasukan = PemasukanBos::findOrFail($id);

        // Jika data yang dipilih ingin diaktifkan, maka nonaktifkan semua yang lain terlebih dahulu
        if ($pemasukan->status == 0) {
            PemasukanBos::where('status', 1)->update(['status' => 0]);
            $pemasukan->status = 1;
        } else {
            // Cegah menonaktifkan jika hanya ada satu yang aktif
            if (PemasukanBos::where('status', 1)->count() <= 1) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Minimal satu harus tetap aktif!'
                ], 400);
            }
            $pemasukan->status = 0;
        }

        $pemasukan->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Status berhasil diperbarui!',
            'new_status' => $pemasukan->status
        ]);
    }
}
