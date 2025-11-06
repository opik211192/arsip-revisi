<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArsipDraftUpload extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function ArsipDraft()
    {
        return $this->belongsTo(ArsipDraft::class, 'arsip_draft_id', 'id');
    }
}
