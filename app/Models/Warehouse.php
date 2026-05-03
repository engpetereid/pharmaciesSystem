<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Warehouse extends Model
{
    use HasFactory;

    protected $fillable = [
        'quantity',
        'pharmacy_id',
        'drug_id',
        'minimum',
    ];

    protected function casts(): array
    {
        return [
            'quantity' => 'integer',
            'minimum'  => 'integer',
        ];
    }

    public function pharmacy(): BelongsTo
    {
        return $this->belongsTo(Pharma::class, 'pharmacy_id');
    }

    public function drug(): BelongsTo
    {
        return $this->belongsTo(Drug::class);
    }
}
