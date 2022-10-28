<?php

    namespace App\Models;

    use Illuminate\Database\Eloquent\Factories\HasFactory;
    use Illuminate\Database\Eloquent\Model;

    class Log extends Model
    {
        use HasFactory;

        protected $fillable = ['id', 'type', 'point', 'amount', 'object', 'amount_first', 'amount_later', 'user_id', 'accept_by', 'content'];

        function user()
        {
            return $this->belongsTo(User::class);
        }

        function acceptor()
        {
            return $this->belongsTo(User::class,'accept_by','id');
        }
    }
