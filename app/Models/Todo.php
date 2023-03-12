<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Todo extends Model
{
    use HasFactory;

    static function create($data)
    {
        $new = new Todo();
        $new->project_id = (is_numeric($data['todo_project_name']) ? $data['todo_project_name'] : NULL);
        $new->customer_id = (is_numeric($data['todo_project_name']) ? Project::find($data['todo_project_name'])->customer_id : NULL);
        $new->user_id = $data['todo_user'];
        $new->category_name = (isset($data['todo_category_name']) ? $data['todo_category_name'] : NULL);
        $new->title = $data['todo_name'];
        $new->description = $data['todo_description'];
        $new->save();

        return true;
    }

    static function saveEdit($todoId, $data)
    {
        $edit = Todo::find($todoId);
        $edit->project_id = (is_numeric($data['edit_todo_project_name']) ? $data['edit_todo_project_name'] : NULL);
        $edit->customer_id = (is_numeric($data['edit_todo_project_name']) ? Project::find($data['edit_todo_project_name'])->customer_id : NULL);
        $edit->user_id = $data['edit_todo_user'];
        $edit->category_name = (isset($data['edit_todo_category_name']) ? $data['edit_todo_category_name'] : NULL);
        $edit->title = $data['edit_todo_name'];
        $edit->description = $data['edit_todo_description'];
        $edit->save();

        return true;
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function getNiceFinishedDate($format = 1)
    {
        $day = date('d', strtotime($this->finished_date));
        $month = date('m', strtotime($this->finished_date));
        $year = date('Y', strtotime($this->finished_date));

        switch ($month) {
            case 1:
                $m = 'januari';
                $ms = 'jan';
                break;
            case 2:
                $m = 'februari';
                $ms = 'feb';
                break;
            case 3:
                $m = 'maart';
                $ms = 'mrt';
                break;
            case 4:
                $m = 'april';
                $ms = 'apr';
                break;
            case 5:
                $m = 'mei';
                $ms = 'mei';
                break;
            case 6:
                $m = 'juni';
                $ms = 'jun';
                break;
            case 7:
                $m = 'juli';
                $ms = 'jul';
                break;
            case 8:
                $m = 'augustus';
                $ms = 'aug';
                break;
            case 9:
                $m = 'september';
                $ms = 'sept';
                break;
            case 10:
                $m = 'oktober';
                $ms = 'okt';
                break;
            case 11:
                $m = 'november';
                $ms = 'nov';
                break;
            case 12:
                $m = 'december';
                $ms = 'dec';
                break;
        }

        if ($format == 1) {
            return $day . ' ' . $m . ' ' . $year;
        } else {
            return $day . ' ' . $ms . ' ' . $year;
        }
    }

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function getUserImage()
    {
        if ($this->user->profile_image != null) {

        } else {
            return asset('/img/person.webp');
        }
    }
}
