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
}
