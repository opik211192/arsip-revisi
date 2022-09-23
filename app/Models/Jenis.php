<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jenis extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function arsips()
    {
        return $this->hasMany(Arsip::class);
    }
}
