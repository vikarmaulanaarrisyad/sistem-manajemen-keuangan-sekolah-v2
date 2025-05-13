<?php

namespace App\Exports;

use App\Models\AbsenGuru;
use App\Models\AbsensiGuru;
use App\Models\HariLibur;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PresensiGuruExport implements FromCollection, WithHeadings, WithStyles
{
    protected $request;

    public function __construct($request)
    {
        $this->request = $request;
    }

    public function collection()
    {
        $bulan = $this->request->bulan ?? Carbon::now()->format('m'); // Bulan dari request atau bulan ini
        $tahun = Carbon::now()->format('Y'); // Tahun sekarang

        $tanggalAwal = Carbon::createFromFormat('Y-m-d', "$tahun-$bulan-01");
        $tanggalAkhir = $tanggalAwal->copy()->endOfMonth(); // Akhir bulan

        // Ambil daftar hari libur dari database
        $hariLiburDB = HariLibur::whereYear('tanggal', $tahun)
            ->whereMonth('tanggal', $bulan)
            ->pluck('keterangan', 'tanggal'); // Format: ['2024-08-17' => 'Hari Kemerdekaan']

        // Ambil data presensi dari AbsensiGuru berdasarkan bulan dan tahun
        $presensi = AbsensiGuru::whereYear('tgl_presensi', $tahun)
            ->whereMonth('tgl_presensi', $bulan)
            ->get()
            ->keyBy('tgl_presensi'); // Key menggunakan tanggal presensi

        $data = collect();

        for ($tanggal = $tanggalAwal; $tanggal->lte($tanggalAkhir); $tanggal->addDay()) {
            $hari = $tanggal->translatedFormat('l'); // Nama hari
            $tglStr = $tanggal->format('Y-m-d');

            // Cek apakah hari Minggu atau ada di database HariLibur
            $keterangan = $tanggal->isSunday() ? 'Libur' : ($hariLiburDB[$tglStr] ?? 'Belum Absen');

            // Ambil data presensi jika ada
            $absensi = $presensi[$tglStr] ?? null;
            $jamMasuk = $absensi?->waktu_masuk ? Carbon::parse($absensi->waktu_masuk)->format('H.i') : '';
            $jamPulang = $absensi?->waktu_keluar ? Carbon::parse($absensi->waktu_keluar)->format('H.i') : '';

            $data->push([
                'No' => $data->count() + 1,
                'Tanggal' => $tanggal->format('d-m-Y'),
                'Hari' => $hari,
                'Jam Masuk' => $jamMasuk,
                'Jam Pulang' => $jamPulang,
                'Keterangan' => $keterangan,
            ]);
        }

        return $data;
    }

    public function headings(): array
    {
        return ['No', 'Tanggal', 'Hari', 'Jam Masuk', 'Jam Pulang', 'Keterangan'];
    }

    public function styles(Worksheet $sheet)
    {
        foreach (range('A', 'F') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $highestRow = $sheet->getHighestRow(); // Dapatkan jumlah baris terakhir
        $highestColumn = $sheet->getHighestColumn(); // Kolom terakhir (misalnya: 'F')

        // Terapkan border ke seluruh data yang ada di dalam tabel
        $sheet->getStyle("A1:{$highestColumn}{$highestRow}")->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => '000000'], // Warna hitam
                ],
            ],
        ]);

        for ($row = 2; $row <= $highestRow; $row++) { // Mulai dari baris ke-2 (Data)
            $cellTanggal = 'B' . $row; // Kolom Tanggal
            $cellHari = 'C' . $row; // Kolom Hari
            $cellJamMasuk = 'D' . $row; // Kolom JamMasuk
            $cellJamKeluar = 'E' . $row; // Kolom JamKeluar
            $cellKeterangan = 'F' . $row; // Kolom Keterangan

            // Ambil nilai dari sel Keterangan
            $value = $sheet->getCell($cellKeterangan)->getValue();

            if ($value == 'Belum Absen') {
                // Warna merah untuk "Belum Absen" (Tanggal, Hari, dan Keterangan)
                $sheet->getStyle($cellTanggal)->getFont()->getColor()->setARGB('FF0000');
                $sheet->getStyle($cellHari)->getFont()->getColor()->setARGB('FF0000');
                $sheet->getStyle($cellKeterangan)->getFont()->getColor()->setARGB('FF0000');
            } elseif ($value == 'Libur' || strpos($value, 'Hari') !== false) {
                // Warna kuning untuk "Libur" (Tanggal, Hari, dan Keterangan)
                $sheet->getStyle($cellTanggal)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                    ->getStartColor()->setARGB('FFFF00');
                $sheet->getStyle($cellHari)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                    ->getStartColor()->setARGB('FFFF00');
                $sheet->getStyle($cellJamMasuk)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                    ->getStartColor()->setARGB('FFFF00');
                $sheet->getStyle($cellJamKeluar)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                    ->getStartColor()->setARGB('FFFF00');
                $sheet->getStyle($cellKeterangan)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                    ->getStartColor()->setARGB('FFFF00');
            }
        }
    }
}
