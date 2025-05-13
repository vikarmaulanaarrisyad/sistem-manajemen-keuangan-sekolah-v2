<?php

namespace App\Imports;

use App\Models\Agama;
use App\Models\JenisKelamin;
use App\Models\Siswa;
use App\Models\OrangTua;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class SiswaImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        DB::beginTransaction();

        try {
            // Konversi nilai numerik ke string atau integer agar sesuai dengan tipe data
            $row = array_map(function ($value) {
                return is_numeric($value) ? (string) $value : trim($value);
            }, $row);

            // Pastikan kolom 'nisn' ada
            if (!isset($row['nisn']) || empty($row['nisn'])) {
                throw new \Exception("Kolom 'nisn' tidak ditemukan atau kosong dalam file Excel.");
            }

            // Cek apakah siswa sudah ada berdasarkan NISN
            $siswa = Siswa::where('nisn', (int) $row['nisn'])->first();

            if (!$siswa) {
                // Buat siswa baru
                $siswa = Siswa::create([
                    'nama_lengkap' => $row['nama_lengkap'],
                    'nama_panggilan' => $row['nama_panggilan'],
                    'nis' => (int) $row['nis'],
                    'nik' => (string) $row['nik'],
                    'kk' => (string) $row['kk'],
                    'jenis_kelamin_id' => $this->getJenisKelaminId($row['jenis_kelamin']),
                    'kewarganegaraan_id' => 1,
                    'tempat_lahir' => $row['tempat_lahir'],
                    'tgl_lahir' => $this->convertExcelDateToDate($row['tanggal_lahir_yyyy_mm_dd']),
                    'agama_id' => $this->getAgamaId($row['agama']),
                    'jumlah_saudara' => (int) $row['jumlah_saudara'],
                    'anak_ke' => (int) $row['anak_ke'],
                    'alamat' => $row['alamat'] ?? '',
                    'kelas' => (int) $row['kelas'],
                ]);
            }

            // Cek apakah Orang Tua sudah ada berdasarkan siswa_id
            $orangTua = OrangTua::where('siswa_id', $siswa->id)->first();
            if (!$orangTua) {
                OrangTua::create([
                    'siswa_id' => $siswa->id,
                    'nama_ayah' => $row['nama_ayah'],
                    'nama_ibu' => $row['nama_ibu'],
                    'nama_walimurid' => $row['wali_murid'],
                    'pendidikan_ayah_id' => (int) $row['pendidikan_ayah'],
                    'pendidikan_ibu_id' => (int) $row['pendidikan_ibu'],
                    'pendidikan_walimurid_id' => (int) $row['pendidikan_wali'],
                    'pekerjaan_ayah_id' => (int) $row['pekerjaan_ayah'],
                    'pekerjaan_ibu_id' => (int) $row['pekerjaan_ibu'],
                    'pekerjaan_walimurid_id' => (int) $row['pekerjaan_wali'],
                    'hubungan_siswa_wali' => $row['hubungan_siswa_wali'],
                ]);
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    private function getJenisKelaminId($jenisKelamin)
    {
        return JenisKelamin::firstOrCreate(['nama' => $jenisKelamin])->id;
    }

    private function getAgamaId($agama)
    {
        return Agama::firstOrCreate(['nama' => $agama])->id;
    }

    private function convertExcelDateToDate($excelDate)
    {
        if (is_numeric($excelDate)) {
            return Carbon::instance(Date::excelToDateTimeObject($excelDate))->format('Y-m-d');
        }

        $dateParts = explode('-', $excelDate);
        return count($dateParts) === 3 ? "$dateParts[0]-$dateParts[1]-$dateParts[2]" : null;
    }
}
