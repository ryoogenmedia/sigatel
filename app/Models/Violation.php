<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Violation extends Model
{
    use HasFactory;

    protected $table = 'violations';

    protected $fillable = [
        'student_id',
        'teacher_id',
        'violation_type',
        'description',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id', 'id')->withDefault();
    }

    public function getTimeViolationAttribute()
    {
        $violationTime = $this->created_at;

        if ($violationTime->isToday()) {
            return $violationTime->format('H:i');
        }

        return $violationTime->format('Y-m-d H:i');
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class, 'teacher_id', 'id')->withDefault();
    }
}
