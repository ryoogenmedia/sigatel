<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OnDuty extends Model
{
    use HasFactory;

    protected $table = 'on_duties';

    protected $fillable = [
        'student_id',
        'teacher_id',
        'school_subject_id',
        'photo_student',
        'longitude',
        'latitude',
        'description',
        'status',
        'violation_type',
        'documentation_file',
        'schedule_time',
        'finish_time',
    ];
}
