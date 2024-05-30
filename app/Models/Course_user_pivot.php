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
=======
<<<<<<< HEAD
        'user_id',
        'course_id'
    ];

=======
>>>>>>> 39c884d2eaa72acbef786d005209749c741d1ed1
      'user_id' ,
      'course_id',
      'paid',
      'favorite',
    ];
<<<<<<< HEAD
=======
>>>>>>> 5f4ddeb85994744d46e3bca82b42359cff2435b1
>>>>>>> 39c884d2eaa72acbef786d005209749c741d1ed1
    public function user() : belongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function course() : belongsTo
    {
        return $this->belongsTo(Course::class);
    }
}
