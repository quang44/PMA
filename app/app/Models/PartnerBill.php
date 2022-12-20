<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PartnerBill extends Model
{
    use HasFactory;

    protected $table = 'partner_bill';

    protected $casts = [
        'order_ids' => 'array',
    ];
}
