<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Customer;
use App\Models\CustomerContact;
use App\Models\CustomerAddress;
use App\Models\Project;
use App\WeFactAPIVoipzo;
use App\WeFactAPI;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class WeFactController extends Controller
{
    public function index()
    {
        return view('wefact.index');
    }

    public function reloadFacturen()
    {
        $data = view('wefact.ajax.facturen')->render();
        return response()->json($data);
    }


    public function weFactUpload(Request $request)
    {
        $this->validate($request, [
            'file' => 'mimes:csv,txt',
        ]);

        $request->file->move(public_path('uploads'), $request->file->getClientOriginalName());

        DB::table('wefact')->insert([
            'file' => $request->file->getClientOriginalName(),
        ]);
    }

    public function weFactExport(Request $request)
    {
        $api = new WeFactAPIVoipzo();

        $file = DB::table('wefact')->where('id', '=', $request->id)->first();
        $filepath = public_path('uploads/' . $file->file);

        $header = NULL;
        $delimiter = ';';

        $data = array();

        if (($handle = fopen($filepath, 'r')) !== FALSE) {
            while (($row = fgetcsv($handle, 1000, $delimiter)) !== FALSE) {
                if (!$header) {
                    $header = $row;
                } else {
                    $data[] = array_combine($header, $row);
                }
            }
            fclose($handle);
        }

        $invoiceLines = array();
        $debtorCodes = array();

        foreach ($data as $item) {

            foreach ($item as $key => $value) {
                $new_m[$key] = $value;
            }

            $arnumb = $new_m['AR number'];
            $article_price = str_replace(",", ".", $new_m['article price(vatexclusive)']);
            $omschrijving = $new_m['Free text field 1'] . " " . $new_m['Free text field 2'];

            array_push($debtorCodes, $arnumb);
            array_push($invoiceLines, array(
                "DebtorCode" => $arnumb,
                'Description' => $omschrijving,
                'PriceExcl' => $article_price,
            ));
        }

        $invoices = [];

        foreach (array_unique($debtorCodes) as $dc) {
            foreach ($invoiceLines as $key => $il) {
                if ($dc == $il['DebtorCode']) {
                    $invoices[$dc][$key] = $il;
                }
            }
        }

        $list = $api->sendRequest('invoice', 'list', array());

        foreach ($invoices as $key => $iv) {
            $iv[] = array('Description' => 'Periode: ' . User::translateMonth(Carbon::now()->subMonth()->format('m')));

            if ($invoice = collect($list['invoices'])->where('DebtorCode', $key)->first()) {
                if ($invoice['Status'] == 0) {
                    $api->sendRequest('invoice', 'edit',
                        array(
                            "Identifier" => $invoice['Identifier'],
                            "InvoiceLines" => array_reverse($iv)
                        )
                    );

                } else {
                    $api->sendRequest('invoice', 'add',
                        array(
                            "DebtorCode" => $key,
                            "InvoiceLines" => array_reverse($iv)
                        )
                    );

                }
            } else {
                $api->sendRequest('invoice', 'add',
                    array(
                        "DebtorCode" => $key,
                        "InvoiceLines" => array_reverse($iv)
                    )
                );
            }
        }

        DB::table('wefact')->where('id', '=', $request->id)->delete();
    }

    public function deleteFile(Request $request)
    {
        DB::table('wefact')->where('id', '=', $request->id)->delete();
    }


    public function importCustomersAndProjects()
    {
        WeFactAPI::importCustomers();
        WeFactAPI::importProjects();
        session()->flash('success', 'Import gelukt!');
        return back();
    }
}
