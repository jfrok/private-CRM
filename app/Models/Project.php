<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Models\Activity;

class Project extends Model
{
    use HasFactory, SoftDeletes;

    public function customer()
    {
        return $this->hasOne(Customer::class, 'id', 'customer_id');
    }

    public function workorders()
    {
        return $this->hasMany(WorkOrder::class, 'project_id', 'id');
    }

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function calculatedPrice()
    {
        return number_format($this->set_price * $this->set_hours, 2, ',', '.');
    }

    public function setPrice()
    {
        return number_format($this->set_price, 2, ',', '.');
    }

    public function getNiceDate($format = 1)
    {
        $day = date('d', strtotime($this->created_at));
        $month = date('m', strtotime($this->created_at));
        $year = date('Y', strtotime($this->created_at));

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


    public function last10Activities()
    {
        return Activity::where('subject_type', 'App\Models\Project')->where('subject_id', $this->id)->orderByDesc('created_at')->limit(10)->get();
    }

    public function involvedUsers()
    {
        $todos = Todo::where('project_id', $this->id)->distinct('user_id')->get('user_id')->toArray();
        $users = User::whereIn('id', [$todos])->get();
        return $users;
    }

    public function involvedUsersHours()
    {
        return [38, 2];
    }

    public function todoCategories()
    {
        return Todo::where('project_id', $this->id)->distinct('category_name')->get('category_name')->toArray();
    }

    public function getTodosByCatName($name)
    {
        return Todo::where('project_id', $this->id)->where('category_name', $name)->get();
    }

    public function getProgress()
    {
        $todos = Todo::where('project_id', $this->id)->get();
        $doneCount = $todos->where('status', '!=', 'Open')->count();
        $allCount = $todos->count();
        if ($todos->count() < 1) {
            return 0;
        } else {
            return ($doneCount / $allCount) * 100;
        }
    }

    public function userWorkordersByDate($user, $month, $year)
    {
        return WorkOrder::where('user_id', $user)->whereMonth('date', $month)->whereYear('date', $year)->get();
    }

    public function currentCalculatedPrice()
    {
        return number_format($this->getWorkedHours() * $this->set_price, 2, ',', '.');
    }

    public function getWorkedHours()
    {
        $workorders = WorkOrder::where('project_id', $this->id)->where('status', 'Declarabel')->get();

        $hours = 0;
        foreach ($workorders as $workorder) {
            $from = Carbon::parse($workorder->time_from);
            $to = Carbon::parse($workorder->time_to);
            $hours += $to->diffInMinutes($from) / 60;
        }
        return $hours;
    }

    public function notDeclarabelPrice()
    {
        return number_format($this->getNotDeclarabelHours() * $this->set_price, 2, ',', '.');
    }

    public function getNotDeclarabelHours()
    {
        $workorders = WorkOrder::where('project_id', $this->id)->where('status', 'Niet Declarabel')->get();

        $hours = 0;
        foreach ($workorders as $workorder) {
            $from = Carbon::parse($workorder->time_from);
            $to = Carbon::parse($workorder->time_to);
            $hours += $to->diffInMinutes($from) / 60;
        }
        return $hours;
    }

    public function getAllTotalPrice()
    {
        $workorders = WorkOrder::where('project_id', $this->id)->where('status', '!=', 'Niet meetellen')->get();

        $hours = 0;
        foreach ($workorders as $workorder) {
            $from = Carbon::parse($workorder->time_from);
            $to = Carbon::parse($workorder->time_to);
            $hours += $to->diffInMinutes($from) / 60;
        }
        return number_format($hours * $this->set_price, 2, ',', '.');
    }

    public function getYearlyWorkOrders($year)
    {
        return WorkOrder::where('status', 'Jaarfactuur')->where('project_id', $this->id)->whereyear('date', $year)->get();
    }

    public function totalYearlyPrice($year)
    {
        $workOrders = WorkOrder::where('status', 'Jaarfactuur')->where('project_id', $this->id)->whereyear('date', $year)->get();
        $hours = 0;
        foreach ($workOrders as $workOrder) {
            $from = Carbon::parse($workOrder->time_from);
            $to = Carbon::parse($workOrder->time_to);
            $hours += $to->diffInMinutes($from) / 60;
        }
        return number_format($hours * $this->set_price, 2, ',', '.');
    }


    public static function getUserRemainingHours($user) {
        $hours = 0;

        foreach (Project::where([['user_id', $user], ['set_hours', '!=', null], ['status', 'Open'], ['deleted_at', null]])->get() as $project) {
            if ($project->set_hours !== null) {
                $hours += $project->set_hours - $project->getWorkedHours();
            }
        }

        return $hours;
    }

    public static function getUserDoneDate($userId, $remainingHours)
    {
        $user = User::find($userId);

        // Set current date
        $date = Carbon::now();
        $diffInDays = $date->diffInDays(Carbon::now()->startOfWeek()) + 1;
        $dagCounter = $diffInDays;

        for ($i = 0; $i < round($remainingHours / $user->hours_a_dag); $i++) {
            // If day counter equals or is lower then 'aantal_dagen'
            if ($dagCounter < $user->aantal_dagen) {
                $date->addWeekday();
                $dagCounter++;
            } else {
                $date->addWeek();
                $date->startOfWeek();
                $dagCounter = 1;
            }
        }
        return $date;
    }

    public static function getUserHours($user) {
        $hours = 0;

        foreach (Project::where([['user_id', $user], ['set_hours', '!=', null], ['status', 'Open'], ['deleted_at', null]])->get() as $project) {
            if ($project->set_hours !== null) {
                $hours += $project->set_hours;
            }
        }

        return $hours;
    }
}
