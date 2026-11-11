<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'phone',
        'viber_phone',
        'gender',
        'membership_status',
        'member_code',
        'nrc_number',
        'company',
        'job_title',
        'passport_photo',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function coursePayments()
    {
        return $this->hasMany(CoursePayment::class, 'student_id');
    }

    
}