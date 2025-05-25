<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MealPreference extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'meal_id',
        'opted_in',
        'special_requirements',
    ];

    protected $casts = [
        'opted_in' => 'boolean',
    ];

    /**
     * Get the user that owns the meal preference.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the meal that owns the meal preference.
     */
    public function meal()
    {
        return $this->belongsTo(Meal::class);
    }

    /**
     * Scope a query to only include opted-in preferences.
     */
    public function scopeOptedIn($query)
    {
        return $query->where('opted_in', true);
    }

    /**
     * Scope a query to only include opted-out preferences.
     */
    public function scopeOptedOut($query)
    {
        return $query->where('opted_in', false);
    }
}
