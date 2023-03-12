<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KeysoftwareApiCalls extends Model
{
    use HasFactory;

    static function updateCallsByMonth($token, $data)
    {
        $customer = KeysoftwareCustomer::where('api_token', $token)->first();

        if ($customer !== null) {
            foreach ($data->products as $pr) {
                $row = KeysoftwareApiCalls::whereMonth('date', $data->month)
                    ->whereYear('date', $data->year)
                    ->where('keysoftware_customer_id', $customer->id)
                    ->where('slug', $pr['slug'])
                    ->where('name', $pr['name'])->first();

                if ($row !== null) {
                    $row->count = $row->count + $pr['count'];
                    $row->save();
                } else {
                    $new = new KeysoftwareApiCalls();
                    $new->keysoftware_customer_id = $customer->id;
                    $new->count = $pr['count'];
                    $new->slug = $pr['slug'];
                    $new->name = $pr['name'];
                    $new->date = Carbon::now()->format('Y-m-d');
                    $new->save();
                }

            }

            return true;
        }

        abort(404);
    }

    public function getProductPrice() {
        $planviewerProduct = KeysoftwarePlanviewerProducts::where('slug', $this->slug)->first();
        $totalPrice = number_format($planviewerProduct->total_price * $this->count, 3);

        return number_format($totalPrice, 2);
    }

    public function getProductPriceInclVat() {
        $planviewerProduct = KeysoftwarePlanviewerProducts::where('slug', $this->slug)->first();
        $totalPrice = $this->getProductPrice() + ($this->getProductPrice() / 100) * $planviewerProduct->vat;

        return $totalPrice;
    }

    public function getPlanviewerPrice() {
        $planviewerProduct = KeysoftwarePlanviewerProducts::where('slug', $this->slug)->first();
        return number_format($planviewerProduct->planviewer_price, 3, ',', '.');
    }

    public function getKadasterPrice() {
        $planviewerProduct = KeysoftwarePlanviewerProducts::where('slug', $this->slug)->first();
        return number_format($planviewerProduct->kadaster_price, 3, ',', '.');
    }
}
