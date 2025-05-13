<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class TahunPelajaran extends Model
{
    protected $guarded = ['id'];

    public function scopeAktif(Builder $query)
    {
        $query->where('status', 1);
    }

    public function semester()
    {
        return $this->belongsTo(Semester::class, 'semester_id'); // Foreign key di tabel TahunPelajaran
    }

    public function rombel()
    {
        return $this->hasMany(Rombel::class);
    }

    public function kurikulum()
    {
        return $this->hasMany(Kurikulum::class);
    }
}
