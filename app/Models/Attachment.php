<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Attachment extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'path'
    ];

    protected $hidden = [
        'path'
    ];

    public function attachmentable()
    {
        return $this->morphTo();
    }

    public function owner() {
        $this->belongsTo(User::class, 'owner_id');
    }

}
