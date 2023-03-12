<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WeFact extends Model
{
    use HasFactory;

    protected $table = 'wefact_facturen';

    protected $fillable = [
        'ar_number',
        'quantity',
        'article',
        'article_price',
        'omschrijving',
    ];
}
