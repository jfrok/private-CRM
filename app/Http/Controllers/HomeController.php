<?php

namespace App\Http\Controllers;

use App\Models\Boodschappen;
use App\Models\Customer;
use App\Models\CustomerAddress;
use App\Models\CustomerContact;
use App\Models\Events;
use App\Models\Project;
use App\Models\Todo;
use App\Models\User;
use App\Models\Wiki;
use App\Models\WikiComment;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $users = User::all();
        $customers = Customer::all();
        $chosenUser = User::find((Cookie::has('chosenUser') ? Cookie::get('chosenUser') : Auth::id()));
        $projects = Project::all();
        $todos = Todo::where('project_id', NULL)->where('user_id', ($chosenUser ? $chosenUser->id : Auth::id()))->where('status', 'Open')->get();

        $chosenDate = Carbon::today('Europe/Amsterdam')->format('Y-m-d');

        if (Cookie::has('chosenDate')) {
            $chosenDate = Cookie::get('chosenDate');
        }

        return view('home', compact('users', 'chosenUser', 'customers', 'projects', 'todos', 'chosenDate'));
    }

    public function games()
    {
        return view('games.index');
    }

    public function dinoRun()
    {
        return view('games.dinoRun');
    }

    public function monkeyIsland()
    {
        return view('games.monkeyIsland');
    }

    public function spiderSolitaire()
    {
        return view('games.spiderSolitaire');
    }

    public function goldMiner()
    {
        return view('games.goldMiner');
    }

    public function cleaningSchema()
    {
        return view('misc.cleaningSchema');
    }

    public function wiki()
    {
        $wiki = DB::table('wiki')
            ->select('*')
            ->orderBy('id', 'desc')
            ->get();

        return view('wiki.index', [
            'wiki' => $wiki,
        ]);
    }

    public function editWiki($id)
    {
        $wiki = DB::table('wiki')
            ->select('*')
            ->orderBy('id', 'desc')
            ->get();

        $post = Wiki::findOrFail($id);

        return view('wiki.edit', [
            'post' => $post,
            'wiki' => $wiki,
        ]);
    }

    public function editWikiComment($id)
    {
        $wiki = DB::table('wiki')
            ->select('*')
            ->orderBy('id', 'desc')
            ->get();

        $post = WikiComment::findOrFail($id);

        return view('wiki.editcomment', [
            'post' => $post,
            'wiki' => $wiki,
        ]);
    }

    public function boodschappenLijst()
    {
        $id = 1;

        $lijst = Boodschappen::findOrFail($id);
        $boodschappenlijsten = DB::table('boodschappen')
            ->select('*')
            ->orderBy('id', 'desc')
            ->get();

        return view('boodschappenlijst.show', [
            'lijst' => $lijst,
            'boodschappenlijsten' => $boodschappenlijsten,
        ]);
    }

    public function kalender()
    {
        $projects = DB::table('projects')
            ->select('projects.id AS project_id', 'customers.company_name', 'projects.title')
            ->where('status', '=', 'Open')
            ->join('customers', 'customer_id', '=', 'customers.id')
            ->get();

        $klanten = DB::table('customers')
            ->get();

        $boards = DB::table('todo_boards')
            ->select('todo_boards.*', 'customers.company_name',
                'projects.title AS project_title')
            ->where('todo_boards.status', '=', 'Open')
            ->join('projects', 'project_id', '=', 'projects.id')
            ->join('customers', 'projects.customer_id', '=', 'customers.id')
            ->orderBy('todo_boards.id', 'desc')
            ->get();

        Cookie::queue(Cookie::make('calendarDate', Carbon::today('Europe/Amsterdam')->format('Y-m-d')));

        return view('kalender.index', [
            'events' => Events::all(),
            'projects' => $projects,
            'klanten' => $klanten,
            'boards' => $boards,
            'cookieDate' => Carbon::today('Europe/Amsterdam')->format('Y-m-d')
        ]);
    }
    public function kalenderTest()
    {
        $users = User::all();
        $customers = Customer::all();
        $chosenUser = User::find((Cookie::has('chosenUser') ? Cookie::get('chosenUser') : Auth::id()));
        $allProjects = Project::all();
        $todos = Todo::where('project_id', NULL)->where('user_id', ($chosenUser ? $chosenUser->id : Auth::id()))->where('status', 'Open')->get();

        $chosenDate = Carbon::today('Europe/Amsterdam')->format('Y-m-d');

        if (Cookie::has('chosenDate')) {
            $chosenDate = Cookie::get('chosenDate');
        }

        $projects = DB::table('projects')
            ->select('projects.id AS project_id', 'customers.company_name', 'projects.title')
            ->where('status', '=', 'Open')
            ->join('customers', 'customer_id', '=', 'customers.id')
            ->get();

        $klanten = DB::table('customers')
            ->get();

        $boards = DB::table('todo_boards')
            ->select('todo_boards.*', 'customers.company_name',
                'projects.title AS project_title')
            ->where('todo_boards.status', '=', 'Open')
            ->join('projects', 'project_id', '=', 'projects.id')
            ->join('customers', 'projects.customer_id', '=', 'customers.id')
            ->orderBy('todo_boards.id', 'desc')
            ->get();

        Cookie::queue(Cookie::make('calendarDate', Carbon::today('Europe/Amsterdam')->format('Y-m-d')));

        return view('home', [
            'events' => Events::all(),
            'projects' => $projects,
            'klanten' => $klanten,
            'users' => $users,
            'customers' => $customers,
            'allProjects' => $allProjects,
            'chosenUser' => $chosenUser,
            'chosenDate' => $chosenDate,
            'todos' => $todos,
            'boards' => $boards,
            'cookieDate' => Carbon::today('Europe/Amsterdam')->format('Y-m-d')
        ]);
    }
    public function todo()
    {
        $projects = DB::table('projects')
            ->select('projects.id AS project_id', 'customers.company_name', 'projects.title')
            ->join('customers', 'customer_id', '=', 'customers.id')
            ->get();

        $boards = DB::table('todo_boards')
            ->select('todo_boards.*', 'customers.company_name',
                'projects.title AS project_title')
            ->where('todo_boards.status', '=', 'Open')
            ->join('projects', 'project_id', '=', 'projects.id')
            ->join('customers', 'projects.customer_id', '=', 'customers.id')
            ->orderBy('todo_boards.id', 'desc')
            ->get();

        return view('todo.index', [
            'projects' => $projects,
            'boards' => $boards,
        ]);
    }

    public function csv()
    {
        $csvFile = asset('csv.csv');
        $file_handle = fopen($csvFile, 'r');
        while (!feof($file_handle)) {
            $line_of_text[] = fgetcsv($file_handle, 0, ';');
        }
        fclose($file_handle);
        foreach ($line_of_text as $l) {
            $new = new Customer();
            if ($l[1] == "") {
                $new->company_name = 'Onbekend...';
            } else {
                $new->company_name = $l[1];
            }
            $new->save();

            $newC = new CustomerContact();
            $newC->customer_id = $new->id;
            $newC->function = 'Eigenaar';
            $newC->first_name = $l[5];
            $newC->last_name = $l[6];
            $newC->email = $l[11];
            $newC->save();

            $newA = new CustomerAddress();
            $newA->customer_id = $new->id;
            $newA->status = 'Hoofdlocatie';
            $newA->address = $l[7];
            $newA->zip_code = $l[8];
            $newA->place = $l[9];
            $newA->save();
        }
    }
}
