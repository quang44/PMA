<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AffiliatePayment extends Model
{
    protected $table = 'affiliate_payment';

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
