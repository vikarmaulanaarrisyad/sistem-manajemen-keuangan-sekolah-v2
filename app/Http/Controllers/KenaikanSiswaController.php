<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\Rombel;
use App\Models\TahunPelajaran;
use Illuminate\Support\Facades\DB;

class KenaikanSiswaController extends Controller
{
    public function getSiswa(Request $request)
    {
        $rombelId = $request->rombel_id;

        if (!$rombelId) {
            return response()->json(['siswa' => []]);
        }

        // Ambil Tahun Pelajaran Aktif & Tahun Sebelumnya
        $tahunPelajaranAktif = $this->getTahunPelajaran('aktif');
        $tahunSebelumnya = $this->getTahunPelajaran('sebelumnya', $tahunPelajaranAktif->id);

        if (!$tahunPelajaranAktif || !$tahunSebelumnya) {
            return response()->json(['error' => 'Tahun Pelajaran tidak ditemukan.'], 404);
        }

        // Ambil Data Rombel
        $rombel = Rombel::findOrFail($rombelId);

        // Pastikan rombel memiliki kelas sebelum mengecek tingkatnya
        $kelasTingkat = optional($rombel->kelas)->tingkat;
        if (!$kelasTingkat) {
            return response()->json(['error' => 'Kelas atau Tingkat tidak ditemukan.'], 404);
        }

        // Ambil siswa yang ada di rombel tahun sebelumnya tetapi belum masuk ke rombel tahun sekarang
        $filteredSiswa = Siswa::whereHas('siswa_rombel', function ($query) use ($tahunSebelumnya, $rombelId) {
            $query->where('siswa_rombel.tahun_pelajaran_id', $tahunSebelumnya->id)
                ->where('siswa_rombel.rombel_id', $rombelId);
        })
            ->whereDoesntHave('siswa_rombel', function ($query) use ($tahunPelajaranAktif) {
                $query->where('siswa_rombel.tahun_pelajaran_id', $tahunPelajaranAktif->id);
            })
            ->where('level', $kelasTingkat)
            ->with('siswa_rombel')
            ->get();

        // Mapping data siswa
        $siswaData = $filteredSiswa->map(function ($siswa) {
            return [
                'id'            => $siswa->id,
                'nama_lengkap'  => $siswa->nama_lengkap,
                'nisn'          => $siswa->nisn,
                'nis'           => $siswa->nis,
            ];
        });

        return response()->json(['siswa' => $siswaData]);
    }

    /**
     * Ambil Tahun Pelajaran berdasarkan status
     *
     * @param string $status ('aktif' atau 'sebelumnya')
     * @param int|null $idTahunAktif ID Tahun Pelajaran Aktif (opsional, untuk mendapatkan tahun sebelumnya)
     * @return TahunPelajaran|null
     */
    private function getTahunPelajaran($status, $idTahunAktif = null)
    {
        if ($status === 'aktif') {
            return TahunPelajaran::aktif()->orderBy('nama', 'desc')->first();
        }

        if ($status === 'sebelumnya' && $idTahunAktif) {
            return TahunPelajaran::where('id', '<', $idTahunAktif)
                ->orderBy('id', 'desc')
                ->first();
        }

        return null;
    }


    public function index1()
    {
        $tahunPelajaranAktif = TahunPelajaran::aktif()->orderBy('nama', 'desc')->first();
        if (!$tahunPelajaranAktif) {
            return redirect()->back()->with('error', 'Tahun Pelajaran Aktif tidak ditemukan.');
        }

        $tahunSebelumnya = TahunPelajaran::where('id', '<', $tahunPelajaranAktif->id)
            ->orderBy('nama', 'desc')
            ->first();
        if (!$tahunSebelumnya) {
            return redirect()->back()->with('error', 'Tahun Pelajaran Sebelumnya tidak ditemukan.');
        }

        $rombelSebelumnya = Rombel::where('tahun_pelajaran_id', $tahunSebelumnya->id)->get();

        $kelas = Kelas::orderBy('nama')->get();

        $kelasTujuan = [];
        foreach ($kelas as $index => $k) {
            if (isset($kelas[$index + 1])) {
                $kelasTujuan[$k->id] = $kelas[$index + 1]->nama;
            }
        }

        return view('admin.kenaikan.index', compact('rombelSebelumnya', 'tahunSebelumnya', 'tahunPelajaranAktif', 'kelas', 'kelasTujuan'));
    }

