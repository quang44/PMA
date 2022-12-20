<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WarrantyCardDetail extends Model
{
    use HasFactory;
    protected $fillable=['warranty_card_id','product_id','color_id','image','qty','video'];

    function card(){
        return $this->belongsTo(WarrantyCard::class);
    }

    function product(){
        return $this->belongsTo(Product::class)->select('name','id');
    }
    public function color(){
        return $this->belongsTo(Color::class)->select('id','name','code','warranty_duration');
    }
}
