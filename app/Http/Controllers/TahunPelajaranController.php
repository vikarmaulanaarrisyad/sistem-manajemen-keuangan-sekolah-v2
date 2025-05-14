<?php

namespace App\Http\Controllers;

use App\Models\Semester;
use App\Models\TahunPelajaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TahunPelajaranController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $semesters = Semester::all();
        return view('admin.tahunpelajaran.index', compact('semesters'));
    }

    public function data()
    {
        $query = TahunPelajaran::with('semester')->orderBy('id', 'DESC');

        return datatables($query)
            ->addIndexColumn()
            ->editColumn('status', function ($q) {
                $icon = $q->status ? 'fa-toggle-on text-success' : 'fa-toggle-off text-danger';
                return '
                <button onclick="updateStatus(' . $q->id . ')" class="status-toggle btn-link" kodeq="' . $q->id . '">
                    <i class="fas ' . $icon . ' fa-lg"></i>
                </button>
            ';
            })
            ->addColumn('aksi', function ($q) {
                return '
                <button onclick="editForm(`' . route('tahunpelajaran.show', $q->id) . '`)" class="btn btn-sm btn-primary" title="Edit"><i class="fas fa-pencil-alt"></i></button>
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
            'semester_id' => 'required',
        ];

        $messages = [
            'nama.required' => 'Tahun pelajaran tidak boleh kosong.',
            'nama.min' => 'Tahun pelajaran harus memiliki minimal 1 karakter.',
            'semester_id.required' => 'Semester wajib dipilih.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return response()->json([
                'status'  => 'error',
                'errors'  => $validator->errors(),
                'message' => 'Maaf, inputan yang Anda masukkan salah. Silakan periksa kembali dan coba lagi.',
            ], 422);
        }

        $data = $request->only(['nama', 'semester_id']); // Mengambil data yang diperlukan saja
        TahunPelajaran::create($data);

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
        $tahunPelajaran = TahunPelajaran::findOrfail($id);

        return response()->json(['data' => $tahunPelajaran]);
    }

    /**
     * update status.
     */
    public function updateStatus($id)
    {
        $tahunPelajaran = TahunPelajaran::findOrFail($id);

        // Jika data yang dipilih ingin diaktifkan, maka nonaktifkan semua yang lain terlebih dahulu
        if ($tahunPelajaran->status == 0) {
            TahunPelajaran::where('status', 1)->update(['status' => 0]);
            $tahunPelajaran->status = 1;
        } else {
            // Cegah menonaktifkan jika hanya ada satu yang aktif
            if (TahunPelajaran::where('status', 1)->count() <= 1) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Minimal satu Tahun Pelajaran harus tetap aktif!'
                ], 400);
            }
            $tahunPelajaran->status = 0;
        }

        $tahunPelajaran->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Status berhasil diperbarui!',
            'new_status' => $tahunPelajaran->status
        ]);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $rules = [
            'nama' => 'required|min:1',
            'semester_id' => 'required',
        ];

        $messages = [
            'nama.required' => 'Tahun pelajaran tidak boleh kosong.',
            'nama.min' => 'Tahun pelajaran harus memiliki minimal 1 karakter.',
            'semester_id.required' => 'Semester wajib dipilih.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return response()->json([
                'status'  => 'error',
                'errors'  => $validator->errors(),
                'message' => 'Maaf, inputan yang Anda masukkan salah. Silakan periksa kembali dan coba lagi.',
            ], 422);
        }

        $data = $request->only(['nama', 'semester_id']); // Mengambil data yang diperlukan saja
        $tahunPelajaran = TahunPelajaran::findOrfail($id);
        $tahunPelajaran->update($data);

        return response()->json([
            'status' => 'success',
            'message' => 'Data berhasil diperbaharui'
        ], 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
