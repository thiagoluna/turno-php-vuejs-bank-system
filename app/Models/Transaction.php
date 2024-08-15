<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Ramsey\Uuid\Uuid;

class Transaction extends Model
{
    use HasFactory;

    protected $guarded = [];

    public $incrementing = false;

    protected static function booted(): void
    {
        static::creating(fn(Transaction $transaction) => $transaction->id = (string) Uuid::uuid4());
    }

    public function bankAccount(): BelongsTo
    {
        return $this->belongsTo(BankAccount::class);
    }
}
