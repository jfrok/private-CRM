<?php

namespace App\Http\Controllers;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function logout() {
        auth()->logout();
        return redirect('/');
    }

    public function checkLogin() {
        $email = $_GET['email'];
        $password = $_GET['password'];
        if($email != null && $password != null) {
            $user = User::where('email', $email)->first();
            if (Hash::check($password, $user->password)) {
                Auth::loginUsingId($user->id);
                Cookie::queue(Cookie::make('chosenUser', Auth::id()), 9999);
                $user->last_login = Carbon::now('Europe/Amsterdam');
                $user->save();
                activity()->performedOn($user)->causedBy($user)->log($user->name . ' ingelogd in systeem');
                return response()->json(true);
            } else {
                return response()->json(false);
            }
        } else {
            return response()->json(false);
        }
    }

    public function selectUser($id) {
        Cookie::queue(Cookie::make('chosenUser', $id, 604800));
    }

    public function index() {
        $users = User::all();
        return view('users.index', compact('users'));
    }

    public function create() {
        if(User::whereEmail(request()->email)->exists()) {
            return;
        } else {
            $new = new User();
            $new->name = request()->name;
            $new->email = request()->email;
            if(request()->password === request()->password_veri) {
                $new->password = bcrypt(request()->password);
            } else {
                return;
            }
            $new->role = 'Admin';
            $new->description = request()->description;
            $new->color = '#5561f6';
            $new->project_cost = 75.00;
            $new->hours_a_week = 40;
            $new->save();
        }

        activity()->performedOn($new)->causedBy(User::find(Cookie::get('chosenUser')))->log('Gebruiker '. $new->name . ' aangemaakt');
        $users = User::all();
        $data = view('users.ajax.usersTable', compact('users'))->render();
        return response()->json($data);
    }

    public function search() {
        $search = $_GET['searchQuery'];
        $query = User::where('name', 'like', '%'.$search.'%');
        $users = $query->get();
        $data = view('users.ajax.usersTable', compact('users'))->render();
        return response()->json($data);
    }

    public function show($id) {
        $user = User::find($id);
        return view('users.show', compact('user'));
    }

    public function loadView($id, $view) {
        $user = User::find($id);
        $data = view($view, compact('user'))->render();
        return response()->json($data);
    }

    public function delete($id) {
        $user = User::find($id);
        activity()->performedOn($user)->causedBy(User::find(Cookie::get('chosenUser')))->log('Gebruiker '. $user->name . ' verwijderd');
        $user->delete();

        return redirect('/gebruikers');
    }

    public function edit($id) {
        $user = User::find($id);
        if(request()->email != $user->email) {
            if(User::whereEmail(request()->email)->exists()) {
                $edit = false;
            } else {
                $edit = true;
                $user->email = request()->email;
            }
        } else {
            $edit = true;
        }

        if($edit == true) {
            $user->name = request()->name;
            if(request()->password != null) {
                if(request()->password === request()->password_veri) {
                    $user->password = bcrypt(request()->password);
                } else {
                    return;
                }
            }
            $user->role = 'Admin';
            $user->min_income = request()->min_income;
            $user->hourly_costs = request()->hourly_costs;
            $user->project_cost = request()->project_cost;
            $user->hours_a_dag = request()->hours_a_dag;
            $user->aantal_dagen = request()->aantal_dagen;
            $user->hours_a_week = request()->hours_a_week;
            $user->description = request()->description;
            $user->color = request()->user_color;
            $user->save();

            activity()->performedOn($user)->causedBy(User::find(Cookie::get('chosenUser')))->log('Gebruiker '. $user->name . ' aangepast');
        } else {
            return;
        }

        $data = view('users.ajax.showMain', compact('user'))->render();
        return response()->json($data);
    }

    public function changePerformanceTime($id, $month, $year) {
        Cookie::queue(Cookie::make('userPerformanceMonth', $month), 9999);
        Cookie::queue(Cookie::make('userPerformanceYear', $year), 9999);

        return response()->json(true);
    }
}
