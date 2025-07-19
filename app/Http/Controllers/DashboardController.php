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
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class DashboardController extends Controller
{
    public function index1()
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

    public function index()
    {
        $user = Auth::user();
        $tahunPelajaran = TahunPelajaran::aktif()->first();

        $totalSetor = TransaksiTabungan::where('jenis_transaksi', 'setor')->sum('jumlah');
        $totalTarik = TransaksiTabungan::where('jenis_transaksi', 'tarik')->sum('jumlah');
        $saldoTabunganSiswa = $totalSetor - $totalTarik;

        // BOS
        $totalPemasukan = PemasukanBos::where('tahun_pelajaran_id', $tahunPelajaran->id)->sum('jumlah');
        $totalPengeluaran = PengeluaranBos::where('tahun_pelajaran_id', $tahunPelajaran->id)->sum('jumlah');
        $saldoBOS = $totalPemasukan - $totalPengeluaran;

        $tabunganPerBulan = TransaksiTabungan::selectRaw('MONTH(tanggal_transaksi) as bulan, SUM(jumlah) as total_setor')
            ->where('jenis_transaksi', 'setor')
            ->whereYear('tanggal_transaksi', Carbon::now()->year)
            ->groupByRaw('MONTH(tanggal_transaksi)')
            ->orderByRaw('MONTH(tanggal_transaksi)')
            ->get();

        $tabunganPerTahun = TransaksiTabungan::selectRaw('YEAR(tanggal_transaksi) as tahun, SUM(jumlah) as total_setor')
            ->where('jenis_transaksi', 'setor')
            ->groupByRaw('YEAR(tanggal_transaksi)')
            ->orderByRaw('YEAR(tanggal_transaksi)')
            ->get();

        if ($user->hasRole('admin')) {
            $totalGuru = Guru::count();
            $totalSiswa = Siswa::aktif()->count();
            $totalKurikulum = $tahunPelajaran?->kurikulum()->count() ?? 0;
            $totalRombel = $tahunPelajaran?->rombel()->count() ?? 0;

            $siswaLaki = Siswa::aktif()->whereHas('jenis_kelamin', fn($q) => $q->where('nama', 'Laki-laki'))->count();
            $siswaPerempuan = Siswa::aktif()->whereHas('jenis_kelamin', fn($q) => $q->where('nama', 'Perempuan'))->count();

            $totalSaldo = $saldoBOS + $saldoTabunganSiswa;

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
                'saldoBOS',
                'saldoTabunganSiswa',
                'totalSaldo',
                'tahunPelajaran'
            ));
        }

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

            return redirect()->back()->with('error', 'Data siswa tidak ditemukan.');
        }

        // Guru
        $guruLogin = Guru::where('user_id', $user->id)->first();
        $rombelGuru = Rombel::where('wali_kelas_id', $guruLogin->id)
            ->where('tahun_pelajaran_id', $tahunPelajaran->id)
            ->pluck('id');

        $jumlahSiswaDiajar = 0;
        if ($rombelGuru->isNotEmpty()) {
            $jumlahSiswaDiajar = DB::table('siswa_rombel')
                ->whereIn('rombel_id', $rombelGuru)
                ->where('tahun_pelajaran_id', $tahunPelajaran->id)
                ->distinct('siswa_id')
                ->count('siswa_id');
        }

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

        $totalSaldoGuru = $saldoPerSiswa->sum('saldo');

        return view('guru.dashboard.index', compact(
            'totalSetor',
            'totalTarik',
            'jumlahSiswaDiajar',
            'tabunganPerBulan',
            'tabunganPerTahun',
            'saldoPerSiswa',
            'totalPemasukan',
            'totalPengeluaran',
            'tahunPelajaran',
            'totalSaldoGuru',
            'saldoBOS'
        ));
    }

    public function tabungan1(Request $request)
    {
        $user = auth()->user();
        $siswa = Siswa::where('user_id', $user->id)->first();

        $bulan = $request->input('bulan');

        if (!$siswa) {
            return response()->json(['error' => 'Data siswa tidak ditemukan.'], 404);
        }

        $query = TransaksiTabungan::where('siswa_id', $siswa->id)
            ->orderBy('tanggal_transaksi', 'asc')
            ->get();

        $saldo = 0;

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('tanggal', function ($q) {
                return $q->tanggal_transaksi;
            })
            ->addColumn('uraian', function ($q) {
                return $q->uraian;
            })
            ->addColumn('pemasukan', function ($q) {
                return $q->jenis_transaksi === 'setor' ? number_format($q->jumlah, 0, ',', '.') : '';
            })
            ->addColumn('pengeluaran', function ($q) {
                return $q->jenis_transaksi === 'tarik' ? number_format($q->jumlah, 0, ',', '.') : '';
            })
            ->addColumn('saldo', function ($q) use (&$saldo) {
                // Update saldo per baris
                if ($q->jenis_transaksi === 'setor') {
                    $saldo += $q->jumlah;
                } else {
                    $saldo -= $q->jumlah;
                }

                return number_format($saldo, 0, ',', '.');
            })
            ->rawColumns(['pemasukan', 'pengeluaran', 'saldo'])
            ->make(true);
    }

    public function tabungan(Request $request)
    {
        $user = auth()->user();
        $siswa = Siswa::where('user_id', $user->id)->first();

        $bulan = $request->input('bulan');

        $query = TransaksiTabungan::where('siswa_id', $siswa->id)
            ->when($bulan, fn($q) => $q->whereMonth('tanggal_transaksi', $bulan))
            ->orderBy('tanggal_transaksi');

        $saldo = 0;

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('tanggal', fn($q) => $q->tanggal_transaksi)
            ->addColumn('pemasukan', fn($q) => $q->jenis_transaksi === 'setor' ? 'Rp' . number_format($q->jumlah, 0, ',', '.') : '')
            ->addColumn('pengeluaran', fn($q) => $q->jenis_transaksi === 'tarik' ? 'Rp' . number_format($q->jumlah, 0, ',', '.') : '')
            ->addColumn('saldo', function ($q) use (&$saldo) {
                $saldo += $q->jenis_transaksi === 'setor' ? $q->jumlah : -$q->jumlah;
                return 'Rp' . number_format($saldo, 0, ',', '.');
            })
            ->rawColumns(['pemasukan', 'pengeluaran', 'saldo'])
            ->make(true);
    }

    public function grafikSaldo()
    {
        $user = Auth::user();

        if ($user->hasRole('siswa')) {
            $siswa = Siswa::where('user_id', $user->id)->first();

            if (!$siswa) {
                return response()->json(['message' => 'Siswa tidak ditemukan'], 404);
            }

            $data = TransaksiTabungan::where('siswa_id', $siswa->id)
                ->selectRaw("MONTH(tanggal_transaksi) as bulan,
                        SUM(CASE WHEN jenis_transaksi = 'setor' THEN jumlah ELSE 0 END) as pemasukan,
                        SUM(CASE WHEN jenis_transaksi = 'tarik' THEN jumlah ELSE 0 END) as pengeluaran")
                ->groupByRaw("MONTH(tanggal_transaksi)")
                ->orderByRaw("MONTH(tanggal_transaksi)")
                ->get();

            // Hitung saldo per bulan
            $bulanList = range(1, now()->month);

            $saldo = 0;
            $grafik = [];

            foreach ($bulanList as $bulan) {
                $item = $data->firstWhere('bulan', $bulan);
                $pemasukan = $item->pemasukan ?? 0;
                $pengeluaran = $item->pengeluaran ?? 0;
                $saldo += $pemasukan - $pengeluaran;

                $grafik[] = [
                    'bulan' => \Carbon\Carbon::create()->month($bulan)->locale('id')->monthName,
                    'saldo' => $saldo
                ];
            }

            return response()->json($grafik);
        }

        return response()->json(['message' => 'Akses ditolak'], 403);
    }
}
