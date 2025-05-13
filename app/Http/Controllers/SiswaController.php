<?php

namespace App\Http\Controllers;

use App\Imports\SiswaImport;
use App\Models\Agama;
use App\Models\JenisKelamin;
use App\Models\Kewarganegaraan;
use App\Models\OrangTua;
use App\Models\Pekerjaan;
use App\Models\Pendidikan;
use App\Models\Rombel;
use App\Models\Siswa;
use App\Models\TahunPelajaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class SiswaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $jenisKelamin = JenisKelamin::all();
        $kewarganegaraan = Kewarganegaraan::all();
        $agama = Agama::all();

        return view('admin.siswa.index', compact('jenisKelamin', 'kewarganegaraan', 'agama'));
    }

    public function data()
    {
        // Ambil tahun pelajaran yang sedang aktif
        $tahunAktif = TahunPelajaran::aktif()->first();

        $query = Siswa::with(['jenis_kelamin', 'siswa_rombel.kelas'])
            ->aktif()
            ->get(); // Ambil semua data dulu karena sortBy() hanya bisa digunakan di Collection

        // Jika rombel perlu diurutkan
        if (request()->has('rombel')) {
            $query = $query->sortBy(function ($q) use ($tahunAktif) {
                $rombel = $q->siswa_rombel->where('tahun_pelajaran_id', $tahunAktif->id)->first();
                return $rombel ? ($rombel->kelas->nama . ' ' . $rombel->nama) : 'ZZZZ'; // Rombel kosong diletakkan di akhir
            });
        }

        if (request()->has('nama_lengkap')) {
            $query = $query->sortBy(function ($q) {
                return $q->nama_lengkap; // Rombel kosong diletakkan di akhir
            });
        }

        return datatables($query)
            ->addIndexColumn()
            ->addColumn('foto', function ($q) {
                if ($q->foto) {
                    $foto = Storage::url($q->foto);
                } else {
                    // Menentukan foto default berdasarkan jenis kelamin
                    if ($q->jenis_kelamin->nama == 'Perempuan') {
                        $foto = asset('AdminLTE/dist/img/avatar3.png'); // Ganti dengan gambar perempuan yang sesuai
                    } else {
                        $foto = asset('AdminLTE/dist/img/avatar4.png'); // Gambar default laki-laki
                    }
                }

                return '<img src="' . $foto . '" class="img-thumbnail rounded-circle" width="50" height="50">';
            })

            ->addColumn('rombel', function ($q) use ($tahunAktif) {
                if (!$tahunAktif) {
                    return '<span class="badge badge-danger">Tidak ada tahun pelajaran aktif</span>';
                }

                $rombel = $q->siswa_rombel->where('tahun_pelajaran_id', $tahunAktif->id)->first();

                return $rombel ? ($rombel->kelas->nama . ' ' . $rombel->nama) : '<span class="badge badge-danger">Tidak terdaftar di rombel aktif</span>';
            })
            ->addColumn('aksi', function ($q) {
                return '<a href="' . route('siswa.detail', $q->id) . '" class="btn btn-sm btn-primary">Lihat Detail</a>';
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
            'nisn' => 'required|min:10|numeric',
            'nik' => 'required|min:16|numeric',
            'nis' => 'required',
            'kk' => 'required|min:16',
            'nama_lengkap' => 'required',
            'nama_panggilan' => 'required',
            'jenis_kelamin_id' => 'required',
            'tempat_lahir' => 'required',
            'tgl_lahir' => 'required',
            'agama_id' => 'required',
            'kewarganegaraan_id' => 'required',
            'jumlah_saudara' => 'required',
            'anakke' => 'required',
            'alamat' => 'required',
            'foto_siswa' => 'required|mimes:png, jpeg, jpg|max:3048',
        ];

        $message = [
            'nisn.required' => 'NISN wajib diisi.',
            'nisn.min' => 'NISN harus terdiri dari minimal 10 angka.',
            'nisn.numeric' => 'NISN harus berupa angka.',

            'nik.required' => 'NIK wajib diisi.',
            'nik.min' => 'NIK harus terdiri dari minimal 16 angka.',
            'nik.numeric' => 'NIK harus berupa angka.',

            'nis.required' => 'NIS wajib diisi.',

            'kk.required' => 'Nomor KK wajib diisi.',
            'kk.min' => 'Nomor KK harus terdiri dari minimal 16 angka.',

            'nama_lengkap.required' => 'Nama lengkap wajib diisi.',
            'nama_panggilan.required' => 'Nama panggilan wajib diisi.',

            'jenis_kelamin_id.required' => 'Jenis kelamin wajib dipilih.',

            'tempat_lahir.required' => 'Tempat lahir wajib diisi.',
            'tgl_lahir.required' => 'Tanggal lahir wajib diisi.',

            'agama_id.required' => 'Agama wajib dipilih.',
            'kewarganegaraan_id.required' => 'Kewarganegaraan wajib dipilih.',

            'jumlah_saudara.required' => 'Jumlah saudara wajib diisi.',
            'anakke.required' => 'Anak ke-berapa wajib diisi.',

            'alamat.required' => 'Alamat wajib diisi.',

            'foto.required' => 'Foto wajib diunggah.',
            'foto.mimes' => 'Foto harus dalam format PNG, JPEG, atau JPG.',
            'foto.max' => 'Ukuran foto tidak boleh lebih dari 3MB.',
        ];

        $validator = Validator::make($request->all(), $rules, $message);

        if ($validator->fails()) {
            return response()->json([
                'status'  => 'error',
                'errors'  => $validator->errors(),
                'message' => 'Maaf, inputan yang Anda masukkan salah. Silakan periksa kembali dan coba lagi.',
            ], 422);
        }

        $data = $request->except('foto_siswa', 'categories');
        $data = [
            'nisn' => $request->nisn,
            'nik' => $request->nik,
            'nis' => $request->nis,
            'kk' => $request->kk,
            'nama_lengkap' => $request->nama_lengkap,
            'nama_panggilan' => $request->nama_panggilan,
            'jenis_kelamin_id' => $request->jenis_kelamin_id,
            'agama_id' => $request->agama_id,
            'tempat_lahir' => $request->tempat_lahir,
            'tgl_lahir' => $request->tgl_lahir,
            'kewarganegaraan_id' => $request->kewarganegaraan_id,
            'jumlah_saudara' => $request->jumlah_saudara,
            'alamat' => $request->alamat,
            'foto' => upload('upload/siswa', $request->file('foto_siswa'), $request->nisn)
        ];

        Siswa::create($data);

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
        $data = Siswa::findOrfail($id);
        $data->foto = Storage::url($data->foto);

        return response()->json(['data' => $data]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    public function detail($id)
    {
        $siswa = Siswa::with('orangtua', 'siswa_rombel')->findOrfail($id);
        $jenisKelamin = JenisKelamin::all();
        $kewarganegaraan = Kewarganegaraan::all();
        $agama = Agama::all();
        $pendidikan = Pendidikan::all();
        $pekerjaan = Pekerjaan::all();
        $ortu = OrangTua::where('siswa_id', $id)->first();

        return view('admin.siswa.detail', compact('siswa', 'jenisKelamin', 'kewarganegaraan', 'agama', 'pendidikan', 'pekerjaan', 'ortu'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $siswa = Siswa::findOrFail($id);

        $rules = [
            'nisn' => 'required|min:10|numeric',
            'nik' => 'required|min:16|numeric',
            'nis' => 'required',
            'kk' => 'required|min:16',
            'nama_lengkap' => 'required',
            'nama_panggilan' => 'required',
            'jenis_kelamin_id' => 'required',
            'tempat_lahir' => 'required',
            'tgl_lahir' => 'required',
            'agama_id' => 'required',
            'kewarganegaraan_id' => 'required',
            'jumlah_saudara' => 'required',
            'anakke' => 'required',
            'alamat' => 'required',
            'foto_siswa' => 'nullable|mimes:png,jpeg,jpg|max:3048', // Foto tidak wajib diupload
        ];

        $message = [
            'nisn.required' => 'NISN wajib diisi.',
            'nisn.min' => 'NISN harus terdiri dari minimal 10 angka.',
            'nisn.numeric' => 'NISN harus berupa angka.',
            'nik.required' => 'NIK wajib diisi.',
            'nik.min' => 'NIK harus terdiri dari minimal 16 angka.',
            'nik.numeric' => 'NIK harus berupa angka.',
            'nis.required' => 'NIS wajib diisi.',
            'kk.required' => 'Nomor KK wajib diisi.',
            'kk.min' => 'Nomor KK harus terdiri dari minimal 16 angka.',
            'nama_lengkap.required' => 'Nama lengkap wajib diisi.',
            'nama_panggilan.required' => 'Nama panggilan wajib diisi.',
            'jenis_kelamin_id.required' => 'Jenis kelamin wajib dipilih.',
            'tempat_lahir.required' => 'Tempat lahir wajib diisi.',
            'tgl_lahir.required' => 'Tanggal lahir wajib diisi.',
            'agama_id.required' => 'Agama wajib dipilih.',
            'kewarganegaraan_id.required' => 'Kewarganegaraan wajib dipilih.',
            'jumlah_saudara.required' => 'Jumlah saudara wajib diisi.',
            'anakke.required' => 'Anak ke-berapa wajib diisi.',
            'alamat.required' => 'Alamat wajib diisi.',
            'foto_siswa.mimes' => 'Foto harus dalam format PNG, JPEG, atau JPG.',
            'foto_siswa.max' => 'Ukuran foto tidak boleh lebih dari 3MB.',
        ];

        $validator = Validator::make($request->all(), $rules, $message);

        if ($validator->fails()) {
            return response()->json([
                'status'  => 'error',
                'errors'  => $validator->errors(),
                'message' => 'Maaf, inputan yang Anda masukkan salah. Silakan periksa kembali dan coba lagi.',
            ], 422);
        }

        // Data yang akan diperbarui
        $data = $request->except('foto_siswa');

        // Jika ada foto baru yang diunggah
        if ($request->hasFile('foto_siswa')) {
            // Hapus foto lama jika ada dan tidak null
            if (!empty($siswa->foto) && Storage::disk('public')->exists($siswa->foto)) {
                Storage::disk('public')->delete($siswa->foto);
            }

            // Simpan foto baru dan perbarui data
            $data['foto'] = upload('upload/siswa', $request->file('foto_siswa'), $request->nisn);
        }

        // Update data siswa
        $siswa->update($data);


        // Update data siswa
        $siswa->update($data);

        return response()->json([
            'status' => 'success',
            'message' => 'Data berhasil diperbarui'
        ], 200);
    }

    public function updateOrtu(Request $request)
    {
        $rules = [
            'nama_ayah' => 'nullable',
            'nama_ibu' => 'required',
            'nama_walimurid' => 'nullable',
            'pekerjaan_ayah_id' => 'required',
            'pekerjaan_ibu_id' => 'required',
            'pekerjaan_walimurid_id' => 'required',
            'pendidikan_ayah_id' =>  'required',
            'pendidikan_ibu_id' =>  'required',
            'pendidikan_walimurid_id' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'status'  => 'error',
                'errors'  => $validator->errors(),
                'message' => 'Maaf, inputan yang Anda masukkan salah. Silakan periksa kembali dan coba lagi.',
            ], 422);
        }

        $siswa = Siswa::where('id', $request->siswa_id)->aktif()->first();

        if (!$siswa) {
            return response()->json(['message' => 'Siswa tidak ditemukan'], 404);
        }

        $data = [
            'nama_ayah' => $request->nama_ayah ?? '-',
            'nama_ibu' => $request->nama_ibu ?? '-',
            'nama_walimurid' => $request->nama_walimurid ?? '-',
            'pekerjaan_ayah_id' => $request->pekerjaan_ayah_id ?? '-',
            'pekerjaan_ibu_id' => $request->pekerjaan_ibu_id ?? '-',
            'pekerjaan_walimurid_id' => $request->pekerjaan_walimurid_id ?? '-',
            'pendidikan_ayah_id' => $request->pendidikan_ayah_id ?? '-',
            'pendidikan_ibu_id' => $request->pendidikan_ibu_id ?? '-',
            'pendidikan_walimurid_id' => $request->pendidikan_walimurid_id ?? '-',
        ];

        OrangTua::updateOrCreate(
            ['siswa_id' => $siswa->id],
            $data
        );

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
            Excel::import(new SiswaImport, $request->file('excelFile'), null, \Maatwebsite\Excel\Excel::XLSX);

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

    public function naikkanSiswaPerRombel($rombel_id)
    {
        $tahunAktif = TahunPelajaran::where('status_aktif', 1)->first();
        if (!$tahunAktif) {
            return response()->json(['message' => 'Tidak ada tahun pelajaran aktif'], 404);
        }

        // Cek apakah rombel ada di tahun sebelumnya
        $rombelLama = Rombel::where('id', $rombel_id)->first();
        if (!$rombelLama) {
            return response()->json(['message' => 'Rombel tidak ditemukan'], 404);
        }

        // Cek apakah ini kelas terakhir (misal: kelas 6)
        if ($rombelLama->nama == "Kelas 6") {
            // Luluskan siswa
            Siswa::where('rombel_id', $rombelLama->id)->update([
                'status' => 'lulus'
            ]);

            return response()->json(['message' => 'Siswa dirombel ini telah lulus'], 200);
        }

        // Cari rombel berikutnya berdasarkan nama yang sama +1 tingkat
        $rombelBaru = Rombel::where('nama', 'Kelas ' . ((int) filter_var($rombelLama->nama, FILTER_SANITIZE_NUMBER_INT) + 1))
            ->where('tahun_pelajaran_id', $tahunAktif->id)
            ->first();

        if (!$rombelBaru) {
            return response()->json(['message' => 'Rombel berikutnya tidak ditemukan'], 404);
        }

        // Pindahkan siswa ke rombel baru dan simpan rombel lama
        Siswa::where('rombel_id', $rombelLama->id)->update([
            'rombel_sebelumnya_id' => $rombelLama->id,
            'rombel_id' => $rombelBaru->id
        ]);

        return response()->json(['message' => 'Siswa berhasil naik kelas'], 200);
    }


    public function batalkanKenaikanSiswa($rombelId)
    {
        // Ambil siswa yang memiliki rombel_sebelumnya_id
        $siswaDinaikkan = Siswa::whereNotNull('rombel_sebelumnya_id')->get();

        if ($siswaDinaikkan->isEmpty()) {
            return response()->json(['message' => 'Tidak ada siswa yang bisa dikembalikan'], 404);
        }

        foreach ($siswaDinaikkan as $siswa) {
            // Kembalikan siswa ke rombel sebelumnya
            $siswa->update([
                'rombel_id' => $siswa->rombel_sebelumnya_id,
                'rombel_sebelumnya_id' => null
            ]);
        }

        return response()->json(['message' => 'Kenaikan siswa berhasil dibatalkan'], 200);
    }
}
