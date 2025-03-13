<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentParent extends Model
{
    use HasFactory;

    protected $table = 'student_parents';

    protected $fillable = [
        'user_id',
        'student_id',
        'name',
        'phone_number',
        'guardian_status',
    ];
}
