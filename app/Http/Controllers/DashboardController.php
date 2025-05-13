<?php

namespace App\Http\Controllers;

use App\Models\Guru;
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

    public function index()
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

        // Ambil saldo per siswa
        $saldoPerSiswa = Siswa::aktif()->withSum('tabungan as saldo', 'jumlah')->get();

        if ($user->hasRole('admin')) {
            return view('dashboard.index', compact(
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
}
