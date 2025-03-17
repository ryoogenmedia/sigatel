<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $table = 'students';

    protected $fillable = [
        'user_id',
        'grade_id',
        'name',
        'phone_number',
        'sex',
        'address',
        'status',
    ];

    public function parent()
    {
        return $this->hasOne(StudentParent::class, 'student_id', 'id')->withDefault();
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id')->withDefault();
    }

    public function grade()
    {
        return $this->belongsTo(Grade::class, 'grade_id', 'id')->withDefault();
    }

    public function on_duties()
    {
        return $this->hasMany(OnDuty::class, 'student_id', 'id');
    }

    // GETTER ATTRIBUTE METHOD MODEL
    public function getTotalDutyAttribute()
    {
        return $this->on_duties()->count();
    }

    public function getTotalPendingDutyAttribute()
    {
        return $this->on_duties()->where('status', 'pending')->count();
    }

    public function getTotalApprovedDutyAttribute()
    {
        return $this->on_duties()->where('status', 'approved')->count();
    }

    public function getTotalRejectDutyAttribute()
    {
        return $this->on_duties()->where('status', 'reject')->count();
    }
}
