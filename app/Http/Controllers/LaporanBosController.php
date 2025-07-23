<?php

namespace App\Http\Controllers;

use App\Models\PemasukanBos;
use App\Models\PengeluaranBos;
use App\Models\TahunPelajaran;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Illuminate\Http\Request;

class LaporanBosController extends Controller
{
    public function index(Request $request)
    {
        $start = $request->input('start', now()->startOfMonth()->toDateString());
        $end = $request->input('end', now()->endOfMonth()->toDateString());
        $tahun_pelajaran_id = $request->input('tahun_pelajaran_id');

        $tahun_pelajaran = TahunPelajaran::all(); // Dropdown filter

        return view('laporan.bos.index', compact('start', 'end', 'tahun_pelajaran_id', 'tahun_pelajaran'));
    }

    private function getData($start, $end, $escape = false, $tahun_pelajaran_id = null)
    {
        $data = [];
        $i = 1;
        $sisa_kas = 0;
        $total_sisa_kas = 0;

        while (strtotime($start) <= strtotime($end)) {
            $queryPemasukan = PemasukanBos::whereDate('tanggal_terima', $start);
            $queryPengeluaran = PengeluaranBos::whereDate('tanggal_pengeluaran', $start);

            if ($tahun_pelajaran_id) {
                $queryPemasukan->where('tahun_pelajaran_id', $tahun_pelajaran_id);
                $queryPengeluaran->where('tahun_pelajaran_id', $tahun_pelajaran_id);
            }

            $pemasukan = $queryPemasukan->sum('jumlah');
            $pengeluaran = $queryPengeluaran->sum('jumlah');
            $total = $pemasukan - $pengeluaran;
            $sisa_kas += $total;
            $total_sisa_kas += $total;

            // Ambil keterangan sesuai tipe data
            $pemasukanModel = $queryPemasukan->first();
            $pengeluaranModel = $queryPengeluaran->first();

            if ($pemasukan > 0) {
                $firstPemasukan = $queryPemasukan->first();
                $keterangan = $firstPemasukan?->keterangan ?? '-';
            } elseif ($pengeluaran > 0) {
                $firstPengeluaran = $queryPengeluaran->first();
                $keterangan = $firstPengeluaran?->uraian ?? '-';
            } else {
                $keterangan = '-';
            }

            $separate = $escape ? ',-' : '';

            $data[] = [
                'DT_RowIndex' => $i++,
                'tanggal' => tanggal_indonesia($start),
                'pemasukan' => format_uang($pemasukan) . $separate,
                'pengeluaran' => format_uang($pengeluaran) . $separate,
                'sisa' => format_uang($sisa_kas) . $separate,
                'keterangan' => $keterangan,
            ];

            $start = date('Y-m-d', strtotime('+1 day', strtotime($start)));
        }

        $data[] = [
            'DT_RowIndex' => '',
            'tanggal' => '',
            'pemasukan' => '',
            'pengeluaran' => !$escape ? '<strong>Total Kas</strong>' : 'Total Kas',
            'sisa' => !$escape ? format_uang($total_sisa_kas) : format_uang($total_sisa_kas) . $separate,
            'keterangan' => '',
        ];

        return $data;
    }


    public function data(Request $request)
    {
        $request->validate([
            'start' => 'required|date',
            'end' => 'required|date|after_or_equal:start',
            'tahun_pelajaran_id' => 'nullable|exists:tahun_pelajarans,id',
        ]);

        $start = $request->start;
        $end = $request->end;
        $tahun_pelajaran_id = $request->tahun_pelajaran_id;

        $data = $this->getData($start, $end, false, $tahun_pelajaran_id);

        return response()->json(['data' => $data]);
    }

    public function exportPDF($start, $end, Request $request)
    {
        $tahun_pelajaran_id = $request->tahun_pelajaran_id;
        $tahun_pelajaran = TahunPelajaran::find($tahun_pelajaran_id);

        $data = $this->getData($start, $end, false, $tahun_pelajaran_id);
        $pdf = PDF::loadView('laporan.bos.report', compact('start', 'end', 'data', 'tahun_pelajaran'));

        return $pdf->stream('Laporan-DanaBos-' . date('Y-m-d-his') . '.pdf');
    }
}
