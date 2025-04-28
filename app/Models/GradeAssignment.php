<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GradeAssignment extends Model
{
    use HasFactory;

    protected $table = 'grade_assignments';

    protected $fillable = [
        'grade_id',
        'teacher_id',
        'school_subject_id',
        'longitude',
        'latitude',
        'reason_teacher',
        'description',
        'status',
        'documentation_image',
        'file_assignment',
        'schedule_time',
        'finish_time',
    ];

    public function grade(){
        return $this->belongsTo(Grade::class,'grade_id','id')->withDefault();
    }

    public function teacher(){
        return $this->belongsTo(Teacher::class,'teacher_id','id')->withDefault();
    }

    public function school_subject(){
        return $this->belongsTo(SchoolSubject::class,'school_subject_id','id')->withDefault();
    }

    public function getScheduleAttribute(){
        return $this->schedule_time ? Carbon::parse($this->schedule_time)->format('d/m/Y H:i:s') : null;
    }

    public function getFinishAttribute(){
        return $this->finish_time ? Carbon::parse($this->finish_time)->format('d/m/Y H:i:s') : null;
    }
}
