<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'chat_id',
        'type',
        'amount',
        'base_amount_uzs',
        'category',
        'note',
        'status',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'base_amount_uzs' => 'decimal:2',
    ];
}
