<?php

namespace App\Http\Controllers;

use App\WeFactAPI;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FactureerlijstController extends Controller
{
    public function index()
    {
        $api = new WeFactAPI();

        return view('factureerlijst.index', [
            'user' => new \App\Models\User(),
            'month' => Carbon::now()->month,
            'producten' => $api->sendRequest('product', 'list', array('order' => 'ASC'))
        ]);
    }

    public function reload()
    {
        $user = new \App\Models\User();
        $month = Carbon::now()->month;

        $api = new WeFactAPI();
        $producten = $api->sendRequest('product', 'list', array('order' => 'ASC'));

        $data = view('factureerlijst.ajax.factuurtable', compact('user', 'month', 'producten'))->render();
        return response()->json($data);
    }

    public function filter($month)
    {
        $user = new \App\Models\User();

        $api = new WeFactAPI();
        $producten = $api->sendRequest('product', 'list', array('order' => 'ASC'));

        $data = view('factureerlijst.ajax.factuurtable', compact('user', 'month', 'producten'))->render();
        return response()->json($data);
    }

    public function checkFactuur($id, $price, $product)
    {
        if ($product != 0) {
            $project = DB::table('projects')->where('id', $id)->first();
            $user = new \App\Models\User();

            $api = new WeFactAPI();

            $product_name = $api->sendRequest('product', 'show', array('ProductCode' => $product));

            $debiteur = $api->sendRequest('debtor', 'list',
                array(
                    'searchchat' => 'CompanyName',
                    'searchfor' => DB::table('customers')->where('id', $project->customer_id)->first()->company_name
                )
            );

            $invoice_lines = array();

            foreach (\App\Models\User::all() as $user) {
                array_push($invoice_lines, array(
                    'ProductCode' => $product,
                    'Number' => $user->getWorkedHoursByProject(true, $project->id, Carbon::now()->month, Carbon::now()->year),
                    'NumberSuffix' => ' uur',
                    'PriceExcl' => $project->set_price > $user->project_cost ? $project->set_price : $user->project_cost,
                    'Description' => $product_name['product']['ProductName'] . ' ' . $user->translateMonth(Carbon::now()->format('m')),
                ));
            }

            $api->sendRequest('invoice', 'add',
                array(
                    'DebtorCode' => $debiteur['debtors'][0]['DebtorCode'],
                    'InvoiceLines' => $invoice_lines
                )
            );
        }

        DB::table('gefactureerd')->insert([
            'checked' => 'yes',
            'project_id' => $id,
            'date_checked' => Carbon::today('Europe/Amsterdam')->format('Y-m-d'),
        ]);
    }

    public function uncheckFactuur($id)
    {
        DB::table('gefactureerd')->where('project_id', $id)->delete();
    }
}
