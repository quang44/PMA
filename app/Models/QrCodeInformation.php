<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QrCodeInformation extends Model
{
    protected $table='qr_code_informations';

    function  user(){
        return $this->belongsTo(User::class);
    }

}
