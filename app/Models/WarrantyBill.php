<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WarrantyBill extends Model
{
    protected $table='warranty_bill';
    protected $fillable=['name','email','barcode','user_id','phone'];
    function  user(){
        return $this->belongsTo(User::class);
    }

}
