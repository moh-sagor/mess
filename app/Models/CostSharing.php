<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CostSharing extends Model
{
    use HasFactory;

    protected $table = 'cost_sharing';

    protected $fillable = [
        'user_id',
        'period_start',
        'period_end',
        'total_meals_cost',
        'total_other_expenses',
        'user_share_amount',
        'meals_participated',
        'total_meals_available',
        'sharing_method',
        'status',
    ];

    protected $casts = [
        'period_start' => 'date',
        'period_end' => 'date',
        'total_meals_cost' => 'decimal:2',
        'total_other_expenses' => 'decimal:2',
        'user_share_amount' => 'decimal:2',
        'meals_participated' => 'integer',
        'total_meals_available' => 'integer',
    ];

    /**
     * Get the user that owns the cost sharing record.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope a query to only include records for a specific period.
     */
    public function scopeForPeriod($query, $startDate, $endDate)
    {
        return $query->where('period_start', $startDate)
                    ->where('period_end', $endDate);
    }

    /**
     * Scope a query to only include calculated records.
     */
    public function scopeCalculated($query)
    {
        return $query->where('status', 'calculated');
    }

    /**
     * Scope a query to only include paid records.
     */
    public function scopePaid($query)
    {
        return $query->where('status', 'paid');
    }

    /**
     * Scope a query to only include pending records.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Calculate the participation percentage.
     */
    public function getParticipationPercentageAttribute(): float
    {
        if ($this->total_meals_available == 0) {
            return 0;
        }
        
        return ($this->meals_participated / $this->total_meals_available) * 100;
    }

    /**
     * Get the total amount (meals + other expenses).
     */
    public function getTotalAmountAttribute(): float
    {
        return $this->total_meals_cost + $this->total_other_expenses;
    }

    /**
     * Check if the cost sharing is paid.
     */
    public function isPaid(): bool
    {
        return $this->status === 'paid';
    }

    /**
     * Check if the cost sharing is pending.
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * Check if the cost sharing is calculated.
     */
    public function isCalculated(): bool
    {
        return $this->status === 'calculated';
    }
}
