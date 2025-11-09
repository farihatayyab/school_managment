<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'class_id',
        'roll_no',
        'gender',
        'address',
        'phone',
    ];

    /**
     * Get the user record associated with the student.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }     

    /**
     * Get the class this student belongs to.
     */
    public function classRoom()
    {
        return $this->belongsTo(ClassRoom::class, 'class_id');
    }

      public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }
}
