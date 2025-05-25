<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'address',
        'is_active',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Get the meal preferences for the user.
     */
    public function mealPreferences()
    {
        return $this->hasMany(MealPreference::class);
    }

    /**
     * Get the meals the user has opted in for.
     */
    public function optedInMeals()
    {
        return $this->belongsToMany(Meal::class, 'meal_preferences')
                    ->wherePivot('opted_in', true)
                    ->withPivot('special_requirements', 'created_at', 'updated_at');
    }

    /**
     * Get the bazaar records created by the user.
     */
    public function bazaarRecords()
    {
        return $this->hasMany(BazaarRecord::class, 'created_by');
    }

    /**
     * Get the expenses created by the user.
     */
    public function expenses()
    {
        return $this->hasMany(Expense::class, 'created_by');
    }

    /**
     * Get the expenses approved by the user.
     */
    public function approvedExpenses()
    {
        return $this->hasMany(Expense::class, 'approved_by');
    }

    /**
     * Get the cost sharing records for the user.
     */
    public function costSharings()
    {
        return $this->hasMany(CostSharing::class);
    }

    /**
     * Scope a query to only include active users.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Check if user has a specific role.
     */
    public function isAdmin(): bool
    {
        return $this->hasRole('admin');
    }

    /**
     * Check if user is a manager.
     */
    public function isManager(): bool
    {
        return $this->hasRole('manager');
    }

    /**
     * Check if user is a regular user.
     */
    public function isUser(): bool
    {
        return $this->hasRole('user');
    }
}
