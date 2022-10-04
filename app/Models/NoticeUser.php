<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NoticeUser extends Model
{
    use HasFactory;

    protected $table = 'notice_users';

    protected $fillable = ['user_id', 'status', 'updated_time'];
}
