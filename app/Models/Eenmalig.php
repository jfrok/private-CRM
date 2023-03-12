<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Eenmalig extends Model
{
    use HasFactory;

    protected $table = 'eenmalige_bedragen';

    protected $fillable = [
        'user_id',
        'bedrijfsnaam',
        'datum',
        'prijs',
        'beschrijving',
    ];
}
