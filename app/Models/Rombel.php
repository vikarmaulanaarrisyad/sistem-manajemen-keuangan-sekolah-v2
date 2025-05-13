<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rombel extends Model
{

    protected $guarded = ['id'];

    public function tahun_pelajaran()
    {
        return $this->belongsTo(TahunPelajaran::class);
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }

    public function kurikulum()
    {
        return $this->belongsTo(Kurikulum::class);
    }

    public function walikelas()
    {
        return $this->hasOne(Guru::class, 'id', 'wali_kelas_id');
    }

    public function siswa_rombel()
    {
        return $this->belongsToMany(Siswa::class, 'siswa_rombel', 'rombel_id', 'siswa_id')
            ->withPivot('tahun_pelajaran_id')
            ->withTimestamps();
    }

    public function pembelajaran()
    {
        return $this->hasMany(Pembelajaran::class, 'rombel_id', 'id');
    }
}
