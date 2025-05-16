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
                'tabunganPerTahun',
                'totalPemasukan',
                'totalPengeluaran',
                'tahunPelajaran',
            ));
        }

        // Jika siswa login
        if ($user->hasRole('siswa')) {
            $siswa = Siswa::where('user_id', $user->id)->first();

            if ($siswa) {
                $totalSetorSiswa = TransaksiTabungan::where('siswa_id', $siswa->id)
                    ->where('jenis_transaksi', 'setor')
                    ->sum('jumlah');

                $totalTarikSiswa = TransaksiTabungan::where('siswa_id', $siswa->id)
                    ->where('jenis_transaksi', 'tarik')
                    ->sum('jumlah');

                $saldoSiswa = $totalSetorSiswa - $totalTarikSiswa;

                return view('siswa.dashboard.index', compact(
                    'totalSetorSiswa',
                    'totalTarikSiswa',
                    'saldoSiswa'
                ));
            }

            // Jika data siswa tidak ditemukan
            return redirect()->back()->with('error', 'Data siswa tidak ditemukan.');
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
