<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Semester extends Model
{
    public function tahun_pelajaran()
    {
        return $this->hasMany(TahunPelajaran::class);
    }
}
