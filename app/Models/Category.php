<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_category_id',
        'name',
        'slug',
        'icon_path',
        'description',
        'is_active',
    ];

    /**
     * Relationship: Every Category belongs to a CourseCategory (Top Tier).
     */
    public function courseCategory(): BelongsTo
    {
        return $this->belongsTo(CourseCategory::class, 'course_category_id');
    }

    /**
     * Relationship: A Category has many Courses.
     */
    public function courses(): HasMany
    {
        return $this->hasMany(Course::class, 'category_id');
    }
}