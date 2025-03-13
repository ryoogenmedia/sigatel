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

    public function teacher()
    {
        return $this->belongsTo(Teacher::class, 'teacher_id', 'id')->withDefault();
    }

    public function on_duties()
    {
        return $this->hasMany(OnDuty::class, 'shool_subject_id', 'id');
    }
}
