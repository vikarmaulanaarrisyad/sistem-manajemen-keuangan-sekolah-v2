<?php

namespace App\Imports;

use App\Models\Agama;
use App\Models\JenisKelamin;
use App\Models\OrangTua;
use App\Models\Siswa;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class SiswaImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        if (empty($row['nama_lengkap']) || empty($row['tanggal_lahir_yyyy_mm_dd']) || empty($row['agama']) || empty($row['jenis_kelamin'])) {
            return null;
        }

        DB::beginTransaction();
        try {
            $this->prosesDataSiswa($row);
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

    /**
     * Proses data siswa: Insert atau Update
     */
    private function prosesDataSiswa(array $row)
    {
        $nisn = !empty($row['nisn']) ? $row['nisn'] : 0; // Jika NISN kosong/null, set ke 0

        if ($nisn != 0) {
            $siswa = Siswa::updateOrCreate(
                ['nisn' => $nisn, 'nis' => $row['nis']],
                [
                    'nama_lengkap' => $row['nama_lengkap'] ?? '',
                    'nama_panggilan' => $row['nama_panggilan'] ?? '',
                    'nik' => $row['nik'] ?? null,
                    'kk' => $row['kk'] ?? null,
                    'jenis_kelamin_id' => $this->getJenisKelaminId($row['jenis_kelamin'] ?? ''),
                    'tempat_lahir' => $row['tempat_lahir'] ?? '',
                    'tgl_lahir' => !empty($row['tanggal_lahir_yyyy_mm_dd']) ? $this->convertExcelDateToDate($row['tanggal_lahir_yyyy_mm_dd']) : null,
                    'agama_id' => $this->getAgamaId($row['agama'] ?? ''),
                    'jumlah_saudara' => $row['jumlah_saudara'] ?? 0,
                    'anak_ke' => $row['anak_ke'] ?? 0,
                    'alamat' => $row['alamat'] ?? '-',
                    'level' => $row['kelas'] ?? 1,
                    'kewarganegaraan_id' => 1,
                ]
            );
        } else {
            $siswa = Siswa::updateOrCreate(
                ['nis' => $row['nis']],
                [
                    'nisn' => 0,
                    'nama_lengkap' => $row['nama_lengkap'] ?? '',
                    'nama_panggilan' => $row['nama_panggilan'] ?? '',
                    'nik' => $row['nik'] ?? null,
                    'kk' => $row['kk'] ?? null,
                    'jenis_kelamin_id' => $this->getJenisKelaminId($row['jenis_kelamin'] ?? ''),
                    'tempat_lahir' => $row['tempat_lahir'] ?? '',
                    'tgl_lahir' => !empty($row['tanggal_lahir_yyyy_mm_dd']) ? $this->convertExcelDateToDate($row['tanggal_lahir_yyyy_mm_dd']) : null,
                    'agama_id' => $this->getAgamaId($row['agama'] ?? ''),
                    'jumlah_saudara' => $row['jumlah_saudara'] ?? 0,
                    'anak_ke' => $row['anak_ke'] ?? 0,
                    'alamat' => $row['alamat'] ?? '-',
                    'level' => $row['kelas'] ?? 1,
                    'kewarganegaraan_id' => 1,
                ]
            );
        }

        // === Buat akun user ===
        $email = !empty($row['email']) ? $row['email'] : 'siswa' . $nisn . '@mail.com';
        $username = $nisn;

        $user = User::updateOrCreate(
            ['username' => $username],
            [
                'name' => $row['nama_lengkap'] ?? '',
                'email' => $email,
                'username' => $username,
                'password' => Hash::make($nisn), // default password
            ]
        );

        // Tambah role siswa jika belum
        if (!$user->hasRole('siswa')) {
            $user->assignRole('siswa');
        }

        // Set user_id ke siswa
        $siswa->user_id = $user->id;
        $siswa->save();

        // === Proses data orang tua ===
        $this->prosesDataOrangTua($siswa->id, $row);
    }

    /**
     * Proses data orang tua atau wali
     */
    private function prosesDataOrangTua($siswaId, array $row)
    {
        OrangTua::updateOrCreate(
            ['siswa_id' => $siswaId],
            [
                'nama_ayah' => $row['nama_ayah'] ?? '',
                'nama_ibu' => $row['nama_ibu'] ?? '',
                'nama_walimurid' => $row['wali_murid'] ?? '',
                'pendidikan_ayah_id' => $row['pendidikan_ayah'] ?? 0,
                'pendidikan_ibu_id' => $row['pendidikan_ibu'] ?? 0,
                'pendidikan_walimurid_id' => $row['pendidikan_wali'] ?? 0,
                'pekerjaan_ayah_id' => $row['pekerjaan_ayah'] ?? 0,
                'pekerjaan_ibu_id' => $row['pekerjaan_ibu'] ?? 0,
                'pekerjaan_walimurid_id' => $row['pekerjaan_wali'] ?? 0,
                'hubungan_siswa_wali' => $row['hubungan_siswa_wali'] ?? '',
            ]
        );
    }

    /**
     * Mendapatkan ID Jenis Kelamin
     */
    private function getJenisKelaminId($jenisKelamin)
    {
        return strtolower($jenisKelamin) === 'perempuan' ? 2 : 1;
    }

    /**
     * Mendapatkan ID Agama
     */
    private function getAgamaId($agama)
    {
        return strtolower($agama) === 'islam' ? 1 : 2;
    }

    /**
     * Konversi format tanggal dari Excel ke Y-m-d
     */
    private function convertExcelDateToDate($excelDate)
    {
        if (!$excelDate || !is_numeric($excelDate)) {
            return null;
        }

        return \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($excelDate)->format('Y-m-d');
    }
}
