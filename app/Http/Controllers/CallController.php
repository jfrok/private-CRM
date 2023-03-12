<?php

namespace App\Http\Controllers;

use App\Models\Call;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;

class CallController extends Controller
{
    public function create()
    {
        //Create new call for a user.
        $new = new Call();
        $new->user_id = request('user_id');
        if (request('customer_id_or_name') != "") {
            if (is_numeric(request('customer_id_or_name'))) {
                $customer = Customer::find(request('customer_id_or_name'));
                $new->customer_id = $customer->id;
            } else {
                $newCustomer = new Customer();
                $newCustomer->company_name = request('customer_id_or_name');
                $newCustomer->save();
                activity()->performedOn($newCustomer)->causedBy(User::find(Cookie::get('chosenUser')))->log('Klant  ' . $newCustomer->company_name . ' aangemaakt');
                $new->customer_id = $newCustomer->id;
            }
        }

        $new->caller_name = request('customer_name');
        $new->phone_number = request('phone_number');
        $new->description = request('description');

        //Set notification reminder if checked.
        if (request('notification')) {
            $new->reminder = true;
            $new->remind_date = request('notification_date');
            $new->remind_time = request('notification_time');
        }

        $new->save();

        activity()->performedOn($new)->causedBy(User::find(Cookie::get('chosenUser')))->log('Belverzoek met nr: ' . $new->phone_number . ' aangemaakt');

        //Return the correct blade
        $chosenUser = User::find(Cookie::get('chosenUser'));
        $customers = Customer::all();
        $users = User::all();


        $data = view('home.callList', compact('chosenUser', 'customers', 'users'))->render();
        return response()->json($data);
    }

    public function delete($id)
    {
        $call = Call::find($id);
        activity()->performedOn($call)->causedBy(User::find(Cookie::get('chosenUser')))->log('Belverzoek met nr: ' . $call->phone_number . ' verwijderd');
        $call->delete();

        //Return the correct blade
        $chosenUser = User::find(Cookie::get('chosenUser'));
        $customers = Customer::all();
        $users = User::all();


        $data = view('home.callList', compact('chosenUser', 'customers', 'users'))->render();
        return response()->json($data);
    }

    public function autofill($id) {
        $phone = DB::table('customer_contacts')->where('customer_id', $id)->first()->phone;

        if ($phone !== null) {
            return response()->json($phone);
        }
    }

    public function callApi(Request $request)
    {
        $token = null;

        if ($_GET['token'] === $token && $token !== null) {

            switch ($request->status) {
                case 'created' :
                    Call::createRegistry($request->caller, 'Aangemaakt');
                    break;
                case 'ringing' :
                    Call::updateRegistry($request->caller['number'], 'Inkomend');
                    break;
                case 'in-progress' :
                    Call::updateRegistry($request->caller['number'], 'In Gesprek');
                    break;
                case 'ended' :
                    switch ($request->reason) {
                        case 'completed' :
                            Call::updateRegistry($request->caller['number'], 'Verwerkt');
                            break;
                        case 'busy' :
                            Call::updateRegistry($request->caller['number'], 'Bezet');
                            break;
                        case 'no-answer' :
                            Call::updateRegistry($request->caller['number'], 'Niet Opgenomen');
                            break;
                        case 'failed' :
                            Call::updateRegistry($request->caller['number'], 'Fout');
                            break;
                        case 'cancelled' :
                            Call::updateRegistry($request->caller['number'], 'Zelf Opgehangen');
                            break;
                        case 'abandon' :
                            Call::updateRegistry($request->caller['number'], 'Opgehangen vanuit wachrij');
                            break;
                        default:
                            Call::updateRegistry($request->caller['number'], 'Onbekend');
                            break;
                    }
                    break;
                default:
                    Call::createRegistry($request->caller, 'Onbekend');
                    break;
            }
        }
    }
}
