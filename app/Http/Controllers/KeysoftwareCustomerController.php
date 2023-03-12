<?php

namespace App\Http\Controllers;

use App\Models\KeysoftwareApiCalls;
use App\Models\KeysoftwareCustomer;
use App\Models\KeysoftwarePlanviewerProducts;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;

class KeysoftwareCustomerController extends Controller
{
    public function index()
    {
        $customers = KeysoftwareCustomer::all();
        $products = KeysoftwarePlanviewerProducts::all();
        return view('keysoftware.index', compact('customers', 'products'));
    }

    public function show($id)
    {
        $customer = KeysoftwareCustomer::find($id);
        $month = Carbon::today('Europe/Amsterdam')->format('m');
        $year = Carbon::today('Europe/Amsterdam')->format('Y');
        return view('keysoftware.show', compact('customer', 'year', 'month'));
    }

    public function create(Request $request)
    {
        $new = new KeysoftwareCustomer();
        $new->company_name = $request->company_name;
        $new->company_street = $request->company_street_name;
        $new->company_zipcode = $request->company_zipcode;
        $new->company_number = $request->company_number;
        $new->company_place = $request->company_place;
        $new->company_province = $request->company_province;
        $new->company_phone = $request->company_phone;
        $new->company_email = $request->company_email;
        $new->company_website = $request->company_website;
        $new->start_date = $request->start_date;
        $new->api_token = $request->api_token;
        $new->save();

        return redirect(route('keysoftware.index'))->with('success', 'Toevoegen van makelaar is gelukt!');
    }

    public function edit(Request $request, $id)
    {

        $edit = KeysoftwareCustomer::find($id);
        $edit->company_name = $request->company_name;
        $edit->company_street = $request->company_street_name;
        $edit->company_zipcode = $request->company_zipcode;
        $edit->company_number = $request->company_number;
        $edit->company_place = $request->company_place;
        $edit->company_province = $request->company_province;
        $edit->company_phone = $request->company_phone;
        $edit->company_email = $request->company_email;
        $edit->company_website = $request->company_website;
        $edit->start_date = $request->start_date;
        $edit->api_token = $request->api_token;
        $edit->save();

        return redirect()->back()->with('success', 'Bijwerken van de makelaar is gelukt!');
    }

    public function generateApiToken()
    {
        return KeysoftwareCustomer::generateToken();
    }

    public function loadNewPage($id, $view)
    {

        $year = Cookie::get('keysoftwareApiCalls') ?: Carbon::now()->format('Y');
        $customer = KeysoftwareCustomer::find($id);
        $data = view($view, compact('customer', 'year'))->render();
        return response()->json($data);
    }

    public function changePerformanceTime($id, $year)
    {
        Cookie::queue(Cookie::make('keysoftwareApiCalls', $year), 9999);

        return response()->json(true);
    }

    public function search()
    {
        $search = $_GET['searchQuery'];
        $customers = KeysoftwareCustomer::where('company_name', 'like', '%'.$search.'%')->get();
        $data = view('keysoftware.includes.customersTable', compact('customers'))->render();
        return response()->json($data);
    }

    public function delete($id)
    {
        KeysoftwareCustomer::find($id)->delete();
        KeysoftwareApiCalls::where('keysoftware_customer_id', $id)->delete();
        return redirect(route('keysoftware.index'))->with('success', 'Verwijderen van makelaar is gelukt!');
    }
}
