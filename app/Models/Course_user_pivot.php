<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Course_user_pivot extends Model
{
    use HasFactory;
    protected $fillable = [
<<<<<<< HEAD
        'user_id',
        'course_id'
    ];

=======
      'user_id' ,
      'course_id',
      'paid',
      'favorite',
    ];
>>>>>>> 5f4ddeb85994744d46e3bca82b42359cff2435b1
    public function user() : belongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function course() : belongsTo
    {
        return $this->belongsTo(Course::class);
    }
}
