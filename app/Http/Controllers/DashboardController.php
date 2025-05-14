<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\PemasukanBos;
use App\Models\PengeluaranBos;
use App\Models\Rombel;
use App\Models\Siswa;
use App\Models\TransaksiTabungan;
use App\Models\TahunPelajaran;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index1()
    {
        $user = Auth::user();
        $tahunPelajaran = TahunPelajaran::aktif()->first();

        $guru = Guru::count();
        $siswa = Siswa::aktif()->count();
        $kurikulum = $tahunPelajaran?->kurikulum()->count() ?? 0;
        $rombel = $tahunPelajaran?->rombel()->count() ?? 0;

        // Hitung siswa laki-laki dan perempuan
        $siswaLaki = Siswa::aktif()->whereHas('jenis_kelamin', function ($query) {
            $query->where('nama', 'Laki-laki');
        })->count();

        $siswaPerempuan  = Siswa::aktif()->whereHas('jenis_kelamin', function ($query) {
            $query->where('nama', 'Perempuan');
        })->count();

        // Hitung total setor dan tarik tunai
        $totalSetor = TransaksiTabungan::where('jenis_transaksi', 'setor')->sum('jumlah');
        $totalTarik = TransaksiTabungan::where('jenis_transaksi', 'tarik')->sum('jumlah');

        if ($user->hasRole('admin')) {
            return view('dashboard.index', compact(
                'guru',
                'siswa',
                'rombel',
                'kurikulum',
                'siswaLaki',
                'siswaPerempuan',
                'totalSetor',
                'totalTarik'
            ));
        } else {
            // Guru yang login
            $guru = Guru::where('user_id', $user->id)->first();

            // Ambil semua rombel yang diajar guru dari pivot guru_rombel
            $rombels = Rombel::where('wali_kelas_id', $guru->id)
                ->where('tahun_pelajaran_id', $tahunPelajaran->id)
                ->pluck('id'); // Ambil semua id rombel dalam array

            // Hitung jumlah siswa unik yang diajar guru
            $jumlahSiswaDiajar = 0;
            if ($rombels->isNotEmpty()) {
                $jumlahSiswaDiajar = DB::table('siswa_rombel')
                    ->whereIn('rombel_id', $rombels)
                    ->where('tahun_pelajaran_id', $tahunPelajaran->id)
                    ->distinct('siswa_id')
                    ->count('siswa_id');
            }

            return view('guru.dashboard.index', compact(
                'totalSetor',
                'totalTarik',
                'jumlahSiswaDiajar'
            ));
        }
    }

    public function index2()
    {
        $user = Auth::user();
        $tahunPelajaran = TahunPelajaran::aktif()->first();

        $guru = Guru::count();
        $siswa = Siswa::aktif()->count();
        $kurikulum = $tahunPelajaran?->kurikulum()->count() ?? 0;
        $rombel = $tahunPelajaran?->rombel()->count() ?? 0;

        // Hitung siswa laki-laki dan perempuan
        $siswaLaki = Siswa::aktif()->whereHas('jenis_kelamin', function ($query) {
            $query->where('nama', 'Laki-laki');
        })->count();

        $siswaPerempuan  = Siswa::aktif()->whereHas('jenis_kelamin', function ($query) {
            $query->where('nama', 'Perempuan');
        })->count();

        // Hitung total setor dan tarik tunai
        $totalSetor = TransaksiTabungan::where('jenis_transaksi', 'setor')->sum('jumlah');
        $totalTarik = TransaksiTabungan::where('jenis_transaksi', 'tarik')->sum('jumlah');

        // Grafik Tabungan Per Bulan
        $tabunganPerBulan = TransaksiTabungan::select(DB::raw('MONTH(tanggal_transaksi) as bulan'), DB::raw('SUM(jumlah) as total_setor'))
            ->where('jenis_transaksi', 'setor')
            ->whereYear('tanggal_transaksi', Carbon::now()->year)
            ->groupBy(DB::raw('MONTH(tanggal_transaksi)'))
            ->orderBy(DB::raw('MONTH(tanggal_transaksi)'))
            ->get();

        // Grafik Tabungan Per Tahun
        $tabunganPerTahun = TransaksiTabungan::select(DB::raw('YEAR(tanggal_transaksi) as tahun'), DB::raw('SUM(jumlah) as total_setor'))
            ->where('jenis_transaksi', 'setor')
            ->groupBy(DB::raw('YEAR(tanggal_transaksi)'))
            ->orderBy(DB::raw('YEAR(tanggal_transaksi)'))
            ->get();

        // Ambil guru yang login
        $guru = Guru::where('user_id', $user->id)->first();

        // Ambil semua rombel yang diajar guru
        $rombels = Rombel::where('wali_kelas_id', $guru->id)
            ->where('tahun_pelajaran_id', $tahunPelajaran->id)
            ->pluck('id'); // array of rombel_id

        // Ambil siswa yang terdaftar di salah satu rombel tsb
        $saldoPerSiswa = Siswa::whereHas('siswa_rombel', function ($query) use ($rombels) {
            $query->whereIn('rombels.id', $rombels);
        })
            ->aktif()
            ->withSum(['tabungan as total_setor' => function ($query) {
                $query->where('jenis_transaksi', 'setor');
            }], 'jumlah')
            ->withSum(['tabungan as total_tarik' => function ($query) {
                $query->where('jenis_transaksi', 'tarik');
            }], 'jumlah')
            ->get()
            ->map(function ($siswa) {
                // Hitung saldo dari total setor - total tarik
                $siswa->saldo = ($siswa->total_setor ?? 0) - ($siswa->total_tarik ?? 0);
                return $siswa;
            });

        if ($user->hasRole('admin')) {
            return view('admin.dashboard.index', compact(
                'guru',
                'siswa',
                'rombel',
                'kurikulum',
                'siswaLaki',
                'siswaPerempuan',
                'totalSetor',
                'totalTarik',
                'tabunganPerBulan',
                'tabunganPerTahun'
            ));
        } else {
            // Guru yang login
            $guru = Guru::where('user_id', $user->id)->first();

            // Ambil semua rombel yang diajar guru dari pivot guru_rombel
            $rombels = Rombel::where('wali_kelas_id', $guru->id)
                ->where('tahun_pelajaran_id', $tahunPelajaran->id)
                ->pluck('id'); // Ambil semua id rombel dalam array

            // Hitung jumlah siswa unik yang diajar guru
            $jumlahSiswaDiajar = 0;
            if ($rombels->isNotEmpty()) {
                $jumlahSiswaDiajar = DB::table('siswa_rombel')
                    ->whereIn('rombel_id', $rombels)
                    ->where('tahun_pelajaran_id', $tahunPelajaran->id)
                    ->distinct('siswa_id')
                    ->count('siswa_id');
            }

            return view('guru.dashboard.index', compact(
                'totalSetor',
                'totalTarik',
                'jumlahSiswaDiajar',
                'tabunganPerBulan',
                'tabunganPerTahun',
                'saldoPerSiswa'
            ));
        }
    }

    public function index()
    {
        $user = Auth::user();
        $tahunPelajaran = TahunPelajaran::aktif()->first();

        // Data umum
        $totalSetor = TransaksiTabungan::where('jenis_transaksi', 'setor')->sum('jumlah');
        $totalTarik = TransaksiTabungan::where('jenis_transaksi', 'tarik')->sum('jumlah');

        // Grafik tabungan per bulan (tahun berjalan)
        $tabunganPerBulan = TransaksiTabungan::selectRaw('MONTH(tanggal_transaksi) as bulan, SUM(jumlah) as total_setor')
            ->where('jenis_transaksi', 'setor')
            ->whereYear('tanggal_transaksi', Carbon::now()->year)
            ->groupByRaw('MONTH(tanggal_transaksi)')
            ->orderByRaw('MONTH(tanggal_transaksi)')
            ->get();

        // Grafik tabungan per tahun
        $tabunganPerTahun = TransaksiTabungan::selectRaw('YEAR(tanggal_transaksi) as tahun, SUM(jumlah) as total_setor')
            ->where('jenis_transaksi', 'setor')
            ->groupByRaw('YEAR(tanggal_transaksi)')
            ->orderByRaw('YEAR(tanggal_transaksi)')
            ->get();

        // Total Pemasukan
        $totalPemasukan = PemasukanBos::where('tahun_pelajaran_id', $tahunPelajaran->id)->sum('jumlah');
        $totalPengeluaran = PengeluaranBos::where('tahun_pelajaran_id', $tahunPelajaran->id)->sum('jumlah');

        // Jika admin login
        if ($user->hasRole('admin')) {
            $totalGuru = Guru::count();
            $totalSiswa = Siswa::aktif()->count();
            $totalKurikulum = $tahunPelajaran?->kurikulum()->count() ?? 0;
            $totalRombel = $tahunPelajaran?->rombel()->count() ?? 0;

            $siswaLaki = Siswa::aktif()->whereHas('jenis_kelamin', fn($q) => $q->where('nama', 'Laki-laki'))->count();
            $siswaPerempuan = Siswa::aktif()->whereHas('jenis_kelamin', fn($q) => $q->where('nama', 'Perempuan'))->count();

            return view('admin.dashboard.index', compact(
                'totalGuru',
                'totalSiswa',
                'totalKurikulum',
                'totalRombel',
                'siswaLaki',
                'siswaPerempuan',
                'totalSetor',
                'totalTarik',
                'tabunganPerBulan',
                'tabunganPerTahun'
            ));
        }

        // Jika guru login
        $guruLogin = Guru::where('user_id', $user->id)->first();
        $rombelGuru = Rombel::where('wali_kelas_id', $guruLogin->id)
            ->where('tahun_pelajaran_id', $tahunPelajaran->id)
            ->pluck('id');

        // Hitung jumlah siswa yang diajar guru
        $jumlahSiswaDiajar = 0;
        if ($rombelGuru->isNotEmpty()) {
            $jumlahSiswaDiajar = DB::table('siswa_rombel')
                ->whereIn('rombel_id', $rombelGuru)
                ->where('tahun_pelajaran_id', $tahunPelajaran->id)
                ->distinct('siswa_id')
                ->count('siswa_id');
        }

        // Saldo per siswa
        $saldoPerSiswa = Siswa::whereHas('siswa_rombel', function ($query) use ($rombelGuru) {
            $query->whereIn('rombels.id', $rombelGuru);
        })
            ->aktif()
            ->withSum(['tabungan as total_setor' => fn($q) => $q->where('jenis_transaksi', 'setor')], 'jumlah')
            ->withSum(['tabungan as total_tarik' => fn($q) => $q->where('jenis_transaksi', 'tarik')], 'jumlah')
            ->get()
            ->map(function ($siswa) {
                $siswa->saldo = ($siswa->total_setor ?? 0) - ($siswa->total_tarik ?? 0);
                return $siswa;
            });

        return view('guru.dashboard.index', compact(
            'totalSetor',
            'totalTarik',
            'jumlahSiswaDiajar',
            'tabunganPerBulan',
            'tabunganPerTahun',
            'saldoPerSiswa',
            'totalPemasukan',
            'tahunPelajaran',
            'totalPengeluaran'
        ));
    }
}
