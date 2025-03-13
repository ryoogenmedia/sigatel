<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeedBack extends Model
{
    use HasFactory;

    protected $table = 'feed_backs';

    protected $fillable = [
        'on_duty_id',
        'comment',
    ];

    public function on_duty(){
        return $this->belongsTo(OnDuty::class,'on_duty_id','id')->withDefault();
    }
}
