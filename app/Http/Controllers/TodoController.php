<?php

namespace App\Http\Controllers;

use App\Models\Events;
use App\Models\User;
use App\Models\WorkOrder;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TodoController extends Controller
{
    public function showBoard($id)
    {
        $projects = DB::table('projects')
            ->select('projects.id AS project_id', 'customers.company_name', 'projects.title')
            ->join('customers', 'customer_id', '=', 'customers.id')
            ->get();

        $board = DB::table('todo_boards')->where('id', '=', $id)->first();
        $lists = DB::table('todo_list')->where('board_id', '=', $id)->get();

        $data = view('todo.show', compact('board', 'lists', 'projects'))->render();

        return response()->json($data);
    }

    public function createBoard(Request $request)
    {
        $this->validate($request, [
            'title' => 'required',
        ]);

        DB::table('todo_boards')->insert([
            'project_id' => 1,
            'title' => $request->title,
            'status' => 'Open',
            'datum_aangemaakt' => Carbon::today('Europe/Amsterdam')->format('Y-m-d'),
        ]);
    }

    public function editBoard(Request $request)
    {
        $this->validate($request, [
            'title' => 'required',
            'status' => 'required',
        ]);

        DB::table('todo_boards')->where('id', $request->id)->update([
            'title' => $request->title,
            'status' => $request->status,
        ]);
    }

    public function deleteBoard(Request $request)
    {
        $list = DB::table('todo_list')->where('board_id', $request->board_id)->first();

        DB::table('todo')->where('list_id', $list->id)->delete();
        DB::table('todo_list')->where('board_id', $request->board_id)->delete();
        DB::table('todo_boards')->where('id', $request->board_id)->delete();
    }

    public function reloadBoards()
    {
        $boards = DB::table('todo_boards')
            ->select('todo_boards.*', 'customers.company_name',
                'projects.title AS project_title')
            ->where('todo_boards.status', '=', 'Open')
            ->join('projects', 'project_id', '=', 'projects.id')
            ->join('customers', 'projects.customer_id', '=', 'customers.id')
            ->orderBy('todo_boards.id', 'desc')
            ->get();

        $data = view('todo.ajax.boards', compact('boards'))->render();

        return response()->json($data);
    }

    public function reloadBoardsList()
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

        $data = view('todo.index', compact('boards', 'projects'))->render();

        return response()->json($data);
    }

    public function reloadBoardsStatus($status)
    {
        if ($status !== 'Alle statussen') {
            $boards = DB::table('todo_boards')
                ->select('todo_boards.*', 'customers.company_name',
                    'projects.title AS project_title')
                ->where('todo_boards.status', '=', $status)
                ->join('projects', 'project_id', '=', 'projects.id')
                ->join('customers', 'projects.customer_id', '=', 'customers.id')
                ->orderBy('todo_boards.id', 'desc')
                ->get();
        } else {
            $boards = DB::table('todo_boards')
                ->select('todo_boards.*', 'customers.company_name',
                    'projects.title AS project_title')
                ->join('projects', 'project_id', '=', 'projects.id')
                ->join('customers', 'projects.customer_id', '=', 'customers.id')
                ->orderBy('todo_boards.id', 'desc')
                ->get();
        }

        $data = view('todo.ajax.boards', compact('boards'))->render();

        return response()->json($data);
    }

    public function createList(Request $request)
    {
        $this->validate($request, [
            'board_id' => 'required',
            'title' => 'required',
        ]);

        DB::table('todo_list')->insert([
            'board_id' => $request->board_id,
            'title' => $request->title,
            'project_id' => $request->project_id,
        ]);
    }

    public function editList(Request $request)
    {
        $this->validate($request, [
            'title' => 'required',
        ]);

        DB::table('todo_list')->where('id', $request->list_id)->update([
            'title' => $request->title,
            'project_id' => $request->project_id,
        ]);
    }

    public function deleteList(Request $request)
    {
        DB::table('todo')->where('list_id', $request->list_id)->where('status', '=', 'Open')->delete();
        DB::table('todo_list')->where('id', $request->list_id)->delete();
    }

    public function reloadLists($id)
    {
        $lists = DB::table('todo_list')->where('board_id', '=', $id)->get();

        $data = view('todo.ajax.lists', compact('lists'))->render();

        return response()->json($data);
    }

    public function loadFinished($id)
    {
        $lists = DB::table('todo_list')->where('board_id', '=', $id)->get();

        $data = view('todo.ajax.list-afgerond', compact('lists'))->render();

        return response()->json($data);
    }

    public function createTodo(Request $request)
    {
        $this->validate($request, [
            'list_id' => 'required',
            'user_id' => 'required',
            'title' => 'required',
        ]);

        DB::table('todo')->insert([
            'list_id' => $request->list_id,
            'user_id' => $request->user_id,
            'title' => $request->title,
            'status' => 'Open',
        ]);

        if (isset($request->tijd_vanaf)) {
            $userName = User::where('id', $request->user_id)->first()->name;
            $userId = $request->user_id;

            Events::create([
                'user_name' => $userName,
                'user_id' => $userId,
                'project_id' => $request->project_id,
                'customer_id' => $request->klant_id,
                'datum_vanaf' => $request->datum_vanaf,
                'datum_tot' => $request->datum_tot,
                'tijd_vanaf' => $request->tijd_vanaf,
                'tijd_tot' => $request->tijd_tot,
                'titel' => $request->title,
            ]);
        }
    }

    public function editTodo(Request $request)
    {
        $this->validate($request, [
            'title' => 'required',
        ]);

        $title = DB::table('todo')->where('id', '=', $request->todo_id)->first()->title;

        DB::table('events')->where('titel', '=', $title)->update([
            'titel' => $request->title,
        ]);

        DB::table('todo')->where('id', $request->todo_id)->update([
            'title' => $request->title,
            'user_id' => $request->user_id,
        ]);
    }

    public function deleteTodo(Request $request)
    {
        $event_title = DB::table('todo')->where('id', $request->todo_id)->first()->title;

        DB::table('todo')->where('id', $request->todo_id)->delete();
        Events::where('titel', $event_title)->delete();
    }

    public function finishTodo(Request $request)
    {
        $todo = DB::table('todo')->where('id', $request->todo_id)->first();

        DB::table('todo')->where('id', $request->todo_id)->update([
            'title' => '(✓) ' . $todo->title,
            'status' => 'Afgerond',
        ]);

        Events::where('titel', $todo->title)->update([
            'titel' => '(✓) ' . $todo->title,
        ]);

        if (isset($request->tijd_vanaf_uren)) {
            $new = new WorkOrder();
            $new->project_id = $request->project_id_uren;
            $new->user_id = $request->user_id_uren;
            $new->time_from = $request->tijd_vanaf_uren;
            $new->time_to = $request->tijd_tot_uren;
            $new->date = $request->datum;
            $new->status = $request->status_uren;
            $new->description = $todo->title;
            $new->save();
        }
    }
}
