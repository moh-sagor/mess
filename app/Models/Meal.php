<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Meal extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'meal_date',
        'meal_time',
        'meal_category_id',
        'description',
        'estimated_cost',
        'is_active',
        'preference_deadline',
    ];

    protected $casts = [
        'meal_date' => 'date',
        'meal_time' => 'datetime:H:i',
        'estimated_cost' => 'decimal:2',
        'is_active' => 'boolean',
        'preference_deadline' => 'datetime',
    ];

    /**
     * Get the category that owns the meal.
     */
    public function mealCategory()
    {
        return $this->belongsTo(MealCategory::class);
    }

    /**
     * Get the meal preferences for the meal.
     */
    public function mealPreferences()
    {
        return $this->hasMany(MealPreference::class);
    }

    /**
     * Get the bazaar records for the meal.
     */
    public function bazaarRecords()
    {
        return $this->hasMany(BazaarRecord::class);
    }

    /**
     * Get users who opted in for this meal.
     */
    public function optedInUsers()
    {
        return $this->belongsToMany(User::class, 'meal_preferences')
                    ->wherePivot('opted_in', true)
                    ->withPivot('special_requirements', 'created_at', 'updated_at');
    }

    /**
     * Scope a query to only include active meals.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to only include meals for a specific date.
     */
    public function scopeForDate($query, $date)
    {
        return $query->where('meal_date', $date);
    }

    /**
     * Scope a query to only include meals of a specific type.
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Check if preference deadline has passed.
     */
    public function isPreferenceDeadlinePassed(): bool
    {
        return $this->preference_deadline && Carbon::now()->isAfter($this->preference_deadline);
    }

    /**
     * Get the total participants count.
     */
    public function getParticipantsCountAttribute(): int
    {
        return $this->mealPreferences()->where('opted_in', true)->count();
    }
}
