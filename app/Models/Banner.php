<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    protected $table = 'banner';

    public function getUrlImageAttribute()
    {
        return uploaded_asset($this->image);
    }
}
