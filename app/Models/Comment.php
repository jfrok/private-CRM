<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $table = 'wiki_comments';

    protected $fillable = [
        'user_id',
        'user_name',
        'post_id',
        'body',
    ];
}
