<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lesson extends Model
{
    use HasFactory;

    protected $fillable = [
        'section_id',
        'title',
        'max_questions',
        'type',
        'video_url',
        'content',
        'order',
    ];

    protected $appends = ['course_id'];

    public function section()
    {
        return $this->belongsTo(Section::class);
    }

    public function questions()
    {
        return $this->hasMany(Question::class)->orderBy('order');
    }

    /**
     * Get the parent course_id through Section -> Unit
     */
    public function getCourseIdAttribute()
    {
        return $this->section?->unit?->course_id;
    }

    /**
     * Get the parent Course instance through Section -> Unit
     */
    public function course()
    {
        return $this->hasOneThrough(
            Course::class,
            Section::class,
            'id',          // Foreign key on sections table (referenced via Lesson)
            'id',          // Foreign key on courses table
            'section_id',  // Local key on lessons table
            'unit_id'      // Local key on sections table
        );
    }
}