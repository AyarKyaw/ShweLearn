<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AlumniPayment extends Model
{
    protected $fillable = [
        'alumni_id',
        'amount',
        'currency',
        'payment_method',
        'transaction_id',
        'status',
        'gateway_response',
    ];

    protected $casts = [
        'gateway_response' => 'array',
    ];

    public function alumni()
    {
        return $this->belongsTo(Alumni::class);
    }
}