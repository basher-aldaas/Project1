<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class User_video_pivot extends Model
{
    use HasFactory;

    public function user() : belongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function video() : belongsTo
    {
        return $this->belongsTo(Video::class);
    }
}
