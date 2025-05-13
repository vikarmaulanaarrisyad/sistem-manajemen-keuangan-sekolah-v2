<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class KelasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.kelas.index');
    }

    public function data()
    {
        $query = Kelas::orderBy('nama', 'ASC');

        return datatables($query)
            ->addIndexColumn()
            ->addColumn('aksi', function ($q) {
                return '
                <button onclick="editForm(`' . route('kelas.show', $q->id) . '`)" class="btn btn-sm btn-primary" title="Edit"><i class="fas fa-pencil-alt"></i></button>
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
        $rules = [
            'nama' => 'required|min:1',
            'tingkat' => 'required',
        ];

        $messages = [
            'nama.required' => 'Nama kelas tidak boleh kosong.',
            'nama.min' => 'Nama kelas harus memiliki minimal 1 karakter.',
            'tingkat.required' => 'Tingkat wajib dipilih.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return response()->json([
                'status'  => 'error',
                'errors'  => $validator->errors(),
                'message' => 'Maaf, inputan yang Anda masukkan salah. Silakan periksa kembali dan coba lagi.',
            ], 422);
        }

        $data = $request->only(['nama', 'tingkat']); // Mengambil data yang diperlukan saja
        Kelas::create($data);

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
        $data = Kelas::findOrfail($id);

        return response()->json(['data' => $data]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $rules = [
            'nama' => 'required|min:1',
            'tingkat' => 'required',
        ];

        $messages = [
            'nama.required' => 'Nama kelas tidak boleh kosong.',
            'nama.min' => 'Nama kelas harus memiliki minimal 1 karakter.',
            'tingkat.required' => 'Tingkat wajib dipilih.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return response()->json([
                'status'  => 'error',
                'errors'  => $validator->errors(),
                'message' => 'Maaf, inputan yang Anda masukkan salah. Silakan periksa kembali dan coba lagi.',
            ], 422);
        }

        $data = $request->only(['nama', 'tingkat']); // Mengambil data yang diperlukan saja
        $kelas = Kelas::findOrfail($id);
        $kelas->update($data);

        return response()->json([
            'status' => 'success',
            'message' => 'Data berhasil disimpan'
        ], 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    // fungsi mendapatkan semua data kelas
    public function getKelas()
    {
        $query = Kelas::all();
        return response()->json($query);
    }
}
