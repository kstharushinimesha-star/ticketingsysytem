<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
   

    protected $fillable = ['subject', 'description', 'category', 'priority', 'status', 'user_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function comments()
    {
   
        return $this->hasMany(Comment::class)->latest();
    }
}

