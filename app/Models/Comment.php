<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Post;

class Comment extends Model
{
    protected $fillable = [
        'post_id',
        'comment',
        'user_id'
    ];

}
