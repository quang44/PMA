<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerBill extends Model
{
    use HasFactory;

    protected $table = 'customer_bill';

    protected $casts = [
        'order_ids' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
