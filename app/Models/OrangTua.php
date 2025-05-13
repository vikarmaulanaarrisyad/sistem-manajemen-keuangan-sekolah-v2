<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrangTua extends Model
{
    protected $table = 'orang_tuas';
    protected $guarded = ['id'];

    public function pendidikan_ayah()
    {
        return $this->belongsTo(Pendidikan::class, 'pendidikan_ayah_id');
    }

    public function pendidikan_ibu()
    {
        return $this->belongsTo(Pendidikan::class, 'pendidikan_ibu_id');
    }

    public function pendidikan_walimurid()
    {
        return $this->belongsTo(Pendidikan::class, 'pendidikan_walimurid_id');
    }

    public function pekerjaan_ayah()
    {
        return $this->belongsTo(Pekerjaan::class, 'pekerjaan_ayah_id');
    }

    public function pekerjaan_ibu()
    {
        return $this->belongsTo(Pekerjaan::class, 'pekerjaan_ibu_id');
    }

    public function pekerjaan_walimurid()
    {
        return $this->belongsTo(Pekerjaan::class, 'pekerjaan_walimurid_id');
    }
}
