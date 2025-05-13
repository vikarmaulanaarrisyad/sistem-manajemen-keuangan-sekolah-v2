<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\JurnalGuru;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JurnalController extends Controller
{
    public function index()
    {
        return response()->json(JurnalGuru::all());
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'isi' => 'required|string',
        ]);

        $jurnal = JurnalGuru::create([
            'judul' => $request->judul,
            'isi' => $request->isi,
            'user_id' => Auth::id(),
        ]);

        return response()->json(['message' => 'Jurnal berhasil dibuat', 'jurnal' => $jurnal], 201);
    }

    public function update(Request $request, $id)
    {
        $jurnal = JurnalGuru::findOrFail($id);

        $request->validate([
            'judul' => 'required|string|max:255',
            'isi' => 'required|string',
        ]);

        $jurnal->update($request->all());

        return response()->json(['message' => 'Jurnal berhasil diperbarui', 'jurnal' => $jurnal]);
    }

    public function destroy($id)
    {
        $jurnal = JurnalGuru::findOrFail($id);
        $jurnal->delete();

        return response()->json(['message' => 'Jurnal berhasil dihapus']);
    }
}
