<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransaksiTabungan extends Model
{
    protected $guarded = ['id'];

    // Relasi ke model Siswa
    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }
}
