<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Lesson;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'email_verified_at',
        'google_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function coursePayments()
    {
        return $this->hasMany(CoursePayment::class, 'user_id');
    }

    public function studentProfile()
    {
        return $this->hasOne(StudentProfile::class);
    }

    public function courses()
    {
        return $this->belongsToMany(Course::class)->withTimestamps();
    }

    /**
     * Base relationship for attaching/updating pivot records
     */
    public function lessons()
    {
        return $this->belongsToMany(Lesson::class, 'lesson_user')
                    ->withPivot(
                        'course_id', 
                        'is_completed', 
                        'progress_percent', 
                        'quiz_score', 
                        'completed_at',
                        'homework_file_path',
                        'submitted_at'
                    )
                    ->withTimestamps();
    }

    /**
     * Filtered relationship for reading completed lessons
     */
    public function completedLessons()
    {
        return $this->belongsToMany(Lesson::class, 'lesson_user')
                    ->wherePivot('is_completed', true)
                    ->withPivot('course_id', 'progress_percent', 'quiz_score', 'completed_at')
                    ->withTimestamps();
    }
}