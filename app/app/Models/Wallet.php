<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    protected $fillable=['user_id','amount'];

    public function user(){
    	return $this->belongsTo(User::class);
    }

    public function logs(){
        return $this->hasMany(Log::class,'user_id','user_id')->orderBy('created_at','DESC');
    }
}
