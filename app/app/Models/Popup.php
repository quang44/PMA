<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Popup extends Model
{
    use HasFactory;

    protected $table = 'popups';

    public function getUrlImageAttribute()
    {
        return uploaded_asset($this->image);
    }
}
