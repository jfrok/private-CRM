<?php

namespace App\Http\Controllers;

use App\Models\Events;
use App\Models\User;
use App\Models\WorkOrder;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EventsController extends Controller
{
    public function create(Request $request)
    {
        $this->validate($request, [
            'datum_vanaf' => 'required',
            'datum_tot' => 'required',
            'titel' => 'required',
        ]);

        if ($request->user_name == 'Iedereen') {
            $userName = $request->user_name;
            $userId = 0;
        } elseif (is_numeric($request->user_name)) {
            $userName = User::where('id', $request->user_name)->first()->name;
            $userId = $request->user_name;
        } else {
            $userName = $request->user_name;
            $userId = User::where('name', $request->user_name)->first()->id;
        }

        Events::create([
            'user_name' => $userName,
            'user_id' => $userId,
            'project_id' => $request->project_id,
            'customer_id' => $request->customer_id,
            'datum_vanaf' => $request->datum_vanaf,
            'datum_tot' => $request->datum_tot,
            'tijd_vanaf' => $request->tijd_vanaf,
            'tijd_tot' => $request->tijd_tot,
            'titel' => $request->titel,
        ]);

        if ($request->todocheck == 'on') {
            DB::table('todo')->insert([
                'list_id' => $request->list_id,
                'user_id' => User::where('name', $request->user_name)->first()->id,
                'title' => $request->titel,
                'status' => 'Open',
            ]);
        }
    }

    public function edit(Request $request)
    {
        $this->validate($request, [
            'datum_vanaf_edit' => 'required',
            'datum_tot_edit' => 'required',
            'titel_edit' => 'required',
            'id' => 'required',
        ]);

        if ($request->user_name_edit == 'Iedereen') {
            $userId = 0;
        } else {
            $userId = User::where('name', $request->user_name_edit)->first()->id;
        }

        Events::where('id', $request->id)->update([
            'datum_vanaf' => $request->datum_vanaf_edit,
            'datum_tot' => $request->datum_tot_edit,
            'tijd_vanaf' => $request->tijd_vanaf_edit,
            'tijd_tot' => $request->tijd_tot_edit,
            'project_id' => $request->project_id_edit,
            'customer_id' => $request->customer_id_edit,
            'user_name' => $request->user_name_edit,
            'user_id' => $userId,
            'titel' => $request->titel_edit,
        ]);
    }

    public function addWeek($date)
    {
        $cookieDate = Carbon::parse($date)->addWeeks(1);
        $data = view('kalender.scripts.cookie', compact('cookieDate'))->render();

        return response()->json($data);
    }

    public function subtractWeek($date)
    {
        $cookieDate = Carbon::parse($date)->subWeeks(1);
        $data = view('kalender.scripts.cookie', compact('cookieDate'))->render();

        return response()->json($data);
    }

    public function today()
    {
        $cookieDate = Carbon::today('Europe/Amsterdam')->format('Y-m-d');
        $data = view('kalender.scripts.kalender', compact('cookieDate'))->render();

        return response()->json($data);
    }

    public function reload($date)
    {
        $cookieDate = Carbon::parse($date);
        $data = view('kalender.scripts.kalender', compact('cookieDate'))->render();

        return response()->json($data);
    }

    public function saveWorkorder(Request $request)
    {
        $this->validate($request, [
            'project_id_uren' => 'required',
            'status_uren' => 'required',
            'user_name_uren' => 'required',
            'datum_vanaf_uren' => 'required',
            'tijd_vanaf_uren' => 'required',
            'tijd_tot_uren' => 'required',
        ]);

        $userId = User::where('name', $request->user_name_uren)->first()->id;

        $new = new WorkOrder();
        $new->project_id = $request->project_id_uren;
        $new->user_id = $userId;
        $new->time_from = $request->tijd_vanaf_uren;
        $new->time_to = $request->tijd_tot_uren;
        $new->date = $request->datum_vanaf_uren;
        $new->status = $request->status_uren;
        $new->description = $request->titel_uren;
        $new->save();

        DB::table('todo')->where('title', $request->titel_uren)->update([
            'title' => '(✓) ' . $request->titel_uren,
            'status' => 'Afgerond',
        ]);

        Events::where('titel', $request->titel_uren)->update([
            'titel' => '(✓) ' . $request->titel_uren,
        ]);
    }

    public function filter($user)
    {
        $cookieDate = Carbon::today('Europe/Amsterdam')->format('Y-m-d');

        if ($user == 2) {
            $events = Events::where('user_id', $user)->get();
            $calendarId = 'agendaskytzonline@gmail.com';
            $data = view('kalender.scripts.filter', compact('events', 'calendarId', 'cookieDate'))->render();
        } elseif ($user == 0) {
            $data = view('kalender.scripts.kalender')->render();
        } elseif (is_numeric($user)) {
            $events = Events::where('user_id', $user)->get();
            $calendarId = '';
            $data = view('kalender.scripts.filter', compact('events', 'calendarId', 'cookieDate'))->render();
        } else {
            $events = Events::where('user_id', 0)->get();
            $calendarId = $user;
            $data = view('kalender.scripts.filter', compact('events', 'calendarId', 'cookieDate'))->render();
        }

        return response()->json($data);
    }

    public function delete(Request $request)
    {
        Events::where('id', $request->event_id)->delete();
    }
}
