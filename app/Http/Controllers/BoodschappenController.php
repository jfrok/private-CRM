<?php

namespace App\Http\Controllers;

use App\Models\Boodschappen;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BoodschappenController extends Controller
{
    public function edit($id)
    {
        $lijst = Boodschappen::findOrFail($id);
        $boodschappenlijsten = DB::table('boodschappen')
            ->select('*')
            ->orderBy('id', 'desc')
            ->get();

        return view('boodschappenlijst.edit', [
            'lijst' => $lijst,
            'boodschappenlijsten' => $boodschappenlijsten,
        ]);
    }

    public function editLijstje(Request $request)
    {
        $this->validate($request, [
            'body' => 'required',
        ]);

        DB::table('boodschappen')
            ->where('id', '=', $request->id)
            ->update(['body' => $request->body]);

        return redirect('/boodschappenlijst');
    }
}
