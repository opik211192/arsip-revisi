<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Unit extends Model
{
    use HasFactory;

    //protected $table = "units";
    protected $guarded = [];

    // public function user()
    // {
    //     return $this->BelongsTo(User::class, 'id', 'id');
    // }

}
