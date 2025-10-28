<?php

namespace App\Models;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Arsip extends Model
{
    use HasFactory;

    protected $guarded = [];

    // 🔹 Event otomatis isi created_by & updated_by
    protected static function booted()
    {
        static::creating(function ($arsip) {
            $arsip->created_by = Auth::id();
        });

        static::updating(function ($arsip) {
            $arsip->updated_by = Auth::id();
        });
    }

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

    public function getCreatedAtAttribute()
    {
        return Carbon::parse($this->attributes['created_at'])->format('d-m-Y H:i:s');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
    
    // ini upload multiple
    public function uploads()
    {
        return $this->hasMany(ArsipUpload::class, 'arsip_id');
    }
}
