<?php

namespace App\Http\Controllers;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;

class NotesController extends Controller
{
    public function reload()
    {
        $chosenUser = User::find((Cookie::has('chosenUser') ? Cookie::get('chosenUser') : Auth::id()));
        $data = view('home.ajax.notitie-list', compact('chosenUser'))->render();

        return response()->json($data);
    }

    public function show($id)
    {
        $chosenUser = User::find((Cookie::has('chosenUser') ? Cookie::get('chosenUser') : Auth::id()));
        $note = DB::table('notes')->where('id', '=', $id)->first();
        $data = view('home.ajax.view-note', compact('chosenUser', 'note'))->render();

        return response()->json($data);
    }

    public function create(Request $request)
    {
        $this->validate($request, [
            'title' => 'required',
            'body' => 'required',
        ]);

        DB::table('notes')->insert([
            'project_id' => $request->project_id,
            'title' => $request->title,
            'body' => $request->body,
            'user_id' => $request->user_id,
            'date_added' => Carbon::today('Europe/Amsterdam')->format('d-m-Y'),
        ]);
    }

    public function edit(Request $request)
    {
        $this->validate($request, [
            'title' => 'required',
            'body' => 'required',
        ]);

        DB::table('notes')->where('id', '=', $request->id)->update([
            'project_id' => $request->project_id,
            'title' => $request->title,
            'body' => $request->body,
            'user_id' => $request->user_id,
        ]);
    }


    public function delete(Request $request)
    {
        DB::table('notes')->where('id', '=', $request->id)->delete();
    }
}
