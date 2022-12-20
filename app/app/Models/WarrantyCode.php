<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WarrantyCode extends Model
{
protected $fillable=['code','status','use_at'];

//    function  user(){
//        return $this->belongsTo(User::class);
//    }

}
