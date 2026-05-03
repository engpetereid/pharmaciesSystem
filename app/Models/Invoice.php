<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'pharmacy_id',
        'price',
        'date',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'float',
            'date'  => 'date',
        ];
    }

    /**
     * The pharmacy this invoice belongs to.
     * Explicit FK specified to avoid Eloquent guessing 'pharma_id'.
     */
    public function pharmacy(): BelongsTo
    {
        return $this->belongsTo(Pharma::class, 'pharmacy_id');
    }

    /**
     * The individual line items on this invoice.
     */
    public function items(): HasMany
    {
        return $this->hasMany(InvoiceItem::class);
    }
}