    public function index()
    {
        // Ambil Tahun Pelajaran Aktif
        $tahunPelajaranAktif = TahunPelajaran::aktif()->orderBy('nama', 'desc')->first();
        if (!$tahunPelajaranAktif) {
            return redirect()->back()->with('error', 'Tahun Pelajaran Aktif tidak ditemukan.');
        }

        // Ambil Tahun Pelajaran Sebelumnya yang memiliki semester "Genap"
        $tahunSebelumnya = TahunPelajaran::where('id', '<', $tahunPelajaranAktif->id)
            ->whereHas('semester', function ($query) {
                $query->where('nama', 'Genap');
            })
            ->orderBy('id', 'desc')
            ->first();

        if (!$tahunSebelumnya) {
            return redirect()->back()->with('error', 'Tahun Pelajaran Sebelumnya tidak ditemukan atau bukan semester Genap.');
        }

        // Ambil daftar Rombel berdasarkan Tahun Pelajaran Sebelumnya
        $rombelSebelumnya = Rombel::where('tahun_pelajaran_id', $tahunSebelumnya->id)->get();

        // Ambil daftar Kelas
        $kelas = Kelas::orderBy('nama')->get();

        // Buat daftar tujuan kenaikan kelas
        $kelasTujuan = [];
        foreach ($kelas as $index => $k) {
            if (isset($kelas[$index + 1])) {
                $kelasTujuan[$k->id] = $kelas[$index + 1]->nama;
            }
        }

        return view('admin.kenaikan.index', compact('rombelSebelumnya', 'tahunSebelumnya', 'tahunPelajaranAktif', 'kelas', 'kelasTujuan'));
    }

