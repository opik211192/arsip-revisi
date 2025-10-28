<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArsipUpload extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function arsip()
    {
        return $this->belongsTo(Arsip::class);
    }
}
