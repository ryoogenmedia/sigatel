<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Grade extends Model
{
    use HasFactory;

    protected $table = 'grades';

    protected $fillable = [
        'teacher_id',
        'name',
        'floor',
    ];

    public function teacher()
    {
        return $this->belongsTo(Teacher::class, 'teacher_id', 'id')->withDefault();
    }

    public function students()
    {
        return $this->hasMany(Student::class, 'grade_id', 'id');
    }

    // GETTER ATTRIBUTE MODEL METHOD
    public function getTotalStudentAttribute()
    {
        return $this->students()
            ->count() ?? 0;
    }
}
