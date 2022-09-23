<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Struktural_detail extends Model
{
    use HasFactory;
    protected $guarded = [];

    Public function struktural()
    {
        return $this->belongsTo(Struktural::class);
    }

}
