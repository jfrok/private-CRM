<?php

namespace App\Http\Controllers;



use App\Models\Eenmalig;
use App\Models\Project;

use App\Models\Todo;

use App\Models\User;

use App\Models\WorkOrder;

use Carbon\Carbon;

use http\Env\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;



class WorkOrderController extends Controller

{

    public function create($projId, $fromTime, $toTime)

    {

        $new = new WorkOrder();

        $new->project_id = $projId;

        $new->user_id = Cookie::get('chosenUser');

        $new->time_from = $fromTime;

        $new->time_to = $toTime;

        $new->date = Carbon::today('Europe/Amsterdam');

        $new->save();

        activity()->performedOn($new)->causedBy(User::find(Cookie::get('chosenUser')))->log('Uren ingevuld voor project ' . $new->project->title);

        activity()->performedOn($new->project)->causedBy(User::find(Cookie::get('chosenUser')))->log('Uren ingevuld voor project ' . $new->project->title);

        $todos = Todo::where('project_id', $projId)->where('finished_user', Cookie::get('chosenUser'))->where('finished_date', Carbon::today('Europe/Amsterdam'))->get();

        foreach ($todos as $todo) {

            $todo->workorder_id = $new->id;

            $todo->save();

        }

        $chosenUser = User::find(Cookie::get('chosenUser'));

        $data = view('home.ajax.hoursModal', compact('chosenUser'))->render();

        return response()->json($data);

    }



    public function load()
    {

        $chosenUser = User::find(Cookie::get('chosenUser'));
        $chosenDate = Cookie::get('chosenDate');

        if ($chosenDate == null) {
            $chosenDate =  Carbon::today('Europe/Amsterdam')->format('Y-m-d');
        }

        $data = view('home.hoursList', compact('chosenUser', 'chosenDate'))->render();

        return response()->json($data);

    }



    public function createCustom()

    {

        $to = $_GET['to'];

        $from = $_GET['from'];

        $date = $_GET['date'];

        $project = $_GET['project'];

        $status = $_GET['status'];

        $description = $_GET['description'];

        $new = new WorkOrder();

        $new->project_id = $project;

        $new->user_id = Cookie::get('chosenUser');

        $new->time_from = $from;

        $new->time_to = $to;

        $new->date = $date;

        $new->status = $status;

        $new->description = $description;

        $new->save();

        activity()->performedOn($new)->causedBy(User::find(Cookie::get('chosenUser')))->log('Uren ingevuld voor project ' . $new->project->title);

        activity()->performedOn($new->project)->causedBy(User::find(Cookie::get('chosenUser')))->log('Uren ingevuld voor project ' . $new->project->title);

        $chosenUser = User::find(Cookie::get('chosenUser'));

        $data = view('home.ajax.hoursModal', compact('chosenUser'))->render();

        return response()->json($data);

    }



    public function changeDate($date, $position)

    {

        if ($position == 'center') {

            Cookie::queue(Cookie::make('chosenDate', $date));

            $chosenDate = $date;

        } elseif ($position == 'prev') {

            $newDate = Carbon::parse($date)->subDay()->format('Y-m-d');

            Cookie::queue(Cookie::make('chosenDate', $newDate));

            $chosenDate = $newDate;

        } else {

            $newDate = Carbon::parse($date)->addDay()->format('Y-m-d');

            Cookie::queue(Cookie::make('chosenDate', $newDate));

            $chosenDate = $newDate;

        }

        $chosenUser = User::find(Cookie::get('chosenUser'));

        $data = view('home.hoursList', compact('chosenUser', 'chosenDate'))->render();

        return response()->json($data);

    }



    public function viewWorkOrder($id)

    {

        $chosenWorkorder = WorkOrder::find($id);

        $data = view('home.ajax.viewHoursModal', compact('chosenWorkorder'))->render();

        return response()->json($data);

    }



    public function editWorkOrder($id)

    {

        $to = $_GET['to'];

        $from = $_GET['from'];

        $date = $_GET['date'];

        $project = $_GET['project'];

        $status = $_GET['status'];

        $description = $_GET['description'];

        $new = WorkOrder::find($id);

        $new->project_id = $project;

        $new->time_from = $from;

        $new->time_to = $to;

        $new->date = $date;

        $new->status = $status;

        $new->description = $description;

        $new->save();

        activity()->performedOn($new)->causedBy(User::find(Cookie::get('chosenUser')))->log('Uren aangepast voor project ' . $new->project->title);

        activity()->performedOn($new->project)->causedBy(User::find(Cookie::get('chosenUser')))->log('Uren aangepast voor project ' . $new->project->title);

        return;

    }



    public function deleteWorkOrder($id)

    {

        $workorder = WorkOrder::find($id);

        $todos = Todo::where('workorder_id', $id)->get();

        foreach ($todos as $todo) {

            $todo->workorder_id = null;

            $todo->save();

        }

        $workorder->delete();

        activity()->performedOn($workorder)->causedBy(User::find(Cookie::get('chosenUser')))->log('Uren verwijderd voor project ' . $workorder->project->title);

        activity()->performedOn($workorder->project)->causedBy(User::find(Cookie::get('chosenUser')))->log('Uren verwijderd voor project ' . $workorder->project->title);

        $chosenUser = User::find(Cookie::get('chosenUser'));

        $data = view('home.ajax.hoursModal', compact('chosenUser'))->render();

        return response()->json($data);

    }



    public function showSummary()

    {

        $users = User::all();

        $month = Carbon::today('Europe/Amsterdam')->format('m');

        $year = Carbon::today('Europe/Amsterdam')->format('Y');

        return view('workorders.summary', compact('users', 'month', 'year'));

    }



    public function reloadSummary()

    {

        $users = User::all();

        $month = Carbon::today('Europe/Amsterdam')->format('m');

        $year = Carbon::today('Europe/Amsterdam')->format('Y');

        $data = view('workorders.ajax.workOrderList', compact('users', 'month', 'year'))->render();

        return response()->json($data);

    }



    public function changeTimeline($month, $year)

    {

        Cookie::queue(Cookie::make('workorderTimelineMonth', $month), 9999);

        Cookie::queue(Cookie::make('workorderTimelineYear', $year), 9999);

        return response()->json(true);

    }

    public function eenmaligeBedrag(\Illuminate\Http\Request $request) {
        $this->validate($request, [
            'bedrijf' => 'required',
            'datum' => 'required',
            'prijs' => 'required',
        ]);

        Eenmalig::create([
            'user_id' => $request->user_id,
            'bedrijfsnaam' => $request->bedrijf,
            'datum' => $request->datum,
            'prijs' => $request->prijs,
        ]);

        return redirect('/home');
    }

}
