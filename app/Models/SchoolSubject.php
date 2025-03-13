<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SchoolSubject extends Model
{
    use HasFactory;

    protected $table = 'school_subjects';

    protected $fillable = [
        'teacher_id',
        'name',
        'code',
        'status',
    ];
}
