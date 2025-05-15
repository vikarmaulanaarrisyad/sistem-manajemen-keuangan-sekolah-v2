<?php

namespace App\Http\Controllers;

use App\Models\PemasukanBos;
use App\Models\PengeluaranBos;
use App\Models\TahunPelajaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class PengeluaranBosController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('bendahara.pengeluaran.index');
    }

    public function data()
    {
        $query = PengeluaranBos::orderBy('id', 'DESC');

        return datatables($query)
            ->addIndexColumn()
            ->editColumn('jumlah', function ($q) {
                return format_uang($q->jumlah);
            })

            ->addColumn('aksi', function ($q) {
                return '
                <button onclick="deleteData(`' . route('pengeluaran.destroy', $q->id) . '`, `' . $q->uraian . '`, `' . $q->tanggal_terima . '`, `' . $q->jumlah . '`)" class="btn btn-danger btn-sm">
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
            'uraian' => 'required',
            'tanggal_pengeluaran' => 'required',
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

        // Hitung total pemasukan dan pengeluaran
        $jumlahPemasukan = PemasukanBos::where('tahun_pelajaran_id', $tahunPelajaran->id)->sum('jumlah');
        $jumlahPengeluaran = PengeluaranBos::where('tahun_pelajaran_id', $tahunPelajaran->id)->sum('jumlah');

        // Saldo saat ini
        $saldoTersedia = $jumlahPemasukan - $jumlahPengeluaran;

        if ($jumlah > $saldoTersedia) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Gagal menyimpan. Jumlah pengeluaran melebihi saldo yang tersedia ' . format_uang($saldoTersedia) . '.',
            ], 400);
        }

        $data = [
            'uraian' => $request->uraian,
            'tanggal_pengeluaran' => $request->tanggal_pengeluaran,
            'jumlah' => $jumlah,
            'tahun_pelajaran_id' => $tahunPelajaran->id,
            'user_id' => Auth::user()->id,
        ];

        PengeluaranBos::create($data);

        return response()->json([
            'status' => 'success',
            'message' => 'Data berhasil disimpan'
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $pengeluaranBos = PengeluaranBos::findOrfail($id);
            $pengeluaranBos->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Data pengeluaran berhasil dihapus.',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat menghapus data.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
