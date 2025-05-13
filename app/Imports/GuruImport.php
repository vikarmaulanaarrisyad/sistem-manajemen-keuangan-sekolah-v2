<?php

namespace App\Imports;

use App\Models\Guru;
use App\Models\JenisKelamin;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class GuruImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        DB::beginTransaction();

        try {
            // Bersihkan nama kolom dari spasi yang tidak perlu
            $row = array_map('trim', $row);  // Remove any extra spaces from column names

            // Cek apakah jumlah kolom sesuai dengan yang diharapkan
            if (count($row) < 13) { // Sesuaikan dengan jumlah kolom di Excel
                throw new \Exception("Jumlah kolom tidak sesuai, pastikan data lengkap.");
            }

            // Validasi dan konversi tanggal lahir
            if (isset($row['tanggal_lahir_yyyy_mm_dd'])) {
                $tanggalLahir = $this->convertExcelDateToDate($row['tanggal_lahir_yyyy_mm_dd']); // Gunakan fungsi konversi
                if (!$tanggalLahir || !Carbon::hasFormat($tanggalLahir, 'Y-m-d')) {
                    throw new \Exception("Tanggal Lahir yang diberikan tidak valid.");
                }
            } else {
                throw new \Exception("Kolom 'Tanggal Lahir (YYYY-MM-DD)' tidak ditemukan.");
            }

            // Cek apakah User sudah ada berdasarkan email, jika tidak maka buat
            $user = User::where('email', $row['email'])->first();

            if (!$user) {
                $user = User::create([
                    'email' => $row['email'],
                    'name' => $row['nama_lengkap'],
                    'username' => trim($row['username']),
                    'password' => trim($row['password']) ? trim($row['password']) : Hash::make('password'),
                ]);
            }

            // Menetapkan peran sebagai 'guru'
            $user->assignRole('guru');

            // Cek apakah Guru sudah ada berdasarkan user_id
            $guru = Guru::where('user_id', $user->id)->first();

            if (!$guru) {
                $guru = Guru::create([
                    'user_id' => $user->id,
                    'nama_lengkap' => $row['nama_lengkap'],
                    'gelar_depan' => $row['gelar_depan'],
                    'gelar_belakang' => $row['gelar_belakang'],
                    'tempat_lahir' => $row['tempat_lahir'],
                    'nik' => !empty($row['nik']) ? $row['nik'] : '0',
                    'tgl_lahir' => $tanggalLahir, // Tanggal sudah divalidasi
                    'jenis_kelamin_id' => $this->getJenisKelaminId($row['jenis_kelamin']),
                    'tmt_guru' => $this->convertExcelDateToDate($row['tmt_guru_yyyy_mm_dd']),
                    'tmt_pegawai' => $this->convertExcelDateToDate($row['tmt_pegawai_yyyy_mm_dd']),
                ]);
            }

            DB::commit(); // Commit jika berhasil
        } catch (\Exception $e) {
            DB::rollBack(); // Rollback jika ada error
            throw $e; // Lemparkan error kembali untuk ditangani
        }
    }


    private function getJenisKelaminId($jenisKelamin)
    {
        // Cari data Jenis Kelamin berdasarkan nama
        $jenisKelaminRecord = JenisKelamin::firstOrCreate(
            ['nama' => $jenisKelamin]
        );

        return $jenisKelaminRecord->id;  // Kembalikan ID dari record yang ditemukan
    }

    // Fungsi untuk mengonversi Excel date serial menjadi DateTime
    private function convertExcelDateToDate($excelDate)
    {
        if (is_numeric($excelDate)) {
            // Mengonversi serial date Excel menjadi objek DateTime dengan Carbon
            $date = Date::excelToDateTimeObject($excelDate);
            return Carbon::instance($date)->format('Y-m-d'); // Format tanggal yang benar
        }

        // Jika bukan angka, pastikan itu adalah format yang valid
        $dateParts = explode('-', $excelDate);
        if (count($dateParts) == 3) {
            $year = $dateParts[0];
            $month = $dateParts[1];
            $day = $dateParts[2];

            // Periksa apakah bulan dan hari valid
            if ($month >= 1 && $month <= 12 && $day >= 1 && $day <= 31) {
                return "$year-$month-$day"; // Kembalikan tanggal dalam format yang benar
            }
        }

        // Jika tidak valid, kembalikan null atau beri nilai default
        return null;
    }
}
