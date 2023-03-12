<?php

namespace App\Http\Controllers;

use App\Models\KeysoftwarePlanviewerProducts;
use Illuminate\Http\Request;

class KeysoftwarePlanviewerProductsController extends Controller
{
    public function update(Request $request) {
        foreach($request->product as $key => $pr) {
            $product = KeysoftwarePlanviewerProducts::find($key);
            $product->kadaster_price = $pr['kadaster_price'];
            $product->planviewer_price = $pr['planviewer_price'];
            $product->total_price = $pr['total_price'];
            $product->save();
        }

        return redirect()->back()->with('success', 'Prijzen zijn opgeslagen!');
    }
}
