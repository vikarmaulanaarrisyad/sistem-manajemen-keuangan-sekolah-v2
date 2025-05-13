<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\AbsensiSiswa;
use App\Models\HariLibur;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PresensiSiswaController extends Controller
{
    public function index()
    {
        return response()->json(AbsensiSiswa::all());
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'status' => 'required',
        ]);

        // Konversi tanggal menjadi format Carbon
        $tanggal = Carbon::parse($request->tanggal);

        // Cek apakah hari Minggu
        if ($tanggal->isSunday()) {
            return response()->json([
                'message' => 'Hari Minggu tidak dapat melakukan presensi.',
                'error' => true
            ], 400);
        }

        // Cek apakah termasuk hari libur di database
        $hariLibur = HariLibur::where('tanggal', $request->tanggal)->exists();

        if ($hariLibur) {
            return response()->json([
                'message' => 'Hari ini adalah hari libur, presensi tidak diperbolehkan.',
                'error' => true
            ], 400);
        }

        // Simpan presensi jika tidak termasuk hari libur dan bukan hari Minggu
        $presensi = AbsensiSiswa::create([
            'tgl_presensi' => $request->tanggal,
            'status' => $request->status,
        ]);

        return response()->json([
            'message' => 'Presensi berhasil dicatat',
            'presensi' => $presensi
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $presensi = AbsensiSiswa::findOrFail($id);

        $request->validate([
            'tanggal' => 'required|date',
            'status' => 'required',
        ]);

        $presensi->update($request->all());

        return response()->json(['message' => 'Presensi berhasil diperbarui', 'presensi' => $presensi]);
    }

    public function destroy($id)
    {
        $presensi = AbsensiSiswa::findOrFail($id);
        $presensi->delete();

        return response()->json(['message' => 'Presensi berhasil dihapus']);
    }
}
