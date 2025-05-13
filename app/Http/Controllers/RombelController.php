<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\Kelas;
use App\Models\Kurikulum;
use App\Models\Rombel;
use App\Models\Siswa;
use App\Models\TahunPelajaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class RombelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.rombel.index');
    }

    public function data()
    {
        $tahunPelajaran = TahunPelajaran::aktif()->first();

        $query = Rombel::with('siswa_rombel', 'kelas', 'walikelas', 'kurikulum')
            ->whereHas('tahun_pelajaran', function ($q) use ($tahunPelajaran) {
                $q->where('tahun_pelajaran_id', $tahunPelajaran->id);
            })
            ->get();

        return datatables($query)
            ->addIndexColumn()
            ->editColumn('walikelas', function ($q) {
                return $q->walikelas ? $q->walikelas->nama_lengkap : '<span class="badge badge-info">Belum ada walikelas</span>';
            })
            ->addColumn('tingkat', function ($q) {
                return $q->kelas->tingkat ?? '';
            })
            ->addColumn('walikelas', function ($q) {
                return $q->walikelas->nama_lengkap ?? '';
            })
            ->addColumn('kelas', function ($q) {
                return $q->kelas->nama;
            })
            ->addColumn('jumlahsiswa', function ($q) {
                return $q->siswa_rombel->count() ?? 0;
            })
            ->addColumn('aksi', function ($q) {
                return '
                    <a href="' . route('rombel.show', $q->id) . '" class="btn btn-sm btn-primary">DETAIL</a>
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
        $kelas = Kelas::all();
        $walikelas = Guru::all();
        $kurikulum = Kurikulum::all();

        return view('admin.rombel.create', compact('kelas', 'walikelas', 'kurikulum'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Aturan validasi untuk setiap field
        $rules = [
            'kelas_id' => 'required',  // Validasi dengan pengecekan di tabel kelas
            'kurikulum_id' => 'required',  // Validasi dengan pengecekan di tabel kurikulum
            'walikelas' => 'required',  // Validasi dengan pengecekan di tabel guru untuk wali kelas
        ];

        // Pesan kesalahan kustom
        $messages = [
            'kelas_id.required' => 'Kelas harus dipilih.',
            'kurikulum_id.required' => 'Kurikulum harus dipilih.',
            'walikelas.required' => 'Wali Kelas harus dipilih.',
        ];

        // Validasi input berdasarkan rules dan pesan kesalahan
        $validator = Validator::make($request->all(), $rules, $messages);

        // Jika validasi gagal, kembalikan respons dengan status 422 dan pesan kesalahan
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors(),
                'message' => 'Maaf, inputan yang Anda masukkan salah. Silakan periksa kembali dan coba lagi.',
            ], 422);
        }

        $tahunPelajaran = TahunPelajaran::aktif()->first();

        // Menyimpan data rombel ke dalam database
        $data = Rombel::create([
            'tahun_pelajaran_id' => $tahunPelajaran->id,
            'kelas_id' => $request->kelas_id,
            'kurikulum_id' => $request->kurikulum_id,
            'wali_kelas_id' => $request->walikelas,
            'nama' => $request->nama,
        ]);

        // Jika data berhasil disimpan, kirimkan response sukses
        return response()->json([
            'status' => 'success',
            'message' => 'Data Rombel berhasil disimpan!',
            'data' => $data,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $rombel = Rombel::findOrfail($id);
        $kelas = Kelas::all();
        $walikelas = Guru::all();
        $kurikulum = Kurikulum::all();
        return view('admin.rombel.show', compact('rombel', 'kelas', 'walikelas', 'kurikulum'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $rombel = Rombel::findOrfail($id);
        $kelas = Kelas::all();
        $walikelas = Guru::all();
        $kurikulum = Kurikulum::all();

        return view('admin.rombel.edit', compact('kelas', 'walikelas', 'kurikulum', 'rombel'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // Aturan validasi untuk setiap field
        $rules = [
            'nama' => 'required',  // Validasi dengan pengecekan di tabel guru untuk wali kelas
            'walikelas' => 'required',  // Validasi dengan pengecekan di tabel guru untuk wali kelas
        ];

        // Pesan kesalahan kustom
        $messages = [
            'walikelas.required' => 'Wali Kelas harus dipilih.',
        ];

        // Validasi input berdasarkan rules dan pesan kesalahan
        $validator = Validator::make($request->all(), $rules, $messages);

        // Jika validasi gagal, kembalikan respons dengan status 422 dan pesan kesalahan
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors(),
                'message' => 'Maaf, inputan yang Anda masukkan salah. Silakan periksa kembali dan coba lagi.',
            ], 422);
        }

        // Menyimpan data rombel ke dalam database
        $data = [
            'wali_kelas_id' => $request->walikelas,
            'nama' => $request->nama,
        ];

        $rombel = Rombel::findOrfail($id);
        $rombel->update($data);

        // Jika data berhasil disimpan, kirimkan response sukses
        return response()->json([
            'status' => 'success',
            'message' => 'Data Rombel berhasil disimpan!',
            'data' => $data,
        ], 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function getDataSiswa(Request $request)
    {
        // Dapatkan rombel dari request
        $rombel = Rombel::findOrFail($request->rombel_id);
        // Cek apakah tahun pelajaran aktif tersedia
        $tahunPelajaran = TahunPelajaran::aktif()->first();
        if (!$tahunPelajaran) {
            return response()->json(['success' => false, 'message' => 'Tahun pelajaran aktif tidak ditemukan.']);
        }

        $tahunPelajaranId = $tahunPelajaran->id;
        $semester = $tahunPelajaran->semester->nama;
        $kelasLevel = $rombel->kelas->tingkat;

        if ($semester == 'Ganjil') {
            // Ambil siswa yang belum memiliki entri di siswa_rombel untuk tahun pelajaran tertentu
            $siswa = Siswa::where('level', $kelasLevel)
                ->whereDoesntHave('siswa_rombel', function ($query) use ($tahunPelajaranId) {
                    $query->where('siswa_rombel.tahun_pelajaran_id', $tahunPelajaranId); // Tambahkan alias tabel
                })
                ->get();
        } else {
            // Jika semester Genap, cari tahun pelajaran sebelumnya yang semester-nya Ganjil
            $tahunPelajaranSebelumnya = TahunPelajaran::where('id', '<', $tahunPelajaranId)
                ->whereHas('semester', function ($q) {
                    $q->where('nama', 'Ganjil');
                })
                ->orderBy('id', 'desc')
                ->first();

            if (!$tahunPelajaranSebelumnya) {
                // Jika ini adalah tahun pelajaran pertama, hanya ambil siswa baru yang belum masuk siswa_rombel
                $siswa = Siswa::where('level', $kelasLevel)
                    ->whereDoesntHave('siswa_rombel', function ($query) use ($tahunPelajaranId) {
                        $query->where('siswa_rombel.tahun_pelajaran_id', $tahunPelajaranId);
                    })
                    ->get();
            } else {
                $tahunPelajaranSebelumnyaId = $tahunPelajaranSebelumnya->id;

                // Ambil siswa yang sudah terdaftar di semester Ganjil tahun pelajaran sebelumnya
                $siswaTerdaftar = Siswa::where('level', $kelasLevel)
                    ->whereHas('siswa_rombel', function ($query) use ($tahunPelajaranSebelumnyaId) {
                        $query->where('siswa_rombel.tahun_pelajaran_id', $tahunPelajaranSebelumnyaId);
                    });

                // Ambil siswa baru yang belum memiliki entri di siswa_rombel untuk tahun pelajaran saat ini
                $siswaBaru = Siswa::where('level', $kelasLevel)
                    ->whereDoesntHave('siswa_rombel', function ($query) use ($tahunPelajaranId) {
                        $query->where('siswa_rombel.tahun_pelajaran_id', $tahunPelajaranId);
                    });

                // Gabungkan hasil query siswa terdaftar dan siswa baru
                $siswa = $siswaTerdaftar->union($siswaBaru)->get();
            }
        }

        // Kembalikan data siswa dalam format DataTables
        return datatables($siswa)
            ->addIndexColumn()
            ->addColumn('aksi', function ($siswa) {
                return '<input type="checkbox" class="select-siswa" name="siswa_id[]" value="' . $siswa->id . '">';
            })
            ->escapeColumns([])
            ->make(true);
    }

    public function addSiswa(Request $request)
    {
        // Validasi request
        $validated = $request->validate([
            'rombel_id' => 'required|exists:rombels,id',
            'siswa_ids' => 'required|array',
        ]);

        // Dapatkan tahun pelajaran aktif
        $tahunPelajaran = TahunPelajaran::aktif()->first();

        if (!$tahunPelajaran) {
            return response()->json(['success' => false, 'message' => 'No active academic year found.']);
        }

        $tahunPelajaranId = $tahunPelajaran->id;

        // Temukan rombel yang dipilih
        $rombel = Rombel::findOrFail($validated['rombel_id']);

        // Simpan daftar siswa yang berhasil ditambahkan
        $addedSiswa = [];

        foreach ($validated['siswa_ids'] as $siswaId) {
            // Cek jika siswa sudah terdaftar di rombel untuk tahun pelajaran aktif
            $existingEntry = DB::table('siswa_rombel')
                ->where('siswa_id', $siswaId)
                ->where('rombel_id', $rombel->id)
                ->where('tahun_pelajaran_id', $tahunPelajaranId)
                ->exists();

            if (!$existingEntry) {
                // Ambil data siswa untuk menentukan levelnya
                $siswa = Siswa::find($siswaId);
                if ($siswa->level == 1) {
                    $keterangan = 'Siswa Baru';
                } else {
                    $keterangan = 'Naik dari Kelas Sebelumnya';
                }
                // Tentukan keterangan berdasarkan level siswa
                // $keterangan = ($siswa->level == 1) ? 'Siswa Baru' : 'Naik dari Kelas Sebelumnya';

                // Tambahkan siswa ke rombel dengan keterangan yang sesuai
                $rombel->siswa_rombel()->attach($siswaId, [
                    'tahun_pelajaran_id' => $tahunPelajaranId,
                    'status' => 'Aktif',
                    'keterangan' => $keterangan,
                ]);

                $addedSiswa[] = $siswaId; // Simpan ID siswa yang berhasil ditambahkan
            }
        }

        // Ambil data siswa yang baru ditambahkan
        $siswa = Siswa::whereIn('id', $addedSiswa)->get();

        return response()->json([
            'success' => true,
            'siswa' => $siswa,
            'message' => count($addedSiswa) > 0 ? 'Siswa berhasil ditambahkan ke rombel.' : 'Tidak ada siswa yang ditambahkan karena sudah terdaftar.'
        ]);
    }


    public function getSiswaRombel($id)
    {
        $siswa = Siswa::whereHas('siswa_rombel', function ($query) use ($id) {
            $query->where('rombel_id', $id);
        })
            ->orderBy('nama_lengkap', 'asc')
            ->get();

        return datatables($siswa)
            ->addIndexColumn()
            ->addColumn('aksi', function ($row) {
                return '
                    <button type="button" onclick="hapusSiswa(' . $row->id . ')" class="btn btn-danger btn-sm"><i class="fas fa-trash-alt"></i> Hapus</button>
                ';
            })
            ->skipPaging()
            ->rawColumns(['aksi']) // Pastikan HTML tidak di-escape
            ->make(true);
    }

    public function removeSiswa(Request $request)
    {
        try {
            $siswaId = $request->input('siswa_id');
            $rombelId = $request->input('rombel_id');

            // Cari siswa
            $siswa = Siswa::find($siswaId);

            if (!$siswa) {
                return response()->json([
                    'success' => false,
                    'message' => 'Siswa tidak ditemukan.'
                ], 404);
            }

            // Hapus relasi siswa dari rombel
            $deleted = $siswa->siswa_rombel()->detach($rombelId);

            if ($deleted) {
                return response()->json([
                    'success' => true,
                    'message' => 'Siswa berhasil dihapus dari rombel.'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Siswa tidak terdaftar dalam rombel ini.'
                ], 400);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menghapus siswa.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
