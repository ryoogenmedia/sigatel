<?php

namespace App\Models;

use Carbon\Carbon;
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

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id', 'id')->withDefault();
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class, 'teacher_id', 'id')->withDefault();
    }

    public function school_subject()
    {
        return $this->belongsTo(SchoolSubject::class, 'school_subject_id')->withDefault();
    }

    // GETTER ATTRIBUTE MODEL METHOD

    public function photoStudentUrl()
    {
        return $this->photo_student
            ? url('storage/' . $this->photo_student)
            : asset('ryoogenmedia/no-image.png');
    }

    public function documentationFileUrl()
    {
        return $this->documentation_file
            ? url('storage/' . $this->documentation_file)
            : asset('ryoogenmedia/no-image.png');
    }

    public function getJadwalPenugasanAttribute()
    {
        return Carbon::parse($this->schedule_time)->format('d/m/Y - H:i:s');
    }

    public function getJadwalSelesaiAttribute()
    {
        return Carbon::parse($this->finish_time)->format('d/m/Y - H:i:s');
    }
}
