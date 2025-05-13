<?php

namespace App\Http\Controllers;

use App\Exports\GuruExport;
use App\Imports\GuruImport;
use App\Models\Guru;
use App\Models\JenisKelamin;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class GuruController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $jenisKelamin = JenisKelamin::all();
        return view('admin.guru.index', compact('jenisKelamin'));
    }

    public function data()
    {
        $query = Guru::with('jenis_kelamin', 'user')->orderBy('nama_lengkap', 'ASC');

        return datatables($query)
            ->addIndexColumn()
            ->editColumn('nama_lengkap', function ($q) {
                $namaLengkap = '';

                if (!empty($q->gelar_depan)) {
                    $namaLengkap .= $q->gelar_depan . ' ';
                }

                $namaLengkap .= $q->nama_lengkap;

                if (!empty($q->gelar_belakang)) {
                    $namaLengkap .= ', ' . $q->gelar_belakang;
                }

                return $namaLengkap;
            })
            ->addColumn('aksi', function ($q) {
                return '
                <button onclick="editForm(`' . route('guru.show', $q->id) . '`)" class="btn btn-sm btn-primary" title="Edit"><i class="fas fa-pencil-alt"></i></button>
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
            'email' => 'required|email|unique:users,email',
            'nama_lengkap' => 'required',
            'nik' => 'required|numeric|digits:16',
            'jenis_kelamin_id' => 'required',
            'tempat_lahir' => 'required|string',
            'tgl_lahir' => 'required|date',
            'tmt_guru' => 'required|date',
            'tmt_pegawai' => 'required|date',
        ];

        // Pesan error untuk validasi
        $messages = [
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah terdaftar.',
            'nik.required' => 'NIK wajib diisi.',
            'nik.numeric' => 'NIK harus berupa angka.',
            'nik.digits' => 'NIK harus memiliki 16 digit.',
            'nik.unique' => 'NIK sudah terdaftar.',
            'jenis_kelamin_id.required' => 'Jenis kelamin wajib dipilih.',
            'tempat_lahir.required' => 'Tempat lahir wajib diisi.',
            'tgl_lahir.required' => 'Tanggal lahir wajib diisi.',
            'tgl_lahir.date' => 'Format tanggal lahir tidak valid.',
            'tmt_guru.required' => 'TMT Guru wajib diisi.',
            'tmt_pegawai.required' => 'TMT Pegawai wajib diisi.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return response()->json([
                'status'  => 'error',
                'errors'  => $validator->errors(),
                'message' => 'Maaf, inputan yang Anda masukkan salah. Silakan periksa kembali dan coba lagi.',
            ], 422);
        }

        DB::beginTransaction();

        try {
            // Buat atau cari User berdasarkan email
            $user = User::firstOrCreate(
                ['email' => $request->email], // Kriteria pencarian berdasarkan email
                [
                    'name' => $request->nama_lengkap,
                    'username' => $request->nik,
                    'password' => Hash::make($request->password ?? 'password') // Gunakan password dari request atau default "password"
                ]
            );

            // Beri role "guru" hanya jika user baru dibuat
            if ($user->wasRecentlyCreated) {
                $user->assignRole('guru');
            }

            $data = [
                'user_id' => $user->id,
                'nama_lengkap' => $request->nama_lengkap,
                'tempat_lahir' => $request->tempat_lahir,
                'tgl_lahir' => $request->tgl_lahir,
                'gelar_depan' => $request->gelar_depan,
                'gelar_belakang' => $request->gelar_belakang,
                'nik' => $request->nik,
                'jenis_kelamin_id' => $request->jenis_kelamin_id,
                'tmt_guru' => $request->tmt_guru,
                'tmt_pegawai' => $request->tmt_pegawai,
            ];

            Guru::create($data);

            DB::commit(); // Simpan perubahan jika tidak ada error

            return response()->json([
                'status' => 'success',
                'message' => 'Data berhasil disimpan',
                'user' => $user
            ], 201);
        } catch (\Throwable $th) {
            DB::rollBack(); // Batalkan perubahan jika terjadi error

            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat menyimpan data user',
                'error' => $th->getMessage()
            ], 500);
        }

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
        $data = Guru::with('user')->findOrfail($id);
        $data['email'] = $data->user->email;
        return response()->json(['data' => $data]);
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
        // Cari Guru berdasarkan user_id
        $guru = Guru::where('id', $id)->first();

        // Jika data Guru tidak ditemukan, kembalikan error
        if (!$guru) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Data Guru tidak ditemukan.',
            ], 404);
        }

        // Temukan User berdasarkan ID
        $user = User::findOrFail($guru->user_id);

        // Atur aturan validasi
        $rules = [
            'email' => 'required|email|unique:users,email,' . $user->id, // Email harus unik kecuali milik user sendiri
            'nama_lengkap' => 'required|string',
            'nik' => 'required|numeric|digits:16',
            'jenis_kelamin_id' => 'required|integer',
            'tempat_lahir' => 'required|string',
            'tgl_lahir' => 'required|date',
            'tmt_guru' => 'required|date',
            'tmt_pegawai' => 'required|date',
        ];

        // Pesan error untuk validasi
        $messages = [
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah terdaftar.',
            'nik.required' => 'NIK wajib diisi.',
            'nik.numeric' => 'NIK harus berupa angka.',
            'nik.digits' => 'NIK harus memiliki 16 digit.',
            'jenis_kelamin_id.required' => 'Jenis kelamin wajib dipilih.',
            'tempat_lahir.required' => 'Tempat lahir wajib diisi.',
            'tgl_lahir.required' => 'Tanggal lahir wajib diisi.',
            'tgl_lahir.date' => 'Format tanggal lahir tidak valid.',
            'tmt_guru.required' => 'TMT Guru wajib diisi.',
            'tmt_pegawai.required' => 'TMT Pegawai wajib diisi.',
        ];

        // Validasi data request
        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return response()->json([
                'status'  => 'error',
                'errors'  => $validator->errors(),
                'message' => 'Inputan yang Anda masukkan salah. Silakan periksa kembali.',
            ], 422);
        }

        DB::beginTransaction();

        try {
            // Update data User
            $user->update([
                'name' => $request->nama_lengkap,
                'username' => $request->nik,
                'email' => $request->email,
                'password' => $request->filled('password') ? Hash::make($request->password) : $user->password // Hanya update jika ada password baru
            ]);

            // Update atau buat baru data Guru
            Guru::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'nama_lengkap' => $request->nama_lengkap,
                    'tempat_lahir' => $request->tempat_lahir,
                    'tgl_lahir' => $request->tgl_lahir,
                    'gelar_depan' => $request->gelar_depan,
                    'gelar_belakang' => $request->gelar_belakang,
                    'nik' => $request->nik,
                    'jenis_kelamin_id' => $request->jenis_kelamin_id,
                    'tmt_guru' => $request->tmt_guru,
                    'tmt_pegawai' => $request->tmt_pegawai,
                ]
            );

            DB::commit(); // Simpan perubahan

            return response()->json([
                'status' => 'success',
                'message' => 'Data berhasil diperbarui',
                'user' => $user
            ], 200);
        } catch (\Throwable $th) {
            DB::rollBack(); // Batalkan perubahan jika terjadi error

            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat memperbarui data user',
                'error' => $th->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function exportEXCEL()
    {
        $fileName = 'guru_' . now()->format('Ymdhis') . '.xlsx';
        return Excel::download(new GuruExport, $fileName);
    }

    public function importEXCEL(Request $request)
    {
        // Validasi file
        $validator = Validator::make($request->all(), [
            'excelFile' => 'required|file|mimes:xlsx,xls|max:2048', // Maks 2MB
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()->first()
            ], 422);
        }

        try {
            // Proses import menggunakan Laravel Excel
            Excel::import(new GuruImport, $request->file('excelFile'), null, \Maatwebsite\Excel\Excel::XLSX);

            return response()->json([
                'status' => 'success',
                'message' => 'File berhasil diupload dan diproses!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
