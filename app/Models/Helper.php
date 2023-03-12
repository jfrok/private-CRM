<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Helper extends Model
{
    use HasFactory;

    static function getMonthByNumber($i) {
        $str = '';
        switch($i) {
            case 1:
                $str = 'jan';
                break;
            case 2:
                $str = 'feb';
                break;
            case 3:
                $str = 'maa';
                break;
            case 4:
                $str = 'apr';
                break;
            case 5:
                $str = 'mei';
                break;
            case 6:
                $str = 'jun';
                break;
            case 7:
                $str = 'jul';
                break;
            case 8:
                $str = 'aug';
                break;
            case 9:
                $str = 'sep';
                break;
            case 10:
                $str = 'okt';
                break;
            case 11:
                $str = 'nov';
                break;
            case 12:
                $str = 'dec';
                break;
        }

        return $str;
    }
}
