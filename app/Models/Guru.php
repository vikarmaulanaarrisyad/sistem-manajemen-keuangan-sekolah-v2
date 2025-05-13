<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Guru extends Model
{
    use Notifiable; // Tambahkan ini agar bisa menerima notifikasi
    protected $guarded = ['id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function jenis_kelamin()
    {
        return $this->belongsTo(JenisKelamin::class);
    }

    public function rombel()
    {
        return $this->belongsTo(Rombel::class);
    }
}
