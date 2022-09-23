<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Struktural extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function struktural_detail()
    {
       return $this->hasMany(Struktural_detail::class);
    }
}
