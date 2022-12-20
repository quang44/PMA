<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bank extends Model
{
    use HasFactory;

    protected $table = 'bank';

    protected $fillable = ['name', 'icon'];

    public function getUrlIconAttribute()
    {
        return uploaded_asset($this->icon);
    }
}
