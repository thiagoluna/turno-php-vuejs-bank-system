<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Ramsey\Uuid\Uuid;

class BankAccount extends Model
{
    use HasFactory;

    protected $guarded = [];

    public $incrementing = false;

    protected static function booted(): void
    {
        static::creating(fn(BankAccount $bankAccount) => $bankAccount->id = (string) Uuid::uuid4());
    }

    public static function boot(): void
    {
        parent::boot();
        self::creating(function (BankAccount $bankAccount) {
            $bankAccount->account = $bankAccount->max('account') + 1;
        });
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
