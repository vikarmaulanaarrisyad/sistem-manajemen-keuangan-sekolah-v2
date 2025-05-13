<?php

namespace App\Exports;

use App\Models\Guru;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class GuruExport implements FromCollection, WithHeadings, WithMapping, WithColumnFormatting, WithEvents
{
    private $no = 0; // Nomor urut manual

    /**
     * Ambil data guru
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Guru::with('jenis_kelamin', 'user')->get();
    }

    /**
     * Tambahkan heading (judul kolom)
     * @return array
     */
    public function headings(): array
    {
        return [
            'No.',
            'Nama Lengkap',
            'NIK',
            'Tempat Lahir',
            'Tanggal Lahir',
            'Jenis Kelamin',
            'TMT Guru',
            'TMT Pegawai',
        ];
    }

    /**
     * Format setiap baris data sebelum diekspor
     * @param $guru
     * @return array
     */
    public function map($guru): array
    {
        return [
            ++$this->no, // Nomor urut manual
            $guru->nama_lengkap,
            $guru->nik,
            $guru->tempat_lahir,
            $guru->tgl_lahir,
            $guru->jenis_kelamin->nama ?? '',
            $guru->tmt_guru,
            $guru->tmt_pegawai
        ];
    }

    /**
     * Atur format kolom (misalnya format angka, tanggal, dll)
     * @return array
     */
    public function columnFormats(): array
    {
        return [
            'C' => NumberFormat::FORMAT_TEXT, // NIK sebagai teks
            'E' => NumberFormat::FORMAT_DATE_YYYYMMDD, // Format tanggal
            'G' => NumberFormat::FORMAT_DATE_YYYYMMDD, // Format tanggal
            'H' => NumberFormat::FORMAT_DATE_YYYYMMDD, // Format tanggal
        ];
    }

    /**
     * Atur lebar kolom secara otomatis
     * @return array
     */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                foreach (range('A', 'H') as $column) {
                    $event->sheet->getColumnDimension($column)->setAutoSize(true);
                }
            },
        ];
    }
}
