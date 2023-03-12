<div id="replaceHoursList">

    @php

        $business_days_count = 0;
        $eenmaligbedrag = 0;

        $month = \Carbon\Carbon::today('Europe/Amsterdam')->format('m');
        $year = \Carbon\Carbon::today('Europe/Amsterdam')->format('Y');
        $start = \Carbon\Carbon::now('Europe/Amsterdam')->startOfMonth()->format('Y-m-d');

        $from = \Carbon\Carbon::now('Europe/Amsterdam')->format('Y-m-d');
        $to = \Carbon\Carbon::today('Europe/Amsterdam')->endOfMonth()->format('Y-m-d');
        $period = \Carbon\CarbonPeriod::create($from, $to);

        foreach ($period as $day) {
            if ($day->isWeekday()) {
                $business_days_count++;
            }
        }

        $user = \App\Models\User::find($chosenUser->id);
        $money = $user->totalUserMoneyThisMonthAndYear(true, $month, $year, $chosenUser->id);

        $eenmaligebedragen = \Illuminate\Support\Facades\DB::table('eenmalige_bedragen')
                           ->where('user_id', '=', $chosenUser->id)
                           ->whereBetween('datum', [$start, $to])
                           ->get();

        foreach ($eenmaligebedragen as $bedrag) {
            $eenmaligbedrag += $bedrag->prijs;
        }

        $target1 = $user->min_income - $money;
        $target2 = $target1 - $eenmaligbedrag;
        $target_vandaag = $target2 / $business_days_count;

        $totaal = $money + $eenmaligbedrag;

    @endphp

    <div class="row">

        <div class="col s12">

            <br>

            <table>

                <tr class="bg-dark white-text">

                    <td class="right pr10">

                        <a onclick="changeDate('prev')" class="btn-floating white clickable hoverable">
                            <i class="material-icons black-text">arrow_back</i>
                        </a>

                    </td>

                    <td class="center width100">

                        <input type="date" onchange="changeDate('center')" value="{{ $chosenDate }}" id="hoursInput">

                    </td>

                    <td class="left pl10">

                        <a onclick="changeDate('next')" class="btn-floating white clickable hoverable">
                            <i class="material-icons black-text">arrow_forward</i>
                        </a>

                    </td>

                    <td></td>

                </tr>

            </table>

            <table class="striped styledTable">

                @if($chosenUser->getWorkOrdersByDate($chosenDate)->count() > 0)

                    <tbody>

                    @foreach($chosenUser->getWorkOrdersByDate($chosenDate)->sortBy('time_from') as $workorder)

                        <tr class="clickable hoverable" id="hour{{$workorder->id}}"
                            onclick="openWorkOrderModal('{{ $workorder->id }}')">

                            <td>{{ ($workorder->project->customer ? $workorder->project->customer->company_name . " | ": "") . $workorder->project->title }}</td>

                            <td>
                                <b>{{ date('H:i', strtotime($workorder->time_from)) . ' tot ' . date('H:i', strtotime($workorder->time_to)) }}</b>
                            </td>

                            <td></td>

                            <td class="center-align">{{ $workorder->getTotalTime() }}</td>

                            <td>
                                <i class="material-icons {{ $workorder->getStatusColor() }}">{{ $workorder->getStatus() }}</i>
                            </td>

                        </tr>

                    @endforeach

                    </tbody>

                @else

                    <tbody>

                    <tr>
                        <td class="center" colspan="99">Geen ingevulde uren...</td>
                    </tr>

                    </tbody>

                @endif

                <tbody>

                <tr class="bg-dark white-text">

                    @if(!isset($user->min_income))

                        <td></td>

                    @elseif($target_vandaag > 0)

                        <td>
                            Je moet
                            @if($target_vandaag > 300)
                                <b class="red-text">&euro;{{ number_format($target_vandaag, 2, ',', '.') }}</b>
                            @else
                                <b>&euro;{{ number_format($target_vandaag, 2, ',', '.') }}</b>
                            @endif
                            per dag halen
                            wil je je target bereiken.
                        </td>
                        <td>(&euro;{{ number_format($totaal, 2, ',', '.') }} /
                            &euro;{{ number_format($user->min_income, 2, ',', '.') }})
                        </td>

                    @else

                        <td>Je hebt je target bereikt gefeliciteerd!</td>
                        <td>(&euro;{{ number_format($totaal, 2, ',', '.') }} /
                            &euro;{{ number_format($user->min_income, 2, ',', '.') }})
                        </td>

                    @endif

                    <td></td>
                    <td>
                    <td><b>{{ $chosenUser->getWorkedHoursByDate($chosenDate) }}</b></td>
                    </td>

                    @if($chosenUser->getWorkedHoursByDate($chosenDate) != 0)

                </tr>

                </tbody>

                @endif

            </table>

        </div>

        <div class="col s12">

            <br>

            <div class="card-title left" id="hoursListTitle"><i class="bi bi-list"></i> &nbsp;&nbsp;Uren overzicht</div>

            <div class="title right">

                @if(\App\Models\WorkOrder::getDeclarabelTeamHours() >= 80)

                    <i class="material-icons fireworks">celebration</i>

                @endif

                {{ number_format(\App\Models\WorkOrder::getDeclarabelTeamHours(), 0) }}
                % <a href="{{ url('/uren/overzicht') }}" class="btn-floating orange">
                    <i class="material-icons">launch</i>
                </a>

            </div>

        </div>

        <div class="col s12">

            <br>

            <div class="progress">
                <div class="determinate" style="width:{{ \App\Models\WorkOrder::getDeclarabelTeamHours() }}%"></div>
            </div>

            <table class="striped">

                <tbody>

                @foreach(\App\Models\User::all() as $user)
                    <tr class="clickable hoverable"
                        onclick="window.location.href='{{ url('/gebruikers/bekijken/'.$user->id) }}'">
                        <td><img class="circle userImage" src="{{ $user->getProfileImage() }}"></td>
                        <td>{{ $user->name }}</td>
                        <td>{{ number_format($user->getDeclarabelHours(), 0) }}%</td>
                    </tr>
                @endforeach

                </tbody>

            </table>

        </div>

    </div>

</div>
