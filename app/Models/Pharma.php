<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Represents a pharmacy.
 * Table name is 'pharmacies'. No timestamps by design.
 */
class Pharma extends Model
{
    use HasFactory;

    protected $table = 'pharmacies';

    public $timestamps = false;

    protected $fillable = [
        'name',
        'user_id',
    ];

    /**
     * The user (supervisor) who owns this pharmacy.
     *
     * Fix: was incorrectly declared as hasOne (which queries the USERS table
     * for a pharma_id that doesn't exist there). The FK lives on THIS table,
     * so the correct relation is belongsTo.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
