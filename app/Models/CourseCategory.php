<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Support\Str;

class CourseCategory extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'slug',
        'image',
        'description',
    ];

    /**
     * Boot function to automatically generate slugs on creation or updates.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($category) {
            if (empty($category->slug)) {
                $category->slug = Str::slug($category->name);
            }
        });

        static::updating(function ($category) {
            if ($category->isDirty('name') && empty($category->slug)) {
                $category->slug = Str::slug($category->name);
            }
        });
    }

    /**
     * Relationship: A CourseCategory has many Categories.
     */
    public function categories(): HasMany
    {
        return $this->hasMany(Category::class, 'course_category_id');
    }

    /**
     * Relationship: A CourseCategory has many Courses THROUGH Category.
     */
    public function courses(): HasManyThrough
    {
        return $this->hasManyThrough(
            Course::class,
            Category::class,
            'course_category_id', // Foreign key on categories table
            'category_id',        // Foreign key on courses table
            'id',                 // Local key on course_categories table
            'id'                  // Local key on categories table
        );
    }
}