<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BazaarRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'item_name',
        'quantity',
        'unit',
        'unit_price',
        'total_cost',
        'purchase_date',
        'vendor_name',
        'notes',
        'meal_id',
        'created_by',
    ];

    protected $casts = [
        'quantity' => 'decimal:2',
        'unit_price' => 'decimal:2',
        'total_cost' => 'decimal:2',
        'purchase_date' => 'date',
    ];

    /**
     * Get the meal that owns the bazaar record.
     */
    public function meal()
    {
        return $this->belongsTo(Meal::class);
    }

    /**
     * Get the user who created the bazaar record.
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the expenses for the bazaar record.
     */
    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }

    /**
     * Scope a query to only include records for a specific date.
     */
    public function scopeForDate($query, $date)
    {
        return $query->where('purchase_date', $date);
    }

    /**
     * Scope a query to only include records within a date range.
     */
    public function scopeBetweenDates($query, $startDate, $endDate)
    {
        return $query->whereBetween('purchase_date', [$startDate, $endDate]);
    }

    /**
     * Calculate total cost automatically before saving.
     */
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($bazaarRecord) {
            $bazaarRecord->total_cost = $bazaarRecord->quantity * $bazaarRecord->unit_price;
        });
    }
}
