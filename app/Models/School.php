<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class School extends Model
{
    use HasFactory;

    protected $table = 'schools';

    protected $fillable = [
        'name_school',
        'logo',
        'address',
        'email',
        'telp',
        'phone',
        'postal_code',
        'accreditation',
    ];

    public function logoUrl()
    {
        return $this->logo
            ? url('storage/' . $this->logo)
            : asset('ryoogenmedia/no-image.png');
    }
}
