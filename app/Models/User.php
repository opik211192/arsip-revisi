<?php

namespace App\Models;

use App\Models\Unit;
use App\Models\Struktur;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, HasRoles, HasFactory, Notifiable;

    // /protected $table = "units";

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    // protected $fillable = [
    //     'name',
    //     'email',
    //     'password',
    //     'username',
    //     'unit_id',
    // ];
    protected $guarded = [];
    
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function arsips()
    {
        return $this->hasMany(Arsip::class);
    }

    public function struktural()
    {
        return $this->belongsTo(Struktural::class);
    }

    public function struktural_detail()
    {
        return $this->belongsTo(Struktural_detail::class, 'struktural_detail_id', 'id');
    }

}
