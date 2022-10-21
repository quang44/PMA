<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WarrantyCard extends Model
{
    use HasFactory;

    function brand(){
        return $this->belongsTo(Brand::class);
    }
    function uploads(){
        return $this->hasMany(Upload::class,'object_id','id');
    }
}
