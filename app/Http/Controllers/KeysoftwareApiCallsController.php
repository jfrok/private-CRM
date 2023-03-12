<?php

namespace App\Http\Controllers;

use App\Models\KeysoftwareApiCalls;
use App\Models\KeysoftwareCustomer;
use App\Models\KeysoftwarePlanviewerProducts;
use Illuminate\Http\Request;

class KeysoftwareApiCallsController extends Controller
{
    public function updateApiCalls(Request $request, $token) {
        return KeysoftwareApiCalls::updateCallsByMonth($token, $request);
    }

    public function planviewerProducts($token) {
        if(KeysoftwareCustomer::where('api_token', $token)->exists()) {
            return KeysoftwarePlanviewerProducts::whereNotIn('slug', ['maps_api','data_api'])->get();
        } else {
            abort(404);
        }
    }

    public function planviewerProduct($token, $slug) {
        if(KeysoftwareCustomer::where('api_token', $token)->exists()) {
            return KeysoftwarePlanviewerProducts::where('slug', $slug)->first();
        } else {
            abort(404);
        }
    }
}
