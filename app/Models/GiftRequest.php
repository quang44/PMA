<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GiftRequest extends Model
{
    use HasFactory;
    protected $table='gift_request';
    protected $hidden= ['created_at','updated_at'];
   protected $fillable=['user_id','address','gift_id','created_time','active_time','status','reason','accept_by'];
    function user(){
        return $this->belongsTo(User::class)->select('id','name','email','phone');
    }

    function accept(){
        return $this->belongsTo(User::class,'accept_by')->select('id','name');
    }

    function gift(){
        return $this->belongsTo(Gift::class);
    }
}
