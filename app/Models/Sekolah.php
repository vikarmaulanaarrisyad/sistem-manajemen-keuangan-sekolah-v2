<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sekolah extends Model
{
    protected $guarded = ['id'];

    public function kepala_madrasah()
    {
        return $this->belongsTo(Guru::class, 'kepala_sekolah_id', 'id');
    }
}
