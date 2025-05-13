<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\JurnalGuru;
use Carbon\Carbon;

class JurnalGuruSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            // 📌 Data untuk Kurikulum Merdeka
            [
                'tahun_pelajaran_id' => 1,
                'rombel_id' => 1,
                'guru_id' => 1,
                'mata_pelajaran_id' => 1,
                'tanggal' => Carbon::now()->toDateString(),
                'pembelajaran_ke' => 1,
                'tema' => 'Pengantar Pemrograman',
                'tujuan_pembelajaran' => 'Siswa memahami dasar pemrograman',
                'materi' => 'Dasar-dasar pemrograman dengan contoh kode',
                'penilaian' => 'Quiz singkat',
                'metode_pembelajaran' => 'Diskusi dan latihan kode',
                'evaluasi' => 'Siswa memahami sintaks dasar',
                'refleksi' => 'Siswa antusias dalam belajar',
                'tugas' => 'Membuat program sederhana',
            ],
            [
                'tahun_pelajaran_id' => 1,
                'rombel_id' => 2,
                'guru_id' => 2,
                'mata_pelajaran_id' => 2,
                'tanggal' => Carbon::now()->toDateString(),
                'pembelajaran_ke' => 2,
                'tema' => 'Konsep Bilangan',
                'tujuan_pembelajaran' => 'Siswa mengenal bilangan bulat dan pecahan',
                'materi' => 'Bilangan bulat, pecahan, dan desimal',
                'penilaian' => 'Tes tertulis',
                'metode_pembelajaran' => 'Ceramah dan diskusi',
                'evaluasi' => 'Siswa dapat menghitung dengan benar',
                'refleksi' => 'Siswa membutuhkan lebih banyak latihan',
                'tugas' => 'Kerjakan 5 soal bilangan pecahan',
            ],

            // 📌 Data untuk Kurikulum 2013
            [
                'tahun_pelajaran_id' => 2,
                'rombel_id' => 3,
                'guru_id' => 3,
                'mata_pelajaran_id' => 3,
                'tanggal' => Carbon::now()->subDays(1)->toDateString(),
                'pembelajaran_ke' => 1,
                'tema' => 'Mengenal Lingkungan Sekitar',
                'tujuan_pembelajaran' => 'Siswa dapat mendeskripsikan lingkungan sekitar',
                'materi' => 'Mengenal berbagai tempat di sekitar sekolah',
                'penilaian' => 'Tugas observasi',
                'metode_pembelajaran' => 'Diskusi dan presentasi',
                'evaluasi' => 'Siswa dapat menjelaskan lingkungan sekitar',
                'refleksi' => 'Siswa berpartisipasi aktif',
                'tugas' => 'Membuat laporan lingkungan sekolah',
            ],
            [
                'tahun_pelajaran_id' => 2,
                'rombel_id' => 4,
                'guru_id' => 4,
                'mata_pelajaran_id' => 4,
                'tanggal' => Carbon::now()->subDays(2)->toDateString(),
                'pembelajaran_ke' => 2,
                'tema' => 'Bahasa Indonesia: Perkenalan Diri',
                'tujuan_pembelajaran' => 'Siswa dapat memperkenalkan diri dengan baik',
                'materi' => 'Latihan berbicara dan menulis tentang diri sendiri',
                'penilaian' => 'Ujian berbicara',
                'metode_pembelajaran' => 'Latihan berbicara di depan kelas',
                'evaluasi' => 'Siswa percaya diri dalam berbicara',
                'refleksi' => 'Siswa menikmati pembelajaran',
                'tugas' => 'Menulis paragraf tentang diri sendiri',
            ],
        ];

        foreach ($data as $item) {
            JurnalGuru::firstOrCreate([
                'tahun_pelajaran_id' => $item['tahun_pelajaran_id'],
                'rombel_id' => $item['rombel_id'],
                'guru_id' => $item['guru_id'],
                'mata_pelajaran_id' => $item['mata_pelajaran_id'],
                'tanggal' => $item['tanggal'],
                'pembelajaran_ke' => $item['pembelajaran_ke'],
                'tema' => $item['tema'],
                'tujuan_pembelajaran' => $item['tujuan_pembelajaran'],
                'materi' => $item['materi'],
                'penilaian' => $item['penilaian'],
                'metode_pembelajaran' => $item['metode_pembelajaran'],
                'evaluasi' => $item['evaluasi'],
                'refleksi' => $item['refleksi'],
                'tugas' => $item['tugas'],
            ], $item);
        }
    }
}
