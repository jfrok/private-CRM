<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wiki extends Model
{
    use HasFactory;

    protected $table = 'wiki';

    protected $fillable = [
        'user_id',
        'user_name',
        'titel',
        'body',
    ];
}
