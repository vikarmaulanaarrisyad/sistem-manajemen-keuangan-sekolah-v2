<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\Rombel;
use App\Models\Siswa;
use App\Models\TransaksiTabungan;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Illuminate\Http\Request;

class LaporanTabunganController extends Controller
{
    public function index(Request $request)
    {
        $start = $request->input('start', now()->startOfMonth()->toDateString());
        $end = $request->input('end', now()->endOfMonth()->toDateString());
        $siswa_id = $request->input('siswa_id');

        $kelas = Kelas::all(); // Kirim semua kelas untuk dropdown

        return view('laporan.index', compact('start', 'end', 'siswa_id', 'kelas'));
    }

    public function data(Request $request)
    {
        $request->validate([
            'start' => 'required|date',
            'end' => 'required|date|after_or_equal:start',
            'siswa_id' => 'nullable|exists:siswas,id',
        ]);

        $start = $request->start;
        $end = $request->end;
        $siswa_id = $request->siswa_id;

        $data = $this->getData($start, $end, false, $siswa_id);

        return response()->json(['data' => $data]);
    }


    private function getData($start, $end, $escape = false, $siswa_id = null)
    {
        $data = [];
        $i = 1;
        $sisa_kas = 0;
        $total_sisa_kas = 0;

        while (strtotime($start) <= strtotime($end)) {
            $querySetor = TransaksiTabungan::where('jenis_transaksi', 'setor')
                ->whereDate('tanggal_transaksi', $start);
            $queryTarik = TransaksiTabungan::where('jenis_transaksi', 'tarik')
                ->whereDate('tanggal_transaksi', $start);

            if ($siswa_id) {
                $querySetor->where('siswa_id', $siswa_id);
                $queryTarik->where('siswa_id', $siswa_id);
            }

            $setoran = $querySetor->sum('jumlah');
            $penarikan = $queryTarik->sum('jumlah');

            $total = $setoran - $penarikan;
            $sisa_kas += $total;
            $total_sisa_kas += $total;

            // Ambil keterangan dari salah satu transaksi (setor/tarik)
            $keterangan = null;
            $transaksiSetor = $querySetor->first();
            if ($transaksiSetor) {
                $keterangan = $transaksiSetor->keterangan;
            } else {
                $transaksiTarik = $queryTarik->first();
                if ($transaksiTarik) {
                    $keterangan = $transaksiTarik->keterangan;
                }
            }

            $separate = $escape ? ',-' : '';

            $data[] = [
                'DT_RowIndex' => $i++,
                'tanggal' => tanggal_indonesia($start),
                'pemasukan' => format_uang($setoran) . $separate,
                'pengeluaran' => format_uang($penarikan) . $separate,
                'sisa' => format_uang($sisa_kas) . $separate,
                'keterangan' => $keterangan ?? '-'
            ];

            $start = date('Y-m-d', strtotime('+1 day', strtotime($start)));
        }

        $data[] = [
            'DT_RowIndex' => '',
            'tanggal' => '',
            'pemasukan' => '',
            'pengeluaran' => !$escape ? '<strong>Total Kas</strong>' : 'Total Kas',
            'sisa' => !$escape ? '<strong>' . format_uang($total_sisa_kas) . '</strong>' : format_uang($total_sisa_kas) . $separate,
            'keterangan' => '',
        ];

        return $data;
    }

    // ✅ AJAX: Get Rombel by Kelas
    public function getRombels(Request $request)
    {
        return Rombel::where('kelas_id', $request->kelas_id)->get();
    }

    // ✅ AJAX: Get Siswa by Rombel
    public function getSiswas(Request $request)
    {
        $rombel = Rombel::with('siswa_rombel')->find($request->rombel_id);

        if (!$rombel) {
            return response()->json([]);
        }

        return response()->json($rombel->siswa_rombel);
    }

    public function exportPDF($start, $end, Request $request)
    {
        $siswa_id = $request->siswa_id;
        $siswa = Siswa::with('siswa_rombel')->where('id', $siswa_id)->first();

        $data = $this->getData($start, $end, false, $siswa_id);
        $pdf = PDF::loadView('laporan.report', compact('start', 'end', 'data', 'siswa'));

        return $pdf->stream('Laporan-Tabungan-' . date('Y-m-d-his') . '.pdf');
    }
}
