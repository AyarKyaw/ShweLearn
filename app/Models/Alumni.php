<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Notifications\VerifyAlumniEmail;

class Alumni extends Authenticatable implements MustVerifyEmail
{
    use Notifiable;

    protected $table = 'alumnis';

    protected $fillable = [
        'name',
        'email',
        'course_id',
        'password',
        'image',
        'status',          
        'register_no',
        'email_verified_at', // Make sure this column exists in your `alumnis` table migration
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    public function payments()
    {
        return $this->hasMany(AlumniPayment::class, 'alumni_id');
    }

    protected static function boot()
    {
        parent::boot();

        static::created(function ($alumni) {
            if (!$alumni->register_no) {
                $alumni->register_no = 'MEA-' . str_pad($alumni->id, 6, '0', STR_PAD_LEFT);
                $alumni->saveQuietly();
            }
        });
    }

    public function sendEmailVerificationNotification(): void
    {
        $this->notify(new VerifyAlumniEmail);
    }
}