<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Represents a low-stock alert notification for a pharmacy drug.
 *
 * Renamed from Notification to avoid shadowing Laravel's built-in
 * Illuminate\Notifications\DatabaseNotification class.
 */
class StockNotification extends Model
{
    use HasFactory;

    protected $table = 'notifications';

    protected $fillable = [
        'pharmacy_id',
        'drug_id',
        'message',
    ];

    public function pharmacy(): BelongsTo
    {
        return $this->belongsTo(Pharma::class, 'pharmacy_id');
    }

    public function drug(): BelongsTo
    {
        return $this->belongsTo(Drug::class);
    }
}
