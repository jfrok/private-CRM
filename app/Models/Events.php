<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Events extends Model
{
    use HasFactory;

    protected $table = 'events';

    protected $fillable = [
        'user_id',
        'user_name',
        'project_id',
        'customer_id',
        'datum_vanaf',
        'datum_tot',
        'tijd_vanaf',
        'tijd_tot',
        'titel',
        'beschrijving',
    ];
}
