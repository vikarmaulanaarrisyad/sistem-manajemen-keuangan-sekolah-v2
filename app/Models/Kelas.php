<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{
    protected $guarded = ['id'];

    public function rombel()
    {
        return $this->hasMany(Rombel::class);
    }
}
