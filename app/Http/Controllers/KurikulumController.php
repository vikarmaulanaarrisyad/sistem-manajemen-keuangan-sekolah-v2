<?php

namespace App\Http\Controllers;

use App\Models\Kurikulum;
use App\Models\TahunPelajaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class KurikulumController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $kurikulums = Kurikulum::all();
        return view('admin.kurikulum.index', compact('kurikulums'));
    }

    public function data()
    {
        $query = Kurikulum::with('tahun_pelajaran')->orderBy('id', 'DESC');

        return datatables($query)
            ->addIndexColumn()
            ->editColumn('tahun_pelajaran', function ($q) {
                return $q->tahun_pelajaran->nama . ' ' . $q->tahun_pelajaran->semester->nama;
            })
            ->addColumn('aksi', function ($q) {
                return '
                <button onclick="editForm(`' . route('kurikulum.show', $q->id) . '`)" class="btn btn-sm btn-primary" title="Edit"><i class="fas fa-pencil-alt"></i></button>
            ';
            })
            ->escapeColumns([])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Tentukan aturan validasi berdasarkan pilihan kurikulum
        $rules = [];

        if ($request->kurikulum_lama == 'copy') {
            $rules['nama_kurikulum_lama'] = 'required';
        } else {
            $rules['nama'] = 'required|min:1';
        }

        $messages = [
            'nama.required' => 'Nama kurikulum tidak boleh kosong.',
            'nama.min' => 'Nama kurikulum harus memiliki minimal 1 karakter.',
            'kurikulum_lama.required' => 'Harap pilih kurikulum lama jika ingin menyalin data.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return response()->json([
                'status'  => 'error',
                'errors'  => $validator->errors(),
                'message' => 'Maaf, inputan yang Anda masukkan salah. Silakan periksa kembali dan coba lagi.',
            ], 422);
        }

        // Ambil Tahun Pelajaran Aktif
        $tahunPelajaran = TahunPelajaran::aktif()->first();

        // Menentukan nama kurikulum
        $namaKurikulum = $request->nama ?? $request->nama_kurikulum_lama;

        // Pastikan kurikulum yang akan dibuat tidak duplikat
        if (Kurikulum::where('nama', $namaKurikulum)->where('tahun_pelajaran_id', $tahunPelajaran->id)->exists()) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Kurikulum dengan nama tersebut sudah ada untuk tahun pelajaran ini.',
            ], 422);
        }

        // Simpan data kurikulum baru
        $data = [
            'nama' => $namaKurikulum,
            'tahun_pelajaran_id' => $tahunPelajaran->id,
        ];

        Kurikulum::create($data);

        return response()->json([
            'status' => 'success',
            'message' => 'Data berhasil disimpan'
        ], 201);
    }


    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $kurikulum = Kurikulum::findOrfail($id);

        return response()->json(['data' => $kurikulum]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // Tentukan aturan validasi berdasarkan pilihan kurikulum
        $rules = [];

        if ($request->kurikulum_lama == 'copy') {
            $rules['nama_kurikulum_lama'] = 'required';
        } else {
            $rules['nama'] = 'required|min:1';
        }

        $messages = [
            'nama.required' => 'Nama kurikulum tidak boleh kosong.',
            'nama.min' => 'Nama kurikulum harus memiliki minimal 1 karakter.',
            'nama_kurikulum_lama.required' => 'Harap pilih kurikulum lama jika ingin menyalin data.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return response()->json([
                'status'  => 'error',
                'errors'  => $validator->errors(),
                'message' => 'Maaf, inputan yang Anda masukkan salah. Silakan periksa kembali dan coba lagi.',
            ], 422);
        }

        // Ambil Tahun Pelajaran Aktif
        $tahunPelajaran = TahunPelajaran::aktif()->first();
        if (!$tahunPelajaran) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Tidak ada Tahun Pelajaran aktif yang tersedia.',
            ], 400);
        }

        // Menentukan nama kurikulum
        $namaKurikulum = $request->filled('nama') ? $request->nama : $request->nama_kurikulum_lama;

        // Cek apakah kurikulum dengan nama yang sama sudah ada untuk tahun pelajaran ini, kecuali yang sedang diedit
        $isDuplicate = Kurikulum::where('nama', $namaKurikulum)
            ->where('tahun_pelajaran_id', $tahunPelajaran->id)
            ->where('id', '!=', $id)
            ->exists();

        if ($isDuplicate) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Kurikulum dengan nama tersebut sudah ada untuk tahun pelajaran ini.',
            ], 422);
        }

        // Cari kurikulum yang akan diperbarui
        $kurikulum = Kurikulum::findOrFail($id);

        // Update data kurikulum
        $kurikulum->update([
            'nama' => $namaKurikulum,
            'tahun_pelajaran_id' => $tahunPelajaran->id,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Data berhasil diperbarui'
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
