<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerBank extends Model
{
    use HasFactory;

    protected $table = 'customer_bank';

    protected $fillable = ['user_id', 'name', 'branch', 'username', 'number'];
}
