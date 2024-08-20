<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseClass extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'description', 'assessment', 'date', 'code', 'exam_date', 'day_time',
        'term_id', 'day_time', 'imgUrl'];

    protected $casts = ['day_time' => 'array'];

    public function term()
    {
        return $this->belongsTo(Term::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'class_user')->withPivot('role');
    }
    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function resource() {
        return $this->hasOne(CourseResource::class);
    }

    public function project() {
        return $this->hasOne(Project::class);
    }

    public function homeWorks() {
        return $this->hasMany(HomeWork::class);
    }
}
