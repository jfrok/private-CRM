<?php

namespace App\Models;

use App\Notifications\PushCallRegistry;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Notification;

class Call extends Model
{
    use HasFactory, SoftDeletes;

    public function customer() {
        return $this->hasOne(Customer::class, 'id', 'customer_id');
    }

    public function user() {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function getNiceDate() {
        $date = Carbon::parse($this->created_at);
        $today = Carbon::today('Europe/Amsterdam');
        $yesterday = Carbon::yesterday('Europe/Amsterdam');
        $time = date('H:i', strtotime($this->created_at));
        if($date->isSameDay($today)) {
            return 'vandaag';
        } elseif($date->isSameDay($yesterday)) {
            return 'gisteren';
        } else {
            return $date->diffInDays($today) . ' dagen geleden';
        }
    }

    public static function createRegistry($caller, $status)
    {
        $number = str_replace('+31', '0', $caller['number']);
        $contact = CustomerContact::where('phone', $number)->first();

        $new = new Call();
        $new->customer_id = $contact ? $contact->id : null;
        $new->number = $number;
        $new->caller_name = ($contact ? $contact->first_name . ' ' . $contact->last_name :( $caller['name'] ? $caller['name'] : 'Onbekend'));
//        $new->status = $status;
        $new->save();

        Notification::send(User::all(),new PushCallRegistry($new));

        return $new;
    }

    public static function updateRegistry($number, $status)
    {
        $number = str_replace('+31', '0', $number);

        $edit = CallRegistry::where('number', $number)->latest()->first();

        if ($status == 'Verwerkt') {
            $edit->time = str_replace(' eerder', '', Carbon::parse($edit->created_at)->diffForHumans(Carbon::now()));
        }


        $edit->status = $status;
        $edit->save();

        return $edit;
    }
}
