<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WarrantyBill extends Model
{
    protected $table='warranty_bill';

    function  user(){
        return $this->belongsTo(User::class);
    }

}
