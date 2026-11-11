<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Admin extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'password' => 'hashed',
    ];

    /**
     * The courses assigned to this admin.
     */
    public function courses()
    {
        return $this->belongsToMany(Course::class, 'admin_course')
                    ->withTimestamps();
    }

    /**
     * Check if the admin is a Super Admin or assigned to a specific course.
     *
     * @param int|string $courseId
     * @return bool
     */
    public function hasCourseAccess($courseId): bool
    {
        if ($this->isSuperAdmin()) {
            return true;
        }

        return $this->courses()->where('courses.id', $courseId)->exists();
    }

    /**
     * Check if the admin has a specific role or any of the given roles.
     *
     * @param string|array $roles
     * @return bool
     */
    public function hasRole($roles): bool
    {
        if (is_array($roles)) {
            return in_array($this->role, $roles);
        }

        return $this->role === $roles;
    }

    /**
     * Convenience helper to check if admin is a Super Admin.
     */
    public function isSuperAdmin(): bool
    {
        return $this->role === 'super_admin';
    }
}