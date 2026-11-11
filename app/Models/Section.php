<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    use HasFactory;

    protected $fillable = ['unit_id', 'title', 'description', 'order'];

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function lessons()
    {
        return $this->hasMany(Lesson::class)->orderBy('order', 'asc');
    }

    public function course()
    {
        return $this->hasOneThrough(
            Course::class,
            Unit::class,
            'id',        // Foreign key on Unit table (referenced by Section.unit_id)
            'id',        // Foreign key on Course table (referenced by Unit.course_id)
            'unit_id',   // Local key on Section table
            'course_id'  // Local key on Unit table
        );
    }
}