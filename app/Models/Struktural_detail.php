<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Struktural_detail extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function struktural()
    {
        return $this->belongsTo(Struktural::class);
    }

    public function arsips()
    {
        return $this->belongsTo(Arsip::class, 'id', 'id_pencipta_arsip');
    }

}
