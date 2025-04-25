<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    use HasFactory;

    protected $table = 'teachers';

    protected $fillable = [
        'user_id',
        'name',
        'phone_number',
        'status_piket',
        'address',
        'duty_status',
        'sex',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id')->withDefault();
    }

    public function grade()
    {
        return $this->hasOne(Grade::class, 'teacher_id', 'id')->withDefault();
    }

    public function on_duties()
    {
        return $this->hasMany(OnDuty::class, 'teacher_id', 'id');
    }
}
