<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\CustomerAddress;
use App\Models\CustomerContact;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;

class CustomerController extends Controller
{
    public function index() {
        $customers = Customer::get();
        return view('customers.index', compact('customers'));
    }

    public function create() {
        //Create new customer
        $customer = new Customer();
        $customer->company_name = request('name');
        if(request('is_company')) {
            $customer->is_company = true;
        } else {
            $customer->is_company = false;
        }
        $customer->description = request('description');
        $customer->save();

        activity()->performedOn($customer)->causedBy(User::find(Cookie::get('chosenUser')))->log('Klant '.$customer->company_name.' aangemaakt');

        //Loop through all addresses and create them
        $statusses = request('address_status');
        $addresses = request('address_address');
        $zipCodes = request('address_zip_code');
        $places = request('address_place');

        foreach($addresses as $key => $address) {
            if($address != null) {
                $adr = new CustomerAddress();
                $adr->customer_id = $customer->id;
                $adr->status = $statusses[$key];
                $adr->address = $address;
                if(array_key_exists($key, $zipCodes)) {
                    $adr->zip_code = $zipCodes[$key];
                }
                if(array_key_exists($key, $places)) {
                    $adr->place = $places[$key];
                }
                $adr->save();
            }
        }

        //Loop through all contacts and create them
        $functions = request('contact_function');
        $firstNames = request('contact_first_name');
        $lastNames = request('contact_last_name');
        $emails = request('contact_email');
        $phones = request('contact_phone');

        foreach($lastNames as $key => $lastname) {
            if($lastname != null) {
                $contact = new CustomerContact();
                $contact->customer_id = $customer->id;
                $contact->function = $functions[$key];
                if(array_key_exists($key, $firstNames)) {
                    $contact->first_name = $firstNames[$key];
                }
                $contact->last_name = $lastname;
                if(array_key_exists($key, $emails)) {
                    $contact->email = $emails[$key];
                }
                if(array_key_exists($key, $emails)) {
                    $contact->email = $emails[$key];
                }
                if(array_key_exists($key, $phones)) {
                    $contact->phone = $phones[$key];
                }
                $contact->save();
            }
        }

        $customers = Customer::get();

        $data = view('customers.ajax.customersTable', compact('customers'))->render();
        return response()->json($data);
    }

    public function search() {
        $search = $_GET['searchQuery'];
        $company = $_GET['onlyCompany'];
        $private = $_GET['onlyPrivate'];

        $query = Customer::where('company_name', 'like', '%'.$search.'%');
        $customers = $query->get();

        $data = view('customers.ajax.customersTable', compact('customers'))->render();
        return response()->json($data);
    }

    public function show($id) {
        $customer = Customer::find($id);
        return view('customers.show', compact('customer'));
    }

    public function loadNewPage($id, $view) {
        $customer = Customer::find($id);
        $data = view($view, compact('customer'))->render();
        return response()->json($data);
    }

    public function edit($id) {
        $customer = Customer::find($id);

        $customer->company_name = request('name');
        if(request('is_company')) {
            $customer->is_company = true;
        } else {
            $customer->is_company = false;
        }

        $customer->has_onderhoud = request('has_onderhoud');

        $customer->description = request('description');
        $customer->save();

        activity()->performedOn($customer)->causedBy(User::find(Cookie::get('chosenUser')))->log('Klant '.$customer->company_name.' aangepast');

        //Loop through all addresses and create them
        $addresIds = request('address_id');
        array_shift($addresIds);
        $statusses = request('address_status');
        array_shift($statusses);
        $addresses = request('address_address');
        array_shift($addresses);
        $zipCodes = request('address_zip_code');
        array_shift($zipCodes);
        $places = request('address_place');
        array_shift($places);

        foreach($addresIds as $key => $adrId) {
            if($adrId == 0) {
                $adr = new CustomerAddress();
            } else {
                $adr = CustomerAddress::find($adrId);
            }
            $adr->customer_id = $id;
            if(array_key_exists($key, $statusses)) {
                $adr->status = $statusses[$key];
            }
            if(array_key_exists($key, $addresses)) {
                $adr->address = $addresses[$key];
            }
            if(array_key_exists($key, $zipCodes)) {
                $adr->zip_code = $zipCodes[$key];
            }
            if(array_key_exists($key, $places)) {
                $adr->place = $places[$key];
            }
            $adr->save();
        }

        //Loop through all addresses and create them
        $contactIds = request('contact_id');
        array_shift($contactIds);
        $functions = request('contact_function');
        array_shift($functions);
        $firstNames = request('contact_first_name');
        array_shift($firstNames);
        $lastNames = request('contact_last_name');
        array_shift($lastNames);
        $emails = request('contact_email');
        array_shift($emails);
        $phones = request('contact_phone');
        array_shift($phones);

        foreach($contactIds as $key => $cpId) {
            if($cpId == 0) {
                $contact = new CustomerContact();
            } else {
                $contact = CustomerContact::find($cpId);
            }
            $contact->customer_id = $id;

            if(array_key_exists($key, $functions)) {
                $contact->function = $functions[$key];
            }
            if(array_key_exists($key, $firstNames)) {
                $contact->first_name = $firstNames[$key];
            }
            if(array_key_exists($key, $lastNames)) {
                $contact->last_name = $lastNames[$key];
            }
            if(array_key_exists($key, $emails)) {
                $contact->email = $emails[$key];
            }
            if(array_key_exists($key, $emails)) {
                $contact->email = $emails[$key];
            }
            if(array_key_exists($key, $phones)) {
                $contact->phone = $phones[$key];
            }
            $contact->save();
        }

        $data = view('customers.ajax.showMain', compact('customer'))->render();
        return response()->json($data);
    }

    public function deleteContact($id, $cpId) {
        $contact = CustomerContact::find($cpId);
        activity()->performedOn($contact)->causedBy(User::find(Cookie::get('chosenUser')))->log('Contactpersson '.$contact->name.' verwijderd');
        $contact->delete();
    }

    public function deleteAddress($id, $adrId) {
        $contact = CustomerAddress::find($adrId);
        activity()->performedOn($contact)->causedBy(User::find(Cookie::get('chosenUser')))->log('Adres '.$contact->address.' verwijderd');
        $contact->delete();
    }

    public function deleteCustomer($id) {
        $customer = Customer::find($id);
        activity()->performedOn($customer)->causedBy(User::find(Cookie::get('chosenUser')))->log('Klant '.$customer->company_name.' verwijderd');
        $customer->delete();

        session()->flash('success', 'Klant verwijderd!');
        return redirect('/klanten');
    }
}
