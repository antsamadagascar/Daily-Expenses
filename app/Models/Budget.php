<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Budget extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'amount',
        'name',
        'description',
        'start_date',
        'end_date',
        'period',
        'is_active'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'start_date' => 'date',
        'end_date' => 'date',
        'is_active' => 'boolean'
    ];

    /**
     * Relations
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function expenses(): HasMany
    {
        return $this->hasMany(Expense::class);
    }

    /**
     * Scopes
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeCurrent($query)
    {
        $now = now();
        return $query->where('start_date', '<=', $now)
                    ->where('end_date', '>=', $now);
    }

    /**
     * Accesseurs & Mutateurs
     */
    public function getSpentAmountAttribute()
    {
        return $this->expenses()
                   ->whereBetween('expense_date', [$this->start_date, $this->end_date])
                   ->sum('amount');
    }

    public function getRemainingAmountAttribute()
    {
        return $this->amount - $this->spent_amount;
    }

    public function getProgressPercentageAttribute()
    {
        if ($this->amount == 0) return 0;
        return min(100, ($this->spent_amount / $this->amount) * 100);
    }

    public function getIsOverBudgetAttribute()
    {
        return $this->spent_amount > $this->amount;
    }
}