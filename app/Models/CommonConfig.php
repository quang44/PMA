<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommonConfig extends Model
{
    use HasFactory;
    protected $table = 'common_configs';
    public $timestamps = FALSE;
}
