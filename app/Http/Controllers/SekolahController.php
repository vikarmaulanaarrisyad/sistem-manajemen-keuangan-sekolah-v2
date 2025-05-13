<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\Sekolah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SekolahController extends Controller
{
    public function index()
    {
        $sekolah = Sekolah::first();
        $guru = Guru::all();

        return view('pengaturan.sekolah.index', compact('sekolah', 'guru'));
    }

    public function update(Request $request, $id)
    {
        $sekolah = Sekolah::findOrFail($id);

        // Validate the request data
        $validatedData = $request->validate([
            'nama' => 'nullable|string|max:255',
            'npsn' => 'nullable|numeric',
            'nsm' => 'nullable|numeric',
            'alamat' => 'nullable|string',
            'notelp' => 'nullable|numeric',
            'email' => 'nullable|email',
            'website' => 'nullable|url',
            'kepala_sekolah_id' => 'nullable|exists:gurus,id',
            'bendahara_id' => 'nullable|exists:gurus,id',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif',
            'sejarah' => 'nullable',
            'visi' => 'nullable',
            'misi' => 'nullable',
        ]);

        $data = $request->except('logo');

        if ($request->hasFile('logo')) {
            if (Storage::disk('public')->exists($sekolah->logo)) {
                Storage::disk('public')->delete($sekolah->logo);
            }

            // Store the file with a unique name and store the original name if necessary
            $data['logo'] = upload('logo', $request->file('logo'), 'logo');
        }

        // Update the other fields with the validated data
        $sekolah->update($data);

        return response()->json(['success' => true]);
    }
}
