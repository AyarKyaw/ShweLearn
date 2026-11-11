<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    use HasFactory;

    protected $fillable = ['course_id', 'title', 'description', 'order'];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function sections()
    {
        return $this->hasMany(Section::class)->orderBy('order', 'asc');
    }

    // Access all lessons inside this unit directly
    public function lessons()
    {
        return $this->hasManyThrough(Lesson::class, Section::class);
    }
}