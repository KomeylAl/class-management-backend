<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;
    protected $fillable = ['course_class_id', 'user_id', 'date', 'attendance'];

    public function class()
    {
        return $this->belongsTo(CourseClass::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
