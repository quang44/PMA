<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $fillable=['id','type','user_id','data','read_at','amount_first','amount_later','notifiable_type','send_group','item_id'];
    use HasFactory;
    function users(){
        return $this->hasMany(User::class);
    }

    function card(){
        return $this->belongsTo(WarrantyCard::class,'item_id','id')->with('user','active_user_id','cardDetail.product');
    }
    function gifts(){
        return $this->belongsTo(GiftRequest::class,'item_id','id')->with(['gift','user','accept']);
    }

}
