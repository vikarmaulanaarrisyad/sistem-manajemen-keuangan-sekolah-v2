<?php

namespace App\Exports;

use App\Models\K13ButirSikap;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

/**
 * Class K13ButirSikapExport
 *
 * Kelas ini digunakan untuk mengekspor data Butir Sikap K13 ke dalam file Excel
 * dengan nomor otomatis dan tanpa ID.
 */
class K13ButirSikapExport implements FromCollection, WithHeadings, WithMapping
{
    /**
     * Mengambil semua data Butir Sikap K13 dari database.
     *
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return K13ButirSikap::select('kode', 'butir_sikap', 'jenis_kompetensi')->get();
    }

    /**
     * Menentukan heading row untuk file Excel.
     *
     * @return array
     */
    public function headings(): array
    {
        return [
            'No',
            'Kode',
            'Butir Sikap',
            'Jenis Kompetensi'
        ];
    }

    /**
     * Memetakan data untuk setiap baris dalam file Excel.
     *
     * @param mixed $row
     * @return array
     */
    public function map($row): array
    {
        static $number = 1; // Inisialisasi nomor otomatis

        return [
            $number++, // Nomor otomatis
            $row->kode,
            $row->butir_sikap,
            $row->jenis_kompetensi
        ];
    }
}
