<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Arsip extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function jenis()
    {
        return $this->belongsTo(Jenis::class);
    }
    
    public function jenis_arsip()
    {
        return $this->belongsTo(JenisArsip::class);
    }
    
    public function struktural_detail()
    {
        return $this->belongsTo(Struktural_detail::class, 'id_pencipta_arsip', 'id');
    }
    
}
