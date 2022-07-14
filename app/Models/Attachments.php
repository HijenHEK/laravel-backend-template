<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attachments extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
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
