<?php


namespace App;

use App\Models\Customer;
use App\Models\CustomerContact;
use App\Models\CustomerAddress;
use App\Models\Project;

class WeFactAPI
{
    private $url;
    private $apiKey;

    function __construct()
    {
        $this->url = 'https://www.mijnwefact.nl/apiv2/api.php';
        $this->apiKey = '2aff4a75eaff4d397e0c13dea484a4e6';
    }

    public function sendRequest($controller, $action, $params)
    {
        if (is_array($params)) {
            $params['api_key'] = $this->apiKey;
            $params['controller'] = $controller;
            $params['action'] = $action;
        }

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $this->url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, '10');
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));

        $curlResp = curl_exec($ch);
        $curlError = curl_error($ch);

        if ($curlError != '') {
            $result = array(
                'controller' => 'invalid',
                'action' => 'invalid',
                'status' => 'error',
                'date' => date('c'),
                'errors' => array($curlError)
            );

        } elseif (curl_getinfo($ch, CURLINFO_HTTP_CODE) == 403) {
            $result = array(
                'controller' => 'invalid',
                'action' => 'invalid',
                'status' => 'error',
                'date' => date('c'),
                'errors' => array($curlResp)
            );

        } else {
            $result = json_decode($curlResp, true);
        }

        return $result;
    }
    static function importCustomers()
    {
        $api = new WeFactAPI();
        $list = $api->sendRequest('debtor', 'list', ['group' => 16]);

        foreach ($list['debtors'] as $debtor) {

            // If customer does not exist with debtor_code
            if (!$customer = Customer::where('wefact_id', $debtor['DebtorCode'])->first()) {

                // Try to find customer with CompanyName and set the DebtorCode
                $name = ($debtor['CompanyName'] != "" ? $debtor['CompanyName'] : $debtor['Initials'] . ($debtor['SurName'] != "" ? ' ' . $debtor['SurName'] : ''));
                if ($customer = Customer::where('company_name', 'like', '%' . $name . '%')->first()) {
                    $customer->wefact_id = $debtor['DebtorCode'];
                    $customer->save();
                } else {
                    // Get all customers from DB without a wefact_id (DebtorCode)
                    $createNew = true;
                    $customersWithoutDC = Customer::where('wefact_id', NULL)->get();
                    foreach ($customersWithoutDC as $c) {
                        if (str_contains(strtolower(str_replace(" ", "", $debtor['CompanyName'])), strtolower(str_replace(" ", "", $c->company_name)))) {
                            $c->wefact_id = $debtor['DebtorCode'];
                            $c->save();

                            $createNew = false;
                            break; // Break from loop if customer is found
                        }
                    }

                    // Customer does not exist in CRM, create new customer
                    if ($createNew) {
                        $customer = new Customer();
                        $customer->company_name = $name;
                        $customer->is_company = $debtor['CompanyName'] != "";
                        $customer->wefact_id = $debtor['DebtorCode'];
                        $customer->save();
                    }
                }
            }

            // Create Contact person
            if ($debtor['EmailAddress'] != "" || $debtor['Initials'] != "") {
                if (!$contact = CustomerContact::where('customer_id', $customer->id)->where('email', $debtor['EmailAddress'])->first()) {
                    $contact = new CustomerContact();
                    $contact->function = "Contactpersoon";
                }

                $contact->customer_id = $customer->id;
                $contact->first_name = $debtor['Initials'];
                $contact->last_name = $debtor['SurName'];
                $contact->email = $debtor['EmailAddress'];
                $contact->phone = NULL;
                $contact->save();
            }

            // Get debtor data and create address
            $getDebtor = $api->sendRequest('debtor', 'show', ['DebtorCode' => $customer->wefact_id]);
            if ($getDebtor['debtor']['Address'] != "") {
                if (!$address = CustomerAddress::where('customer_id', $customer->id)->where('address', $getDebtor['debtor']['Address'])->first()) {
                    $address = new CustomerAddress();
                    $address->customer_id = $customer->id;
                    $address->status = "Hoofdlocatie";
                }

                $address->address = $getDebtor['debtor']['Address'];
                $address->zip_code = $getDebtor['debtor']['ZipCode'];
                $address->place = $getDebtor['debtor']['City'];
                $address->save();
            }
        }
    }

    static function importProjects() {
        $api = new WeFactAPI();

        // Get all accepted offers
        $list = $api->sendRequest('pricequote', 'list', ['status' => 3, 'archived' => 0]);
        foreach ($list['pricequotes'] as $l) {

            // Check if offer is not already created as a project
            if (!Project::where('offer_id', $l['PriceQuoteCode'])->exists()) {

                // Get all data from WeFact from offer
                $offer = $api->sendRequest('pricequote', 'show', ['PriceQuoteCode' => $l['PriceQuoteCode']]);
                $customer = Customer::where('wefact_id', $offer['pricequote']['DebtorCode'])->first();
                $new = new Project();
                $new->customer_id = ($customer ? $customer->id : NULL);
                $new->offer_id = $offer['pricequote']['PriceQuoteCode'];
                $new->title = $offer['pricequote']['PriceQuoteCode'];
                $new->description = NULL;
                $new->include_count = 1;
                $new->set_hours = (floatval($offer['pricequote']['AmountExcl']) / 75);
                $new->set_price = 75;
                $new->status = "Open";
                $new->save();

            }
        }
    }
}
