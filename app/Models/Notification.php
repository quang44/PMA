<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $fillable=['id','type','user_id','data','read_at','amount_first','amount_later','notifiable_type'];
    use HasFactory;
    function users(){
        return $this->hasMany(User::class);
    }

}