    public function prosesKenaikan(Request $request)
    {
        $request->validate([
            'siswa_ids' => 'required|array',
            'kelas_tujuan' => 'required|string',
        ]);

        try {
            DB::beginTransaction();

            // Ambil tahun pelajaran aktif
            $tahunPelajaranAktif = TahunPelajaran::aktif()->orderBy('nama', 'desc')->first();
            if (!$tahunPelajaranAktif) {
                throw new \Exception("Tahun Pelajaran Aktif tidak ditemukan.");
            }

            // Ambil tahun pelajaran sebelumnya
            $tahunSebelumnya = TahunPelajaran::where('id', '<', $tahunPelajaranAktif->id)
                ->orderBy('nama', 'desc')
                ->first();
            if (!$tahunSebelumnya) {
                throw new \Exception("Tahun Pelajaran Sebelumnya tidak ditemukan.");
            }

            $status = "Aktif";
            $keterangan = "-"; // Default jika bukan kelas 1

            // Jika siswa lulus, ubah level ke 7 (Alumni)
            if ($request->kelas_tujuan === "Lulus") {
                Siswa::whereIn('id', $request->siswa_ids)->update([
                    'level' => 7,
                ]);
                $status = "Alumni";
                $keterangan = "Lulus";
            } else {
                // Cari kelas tujuan berdasarkan nama
                $kelas = Kelas::where('nama', $request->kelas_tujuan)->first();
                if (!$kelas) {
                    throw new \Exception("Kelas tujuan tidak ditemukan.");
                }

                // Update level siswa ke tingkat kelas tujuan
                Siswa::whereIn('id', $request->siswa_ids)->update([
                    'rombel_sebelumnya_id' => $request->rombel_sebelumnya,
                    'level' => $kelas->tingkat,
                ]);
            }

            // Loop setiap siswa untuk memperbarui status di `siswa_rombel`
            foreach ($request->siswa_ids as $siswa_id) {
                // Ambil level siswa setelah update
                $siswa = Siswa::find($siswa_id);

                // **Jika siswa berasal dari kelas 1 pada tahun sebelumnya, set keterangan "Siswa Baru"**
                $rombelSebelumnya = DB::table('siswa_rombel')
                    ->where('siswa_id', $siswa_id)
                    ->where('rombel_id', $request->rombel_sebelumnya)
                    ->where('tahun_pelajaran_id', $tahunSebelumnya->id) // Tahun sebelumnya
                    ->first();

                if ($siswa->level == 1) {
                    $keterangan = "Siswa Baru";
                } else {
                    $keterangan = "Naik dari Kelas Sebelumnya";
                }

                // Perbarui atau buat data di `siswa_rombel`
                DB::table('siswa_rombel')
                    ->where('tahun_pelajaran_id', $tahunPelajaranAktif->id)
                    ->update(
                        [
                            'status' => $status,
                            'keterangan' => $keterangan,
                            'updated_at' => now(),
                        ]
                    );
            }

            DB::commit();

            return response()->json([
                'message' => 'Proses kenaikan siswa berhasil dilakukan!',
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function prosesKenaikan1(Request $request)
    {
        $request->validate([
            'siswa_ids' => 'required|array',
            'kelas_tujuan' => 'required|string',
        ]);

        try {
            DB::beginTransaction();

            $status = $request->kelas_tujuan === "Lulus" ? "Alumni" : "Aktif";
            $keterangan = $request->kelas_tujuan === "Lulus" ? "Lulus" : "-";

            // Jika siswa lulus, ubah level ke 7 (Alumni)
            if ($request->kelas_tujuan === "Lulus") {
                Siswa::whereIn('id', $request->siswa_ids)->update([
                    'level' => 7,
                ]);
            } else {
                // Cari kelas tujuan berdasarkan nama
                $kelas = Kelas::where('nama', $request->kelas_tujuan)->first();
                if (!$kelas) {
                    throw new \Exception("Kelas tujuan tidak ditemukan.");
                }

                // Update level siswa ke tingkat kelas tujuan
                Siswa::whereIn('id', $request->siswa_ids)->update([
                    'rombel_sebelumnya_id' => $request->rombel_sebelumnya,
                    'level' => $kelas->tingkat,
                ]);
            }

            // Loop setiap siswa untuk memperbarui status di `siswa_rombel`
            foreach ($request->siswa_ids as $siswa_id) {
                // Ambil level siswa setelah update
                $siswa = Siswa::find($siswa_id);

                // Jika level siswa adalah 1, status tetap "Aktif" dan keterangan "Siswa Baru"
                if ($siswa->level == '1') {
                    $status = "Aktif";
                    $keterangan = "Siswa Baru";
                }

                // Update data di tabel pivot `siswa_rombel`
                DB::table('siswa_rombel')
                    ->where('siswa_id', $siswa_id)
                    ->where('rombel_id', $request->rombel_sebelumnya)
                    ->update([
                        'status' => $status,
                        'keterangan' => $keterangan,
                        'updated_at' => now(),
                    ]);
            }

            DB::commit();

            return response()->json([
                'message' => 'Proses kenaikan siswa berhasil dilakukan!',
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function batalKenaikan(Request $request)
    {
        DB::beginTransaction();
        try {
            $siswa = Siswa::whereNotNull('rombel_id')->update(['rombel_id' => null, 'status' => 'aktif']);

            DB::commit();
            return response()->json(['message' => 'Kenaikan siswa berhasil dibatalkan!']);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['message' => 'Terjadi kesalahan!'], 500);
        }
    }
}
