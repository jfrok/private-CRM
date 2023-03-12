<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Project;
use App\Models\Todo;
use App\Models\User;
use App\Models\WorkOrder;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cookie;

class ProjectController extends Controller
{
    public function index()
    {
        $projects = Project::where('status', 'Open')->where('status', '=', 'Open')->orderByDesc('id')->get();
        $customers = Customer::all();
        $users = User::all();

        return view('projects.index', compact('projects', 'customers', 'users'));
    }

    public function search()
    {
        $search = $_GET['searchQuery'];
        $status = $_GET['searchStatus'];

        if ($status != null) {
            if ($status != 'Alle statussen') {
                $projects = Project::select('projects.*')->join('customers', 'projects.customer_id', '=', 'customers.id')->where('title', 'like', '%' . $search . '%')->orWhere('company_name', 'like', '%' . $search . '%')->where('status', $status)->get();
            } else {
                $projects = Project::select('projects.*')->join('customers', 'projects.customer_id', '=', 'customers.id')->where('title', 'like', '%' . $search . '%')->orWhere('company_name', 'like', '%' . $search . '%')->get();
            }
        } else {
            $projects = Project::where('title', 'like', '%' . $search . '%')->get();
        }

        $data = view('projects.ajax.projectsTable', compact('projects'))->render();
        return response()->json($data);
    }

    public function create()
    {
        $new = new Project();
        $new->customer_id = request('customer_id');
        $new->user_id = request('user_id');
        $new->title = request('title');
        $new->description = request('description');
        if (request('include_count')) {
            $new->include_count = true;
        } else {
            $new->include_count = false;
        }
        $new->set_price = request('set_price');
        $new->set_hours = request('set_hours');
        $new->status = 'Open';
        $new->save();

        activity()->performedOn($new)->causedBy(auth()->user())->log('Project ' . $new->title . ' aangemaakt');

        $projects = Project::all();
        $data = view('projects.ajax.projectsTable', compact('projects'))->render();
        return response()->json($data);
    }

    public function getLast()
    {
        $project = Project::orderByDesc('created_at')->first();
        return $project->id;
    }

    public function show($id)
    {
        $project = Project::find($id);
        return view('projects.show', compact('project'));
    }

    public function deleteProject($id)
    {
        $customer = Project::find($id);
        $customer->delete();

        session()->flash('success', 'Project verwijderd!');
        return redirect('/projecten');
    }

    public function loadNewPage($id, $view)
    {
        $project = Project::find($id);
        $chartData = [
            'AgreedHours' => number_format($project->set_price * $project->set_hours, 2, '.', ''),
            'WorkedHours' => number_format($project->getWorkedHours() * $project->set_price, 2, '.', '')
        ];
        $data = view($view, compact('project', 'chartData'))->render();
        return response()->json($data);
    }

    public function finishTask($projectId, $todoId)
    {
        $todo = Todo::find($todoId);
        $todo->status = 'Afgerond';
        $todo->finished_date = Carbon::now('Europe/Amsterdam');
        $todo->finished_user = Cookie::get('chosenUser');
        $todo->save();

        activity()->performedOn($todo)->causedBy(User::find(Cookie::get('chosenUser')))->log('Todo ' . $todo->title . ' afgerond');

        $project = Project::find($projectId);
        $data = view('projects.ajax.showTodos', compact('project'))->render();
        return response()->json($data);
    }

    public function openTask($projectId, $todoId)
    {
        $todo = Todo::find($todoId);
        $todo->status = 'Open';
        $todo->finished_date = NULL;
        $todo->finished_user = NULL;
        $todo->save();

        activity()->performedOn($todo)->causedBy(User::find(Cookie::get('chosenUser')))->log('Todo ' . $todo->title . ' geopend');

        $project = Project::find($projectId);
        $data = view('projects.ajax.showTodos', compact('project'))->render();
        return response()->json($data);
    }

