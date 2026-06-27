<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Debt extends Model
{
    protected $fillable = [
        'user_id', 'created_by', 'type', 'payment_type', 'person_name',
        'total_amount', 'remaining_amount',
        'installment_amount',
        'due_date', 'note', 'status',
    ];

    protected function casts(): array
    {
        return [
            'due_date' => 'date',
            'total_amount' => 'decimal:2',
            'remaining_amount' => 'decimal:2',
            'installment_amount' => 'decimal:2',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function payments(): HasMany
    {
        return $this->hasMany(DebtPayment::class);
    }

    public function isCicilanTetap(): bool
    {
        return $this->payment_type === 'cicilan_tetap';
    }

    public function getTotalInstallments(): int
    {
        if ($this->isCicilanTetap() && $this->installment_amount > 0) {
            return (int) ceil((float) $this->total_amount / (float) $this->installment_amount);
        }
        return 0;
    }

    public function getPaidInstallments(): int
    {
        return $this->payments()->count();
    }

    public function getPaidInstallmentNumbers(): array
    {
        return $this->payments()->whereNotNull('installment_number')->pluck('installment_number')->toArray();
    }

    public function getExpectedInstallments(): int
    {
        if (!$this->isCicilanTetap() || !$this->due_date) {
            return $this->getTotalInstallments();
        }
        $now = Carbon::now();
        $firstDue = Carbon::parse($this->due_date)->startOfDay();
        $nowDay = $now->copy()->startOfDay();

        if ($nowDay < $firstDue) {
            return 0;
        }

        $monthsPassed = (int) $firstDue->diffInMonths($nowDay) + 1;
        return min($monthsPassed, $this->getTotalInstallments());
    }

    public function getOverdueInstallments(): int
    {
        if (!$this->isCicilanTetap()) {
            return $this->status === 'belum_lunas' && $this->due_date && Carbon::parse($this->due_date)->isPast() ? 1 : 0;
        }

        $expected = $this->getExpectedInstallments();
        $paid = $this->getPaidInstallments();
        return max(0, $expected - $paid);
    }

    public function isOverdue(): bool
    {
        if ($this->status === 'lunas') return false;
        if ($this->remaining_amount <= 0) return false;

        if ($this->isCicilanTetap()) {
            return $this->getOverdueInstallments() > 0;
        }

        return $this->due_date && Carbon::parse($this->due_date)->startOfDay()->isPast();
    }

    public function getOverdueDays(): int
    {
        if (!$this->due_date) return 0;
        $now = Carbon::now()->startOfDay();
        $due = Carbon::parse($this->due_date)->startOfDay();
        if ($now <= $due) return 0;
        return (int) $due->diffInDays($now);
    }
}
