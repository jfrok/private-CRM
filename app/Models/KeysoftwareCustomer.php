<?php

namespace App\Models;

use App\Models\KeysoftwareApiCalls;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class KeysoftwareCustomer extends Model
{
    use HasFactory;

    static function generateToken()
    {
        $token = Str::random(48);
        if (KeysoftwareCustomer::where('api_token', $token)->first() !== null) {
            self::generateToken();
        } else {
            return $token;
        }
    }

    public function getApiCallsByYearAndMonth($month, $year)
    {
        $customerProductCalls = KeysoftwareApiCalls::whereMonth('date', $month)
            ->whereYear('date', $year)
            ->where('keysoftware_customer_id', $this->id)->get()->sum('count');

        return $customerProductCalls;
    }

    public function getProductsThisMonth()
    {
        return KeysoftwareApiCalls::whereMonth('date', Carbon::now()->format('m'))
            ->whereYear('date', Carbon::now()->format('Y'))
            ->where('keysoftware_customer_id', $this->id)->get();
    }

    public function fullAddress()
    {
        return $this->company_street . ' ' . $this->company_number . ', ' . $this->company_zipcode . ' ' . $this->company_place;
    }

    public function apiCallsThisMonth()
    {
        $customerProductCalls = KeysoftwareApiCalls::whereMonth('date', Carbon::now()->format('m'))
            ->whereYear('date', Carbon::now()->format('Y'))
            ->where('keysoftware_customer_id', $this->id)->get()->sum('count');

        return $customerProductCalls;
    }

    public function getTotalProductPriceThisMonth()
    {
        $products = KeysoftwareApiCalls::whereMonth('date', Carbon::now()->format('m'))
            ->whereYear('date', Carbon::now()->format('Y'))
            ->where('keysoftware_customer_id', $this->id)->get();

//        dd($products);

        $totalPrice = 0;
        foreach($products as $pr) {
            $totalPrice = $totalPrice + $pr->getProductPriceInclVat();
        }

        return number_format($totalPrice, 2, ',', '.');
    }
}