    public function createTask($projectId)
    {
        $new = new Todo();
        $new->project_id = $projectId;
        $new->user_id = Cookie::get('chosenUser');
        $new->category_name = request('category_name');
        $new->title = request('name');
        $new->description = request('description');
        $new->status = 'Open';
        $new->save();

        activity()->performedOn($new)->causedBy(User::find(Cookie::get('chosenUser')))->log('Adres ' . $new->title . ' aangemaakt');

        $project = Project::find($projectId);
        $data = view('projects.ajax.showTodos', compact('project'))->render();
        return response()->json($data);
    }

    public function showTask($projectId, $todoId)
    {
        $chosenTodo = Todo::find($todoId);
        $project = Project::find($projectId);
        $data = view('projects.ajax.todoModal', compact('chosenTodo', 'project'))->render();
        return response()->json($data);
    }

    public function editTask($projectId, $todoId)
    {
        if (request('status') == 'Verwijderd') {
            $todo = Todo::find($todoId);
            activity()->performedOn($todo)->causedBy(User::find(Cookie::get('chosenUser')))->log('Todo ' . $todo->title . ' verwijderd');
            $todo->delete();
        } else {
            $todo = Todo::find($todoId);
            $todo->category_name = request('edit_category_name');
            $todo->title = request('name');
            $todo->description = request('edit_description');
            $todo->status = request('status');
            $todo->save();
            activity()->performedOn($todo)->causedBy(User::find(Cookie::get('chosenUser')))->log('Todo ' . $todo->title . ' aangepast');
        }

        $project = Project::find($projectId);
        $data = view('projects.ajax.showTodos', compact('project'))->render();
        return response()->json($data);
    }

    public function changeTimeline($id, $month, $year)
    {
        Cookie::queue(Cookie::make('timelineMonth', $month), 9999);
        Cookie::queue(Cookie::make('timelineYear', $year), 9999);

        return response()->json(true);
    }

    public function edit($id)
    {
        $new = Project::find($id);
        $new->customer_id = request('customer_id');
        $new->user_id = request('user_id');
        $new->title = request('title');
        $new->description = request('description');
        if (request('include_count')) {
            $new->include_count = true;
        } else {
            $new->include_count = false;
        }
        $new->set_price = request('set_price');
        $new->set_hours = request('set_hours');
        $new->status = 'Open';
        $new->save();

        activity()->performedOn($new)->causedBy(auth()->user())->log('Project ' . $new->title . ' aangepast');
        return;
    }

    public function editStatus($id, $status)
    {
        $project = Project::find($id);
        $project->status = $status;
        $project->save();

        activity()->performedOn($project)->causedBy(User::find(Cookie::get('chosenUser')))->log('Project status van ' . $project->title . ' aangepast naar ' . $project->status);
    }

    public function yearlyInvoices($year)
    {
        $projectIds = WorkOrder::where('status', 'Jaarfactuur')->whereyear('date', $year)->distinct('project_id')->pluck('project_id')->toArray();
        $customerIds = Project::whereIn('id', $projectIds)->distinct('customer_id')->pluck('customer_id')->toArray();
        $customers = Customer::whereIn('id', $customerIds)->get();

        return view('projects.yearly-invoices', compact('customers', 'year'));
    }

    public function saveYearlyInvoices()
    {
        WorkOrder::whereIn('id', request()->workOrders)->update(['status' => 'Declarabel']);
        session()->flash('success', 'Geselecteerde uren aangepast!');
        return back();
    }
    public function pieChart($pid)
    {
        $project = Project::find($pid);

        $chartData = [
            'AgreedHours' => number_format($project->set_price * $project->set_hours, 2, '.', ''),
            'WorkedHours' => number_format($project->getWorkedHours() * $project->set_price, 2, '.', '')
        ];

         //dd($chartData);
        return view('projects.ajax.showMain',compact('chartData','project'));
        // dd($result);
    }
}
