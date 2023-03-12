<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use NotificationChannels\WebPush\HasPushSubscriptions;
use Spatie\Activitylog\Models\Activity;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes, HasPushSubscriptions;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'color',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function calls()
    {
        return $this->hasMany(Call::class);
    }

    public function workorders()
    {
        return $this->hasMany(WorkOrder::class);
    }

    public function activities()
    {
        return Activity::where('causer_type', 'App\Models\User')->where('causer_id', $this->id)->orderByDesc('id')->get();
    }

    public function finishedTodoProjects()
    {
        $todos = Todo::where('finished_user', $this->id)->where('finished_date', Carbon::today('Europe/Amsterdam'))->where('workorder_id', null)->distinct()->get('project_id')->toArray();
        return Project::whereIn('id', $todos)->get();
    }

    public function countFinishedTodosBasedOnProject($proj)
    {
        return Todo::where('finished_user', $this->id)->where('finished_date', Carbon::today('Europe/Amsterdam'))->where('workorder_id', null)->where('project_id', $proj)->count();
    }

    public function finishedTodosBasedOnProject($proj)
    {
        return Todo::where('finished_user', $this->id)->where('finished_date', Carbon::today('Europe/Amsterdam'))->where('workorder_id', null)->where('project_id', $proj)->get();
    }

    public function getWorkOrdersByDate($date)
    {
        return WorkOrder::where('user_id', Cookie::get('chosenUser'))->where('date', $date)->orderBy('time_from')->get();
    }

    public function getWorkedHoursByDate($date)
    {
        $workorders = WorkOrder::where('user_id', Cookie::get('chosenUser'))->where('date', $date)->orderBy('time_from')->get();
        $sum = 0;
        foreach ($workorders as $workorder) {
            $from = Carbon::parse($workorder->time_from);
            $to = Carbon::parse($workorder->time_to);
            $sum += $to->diffInMinutes($from);
        }
        return $sum / 60;
    }

    public function getProfileImage()
    {
        if ($this->profile_image != null) {
            return asset($this->profile_image);
        } else {
            return asset('img/person.webp');
        }
    }

    public function getDeclarabelHours()
    {
        $workorders = WorkOrder::whereMonth('date', Carbon::today('Europe/Amsterdam')->format('m'))->whereYear('date', Carbon::today('Europe/Amsterdam')->format('Y'))->where('user_id', $this->id)->get();
        $dec = 0;
        $all = 0;
        foreach ($workorders as $workorder) {
            $from = Carbon::parse($workorder->time_from);
            $to = Carbon::parse($workorder->time_to);
            if ($workorder->project && $workorder->project->include_count == 1) {
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

    public function projectsDateMonth($dec, $month, $year)
    {
        if ($dec == true) {
            $workorders = WorkOrder::where('user_id', $this->id)->whereMonth('date', $month)->whereYear('date', $year)->where('status', 'Declarabel')->get()->pluck('project_id')->toArray();
        } else {
            $workorders = WorkOrder::where('user_id', $this->id)->whereMonth('date', $month)->whereYear('date', $year)->where('status', 'Niet Declarabel')->get()->pluck('project_id')->toArray();
        }
        return Project::whereIn('id', $workorders)->get();
    }

    public function workOrdersDateYear($dec, $month, $year, $proj)
    {
        if ($dec == true) {
            return WorkOrder::where('user_id', $this->id)->whereMonth('date', $month)->whereYear('date', $year)->where('status', 'Declarabel')->where('project_id', $proj)->get();
        } else {
            return WorkOrder::where('user_id', $this->id)->whereMonth('date', $month)->whereYear('date', $year)->where('status', 'Niet declarabel')->where('project_id', $proj)->get();
        }
    }

    public function getWorkedHoursByProject($dec, $proj, $month, $year)
    {
        if ($dec == true) {
            $workorders = WorkOrder::where('user_id', $this->id)->whereMonth('date', $month)->whereYear('date', $year)->where('project_id', $proj)->where('status', 'Declarabel')->get();
        } else {
            $workorders = WorkOrder::where('user_id', $this->id)->whereMonth('date', $month)->whereYear('date', $year)->where('project_id', $proj)->where('status', 'Niet declarabel')->get();
        }
        $all = 0;
        foreach ($workorders as $workorder) {
            $from = Carbon::parse($workorder->time_from);
            $to = Carbon::parse($workorder->time_to);
            $all += $to->diffInMinutes($from) / 60;
        }
        return $all;
    }

    public function getAllWorkedHoursByProject($dec, $proj, $month, $year)
    {
        if ($dec == true) {
            $workorders = WorkOrder::whereMonth('date', $month)->whereYear('date', $year)->where('project_id', $proj)->where('status', 'Declarabel')->get();
        } else {
            $workorders = WorkOrder::whereMonth('date', $month)->whereYear('date', $year)->where('project_id', $proj)->where('status', 'Niet declarabel')->get();
        }
        $all = 0;
        foreach ($workorders as $workorder) {
            $from = Carbon::parse($workorder->time_from);
            $to = Carbon::parse($workorder->time_to);
            $all += $to->diffInMinutes($from) / 60;
        }
        return $all;
    }

    public function getProjectPriceByMonth($dec, $proj, $month, $year) {
        $all = 0;

        foreach (\App\Models\User::all() as $user) {
            if ($dec == true) {
                $workorders = WorkOrder::where('user_id', $user->id)->whereMonth('date', $month)->whereYear('date', $year)->where('project_id', $proj)->where('status', 'Declarabel')->get();
            } else {
                $workorders = WorkOrder::where('user_id', $user->id)->whereMonth('date', $month)->whereYear('date', $year)->where('project_id', $proj)->where('status', 'Niet declarabel')->get();
            }

            $hours = 0;

            foreach ($workorders as $workorder) {
                $from = Carbon::parse($workorder->time_from);
                $to = Carbon::parse($workorder->time_to);
                $hours += $to->diffInMinutes($from) / 60;
            }

            if (Project::find($proj)->set_price < $user->project_cost) {
                $all += $hours * Project::find($proj)->set_price;
            } else {
                $all += $hours * $user->project_cost;
            }
        }

        return $all;
    }

    public function getAllWorkedHoursByProjectByDate($dec, $proj, $date)
    {
        if ($dec == true) {
            $workorders = WorkOrder::where('user_id', '!=', 6)->whereDate('date', '>', $date)->where('project_id', $proj)->where('status', 'Declarabel')->get();
        } else {
            $workorders = WorkOrder::where('user_id', '!=', 6)->whereDate('date', '>', $date)->where('project_id', $proj)->where('status', 'Niet declarabel')->get();
        }
        $all = 0;
        foreach ($workorders as $workorder) {
            $from = Carbon::parse($workorder->time_from);
            $to = Carbon::parse($workorder->time_to);
            $all += $to->diffInMinutes($from) / 60;
        }
        return $all;
    }

    public function getWorkedHoursByProjectPrice($dec, $proj, $month, $year)
    {
        if ($dec == true) {
            $workorders = WorkOrder::where('user_id', $this->id)->whereMonth('date', $month)->whereYear('date', $year)->where('project_id', $proj)->where('status', 'Declarabel')->get();
        } else {
            $workorders = WorkOrder::where('user_id', $this->id)->whereMonth('date', $month)->whereYear('date', $year)->where('project_id', $proj)->where('status', 'Niet declarabel')->get();
        }
        $all = 0;
        foreach ($workorders as $workorder) {
            $from = Carbon::parse($workorder->time_from);
            $to = Carbon::parse($workorder->time_to);
            $all += $to->diffInMinutes($from) / 60;
        }

        $project = Project::find($proj);

        if ($project->set_price < floatval($all * \App\Models\User::where('id', $this->id)->first()->project_cost)) {
            $price = floatval($all * $project->set_price);
        } else {
            $price = floatval($all * \App\Models\User::where('id', $this->id)->first()->project_cost);
        }

        return $price;
    }

    public function totalWorkedHoursThisMonthAndYear($dec, $month, $year)
    {
        if ($dec == true) {
            $workorders = WorkOrder::where('user_id', $this->id)->whereMonth('date', $month)->whereYear('date', $year)->where('status', 'Declarabel')->get();
        } else {
            $workorders = WorkOrder::where('user_id', $this->id)->whereMonth('date', $month)->whereYear('date', $year)->where('status', 'Niet declarabel')->get();
        }
        $all = 0;
        foreach ($workorders as $workorder) {
            $from = Carbon::parse($workorder->time_from);
            $to = Carbon::parse($workorder->time_to);
            $all += $to->diffInMinutes($from) / 60;
        }
        return $all;
    }

    public function totalMoneyThisMonthAndYear($dec, $month, $year)
    {
        if ($dec == true) {
            $workorders = WorkOrder::where('user_id', $this->id)->whereMonth('date', $month)->whereYear('date', $year)->where('status', 'Declarabel')->get();
        } else {
            $workorders = WorkOrder::where('user_id', $this->id)->whereMonth('date', $month)->whereYear('date', $year)->where('status', 'Niet declarabel')->get();
        }

        $all = 0;
        $price = floatval(0);
        foreach ($workorders as $workorder) {
            $from = Carbon::parse($workorder->time_from);
            $to = Carbon::parse($workorder->time_to);
            $all += $to->diffInMinutes($from) / 60;

            $project = Project::find($workorder->project_id);
            if ($this->id == 6) {
                $price += floatval(($to->diffInMinutes($from) / 60) * 4.5);
            } else {
                $price += floatval(($to->diffInMinutes($from) / 60) * $project->set_price);
            }
        }

        return $price;
    }

    public function totalUserMoneyThisMonthAndYear($dec, $month, $year, $user)
    {
        if ($dec == true) {
            $workorders = WorkOrder::where('user_id', $this->id)->whereMonth('date', $month)->whereYear('date', $year)->where('status', 'Declarabel')->get();
        } else {
            $workorders = WorkOrder::where('user_id', $this->id)->whereMonth('date', $month)->whereYear('date', $year)->where('status', 'Niet declarabel')->get();
        }

        $all = 0;
        $price = floatval(0);
        foreach ($workorders as $workorder) {
            $from = Carbon::parse($workorder->time_from);
            $to = Carbon::parse($workorder->time_to);
            $all += $to->diffInMinutes($from) / 60;

            if (Project::find($workorder->project_id)->set_price < \App\Models\User::where('id', $user)->first()->project_cost) {
                $price += floatval(($to->diffInMinutes($from) / 60) * Project::find($workorder->project_id)->set_price);
            } else {
                $price += floatval(($to->diffInMinutes($from) / 60) * \App\Models\User::where('id', $user)->first()->project_cost);
            }
        }

        return $price;
    }

    public function countProjects()
    {
        return Project::where('user_id', $this->id)->count();
    }

    public static function translateMonth($month) {
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

        return $m;
    }

    public function getNiceDate($date, $format = 1)
    {
        $day = date('d', strtotime($date));
        $month = date('m', strtotime($date));
        $year = date('Y', strtotime($date));

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

    public function getPerformanceByYearAndMonth($month, $year)
    {
        $workorders = WorkOrder::whereMonth('date', $month)->whereYear('date', $year)->where('user_id', $this->id)->get();
        $dec = 0;
        $all = 0;
        foreach ($workorders as $workorder) {
            $from = Carbon::parse($workorder->time_from);
            $to = Carbon::parse($workorder->time_to);

            if (!$workorder->project) {
                $all += $to->diffInMinutes($from) / 60;
                if ($workorder->status == 'Declarabel') {
                    $dec += $to->diffInMinutes($from) / 60;
                }
            } else if ($workorder->project && $workorder->project->include_count) {
                $all += $to->diffInMinutes($from) / 60;
                if ($workorder->status == 'Declarabel') {
                    $dec += $to->diffInMinutes($from) / 60;
                }
            }

        }

        if ($all <= 0 || $dec <= 0) {
            return 0;
        } else {
            return ($dec / $all) * 100;
        }
    }

    public function getIncomeByYearAndMonth($month, $year)
    {
        $workorders = WorkOrder::whereMonth('date', $month)->whereYear('date', $year)->where('user_id', $this->id)->where('status', 'Declarabel')->get();
        $dec = 0;
        $all = 0;
        $price = floatval(0.00);
        foreach ($workorders as $workorder) {
            $from = Carbon::parse($workorder->time_from);
            $to = Carbon::parse($workorder->time_to);
            $all += $to->diffInMinutes($from) / 60;
            $price += floatval($workorder->project->set_price * ($to->diffInMinutes($from) / 60));

            if ($workorder->status == 'Declarabel') {
                $dec += $to->diffInMinutes($from) / 60;
            }
        }

        return $price;
    }

    public function countActivitiesByMonthAndYear($month, $year)
    {
        return Activity::where('causer_type', 'App\Models\User')->where('causer_id', $this->id)->whereMonth('created_at', $month)->whereYear('created_at', $year)->count();
    }

    public function getCostsByMonthAndYear($month, $year)
    {
        $workorders = WorkOrder::where('user_id', $this->id)->whereMonth('date', $month)->whereYear('date', $year)->get();
        $all = 0;
        foreach ($workorders as $workorder) {
            $from = Carbon::parse($workorder->time_from);
            $to = Carbon::parse($workorder->time_to);
            $all += $to->diffInMinutes($from) / 60;
        }

        return $all * $this->hourly_costs;
    }
}
