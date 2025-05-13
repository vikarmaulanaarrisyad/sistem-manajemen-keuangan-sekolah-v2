<?php

namespace App\Http\Controllers;

use App\Models\TransaksiTabungan;
use Illuminate\Http\Request;

class TransaksiTabunganController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function setorIndex()
    {
        return view('guru.tabungan.setor.index');
    }

    public function simpanSetor(Request $request)
    {
        dd($request->all());
        // TransaksiTabungan::create([
        //     'peserta_didik_id' => $request->peserta_didik_id,
        //     'tanggal' => $request->tanggal,
        //     'jumlah' => $request->jumlah,
        //     'keterangan' => $request->keterangan,
        //     'petugas' => auth()->user()->id,
        //     'no_trans' => 'ST' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT),
        // ]);

        return response()->json(['message' => 'Data berhasil disimpan']);
    }
}
