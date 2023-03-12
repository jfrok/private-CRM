<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WorkOrder extends Model
{
    use HasFactory, SoftDeletes;

    static function getDeclarabelTeamHours()
    {
        $workorders = WorkOrder::whereMonth('date', Carbon::today('Europe/Amsterdam')->format('m'))->whereYear('date', Carbon::today('Europe/Amsterdam')->format('Y'))->get();
        $dec = 0;
        $all = 0;
        foreach ($workorders as $workorder) {
            $from = Carbon::parse($workorder->time_from);
            $to = Carbon::parse($workorder->time_to);
            if ($workorder->project && $workorder->project->include_count) {
                if ($workorder->status != "Niet meetellen") {
                    $all += $to->diffInMinutes($from) / 60;
                    if ($workorder->status == 'Declarabel') {
                        $dec += $to->diffInMinutes($from) / 60;
                    }
                }
            }
        }

        if ($all <= 0 || $dec <= 0) {
            return 0;
        } else {
            return ($dec / $all) * 100;
        }
    }

    static function getDeclarabelTeamHoursByMonthAndYear($month, $year)
    {
        $workorders = WorkOrder::whereMonth('date', $month)->whereYear('date', $year)->get();
        $dec = 0;
        $all = 0;
        foreach ($workorders as $workorder) {
            $from = Carbon::parse($workorder->time_from);
            $to = Carbon::parse($workorder->time_to);
            if ($workorder->project && $workorder->project->include_count) {
                if ($workorder->status != "Niet meetellen") {
                    $all += $to->diffInMinutes($from) / 60;
                    if ($workorder->status == 'Declarabel') {
                        $dec += $to->diffInMinutes($from) / 60;
                    }
                }
            }
        }

        if ($all <= 0 || $dec <= 0) {
            return 0;
        } else {
            return ($dec / $all) * 100;
        }
    }

    static function getCombinedTarget()
    {
        $target = floatval(0);
        foreach (User::all() as $user) {
            $target += $user->min_income;
        }

        return $target;
    }

    static function getAchievedTargetByMonthAndYear($month, $year)
    {
        $workorders = WorkOrder::whereMonth('date', $month)->whereYear('date', $year)->where('Status', 'Declarabel')->get();

        $price = floatval(0.00);
        foreach ($workorders as $workorder) {
            $project = Project::find($workorder->project_id);
            $from = Carbon::parse($workorder->time_from);
            $to = Carbon::parse($workorder->time_to);
            $single = $to->diffInMinutes($from) / 60;
            $price += floatval($single * $project->set_price);
        }

        return $price;
    }

    public function project()
    {
        return $this->hasOne(Project::class, 'id', 'project_id');
    }

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function getTotalTime()
    {
        $from = Carbon::parse($this->time_from);
        $to = Carbon::parse($this->time_to);
        return $to->diffInMinutes($from) / 60;
    }

    public function finishedTodos()
    {
        return Todo::where('workorder_id', $this->id)->get();
    }

    public function getNiceDate($format = 1)
    {
        $day = date('d', strtotime($this->date));
        $month = date('m', strtotime($this->date));
        $year = date('Y', strtotime($this->date));

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

    public function getTotalPrice($user = null)
    {

        $project = Project::find($this->project_id);
        $all = 0;

        $from = Carbon::parse($this->time_from);
        $to = Carbon::parse($this->time_to);
        $all += $to->diffInMinutes($from) / 60;

        if($user = null) {
            if ($project->set_price < \App\Models\User::where('id', $user)->first()->project_cost) {
                $price = floatval($all * $project->set_price);
            } else {
                $price = floatval($all * \App\Models\User::where('id', $user)->first()->project_cost);
            }
        } else {
            foreach(User::all() as $user) {
                if ($project->set_price < $user->project_cost) {
                    $price = floatval($all * $project->set_price);
                } else {
                    $price = floatval($all * $user->project_cost);
                }
            }
        }


        return $price;
    }

    public function getStatus()
    {
        switch ($this->status) {
            case "Niet Declarabel":
                $status = "close";
                break;
            case "Declarabel":
                $status = "check";
                break;
            case "Jaarfactuur":
                $status = "merge_type";
                break;
            case "Niet meetellen":
                $status = "hourglass_disabled";
                break;
            default:
                $status = "";
                break;
        }

        return $status;
    }

    public function getStatusColor()
    {
        switch ($this->status) {
            case "Niet Declarabel":
                $color = "red-text";
                break;
            case "Declarabel":
                $color = "green-text";
                break;
            case "Jaarfactuur":
                $color = "blue-text";
                break;
            case "Niet meetellen":
                $color = "grey-text";
                break;
            default:
                $color = "";
                break;
        }

        return $color;
    }
}
