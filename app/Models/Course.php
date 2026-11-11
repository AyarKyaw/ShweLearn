<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'category_id',
        'title',
        'hour',
        'price',
        'member_price',
        'desc',
        'image',
    ];

    public function getPriceForUser(?User $user = null): float
    {
        $user = $user ?? auth()->user();

        // Adjust 'is_member' to match your User model's member attribute/role check
        if ($user && $user->is_member && !is_null($this->member_price)) {
            return (float) $this->member_price;
        }

        return (float) $this->price;
    }

    public function instructors(): BelongsToMany
    {
        return $this->belongsToMany(Instructor::class);
    }

    // Relationship to Course Category
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function users()
    {
        return $this->belongsToMany(User::class)->withTimestamps();
    }

    public function units()
    {
        return $this->hasMany(Unit::class);
    }

    public function lessons()
    {
        // Traverses Course -> Unit -> Section -> Lesson
        return $this->hasManyThrough(
            Lesson::class,
            Section::class,
            'unit_id',    // Foreign key on sections table (via unit)
            'section_id'  // Foreign key on lessons table
        );
    }

    // Relationship to Enrolled Students
    public function students()
    {
        return $this->belongsToMany(User::class)->withTimestamps();
    }
    
    public function getProgressForUser($userId = null): int
    {
        $userId = $userId ?? auth()->id();
        if (!$userId) return 0;

        $totalLessons = $this->lessons()->count();
        if ($totalLessons === 0) return 0;

        $completedCount = $this->lessons()
            ->whereHas('users', function ($query) use ($userId) {
                $query->where('user_id', $userId)->where('is_completed', true);
            })->count();

        return (int) round(($completedCount / $totalLessons) * 100);
    }

    public function admins()
    {
        return $this->belongsToMany(Admin::class, 'admin_course')
                    ->withTimestamps();
    }

    public function payments()
    {
        return $this->hasMany(CoursePayment::class, 'course_id');
    }
}