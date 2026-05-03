<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Order extends Model
{
    //
    use HasFactory;
    protected $fillable = [
        'drug_id',
        'pharmacy_id',
        'quantity',
        'accepted'
    ];

    public function pharmacy(): BelongsTo
    {
        return $this->belongsTo(Pharma::class);
    }
    public function drug(): BelongsTo
    {
        return $this->belongsTo(Drug::class);
    }
}
