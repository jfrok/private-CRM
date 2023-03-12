<div id="replaceWorkOrderList">

    @if(\Illuminate\Support\Facades\Cookie::get('workorderTimelineMonth') != null)

        @php($month = \Illuminate\Support\Facades\Cookie::get('workorderTimelineMonth'))

    @else

        @php($month = \Carbon\Carbon::today('Europe/Amsterdam')->format('m'))

    @endif

    @if(\Illuminate\Support\Facades\Cookie::get('workorderTimelineYear') != null)

        @php($year = \Illuminate\Support\Facades\Cookie::get('workorderTimelineYear'))

    @else

        @php($year = \Carbon\Carbon::today('Europe/Amsterdam')->format('Y'))

    @endif

    <div class="col s12">

        <div class="card">

            <div class="card-content">

                <div class="row">

                    <div class="col s12">

                        <div class="card-title left">

                            Overzicht van ingevulde uren

                        </div>

                        <div class="right">

                            <div class="left">

                                <select onchange="changeTimeline()" name="timelineMonth" id="timelineMonth"
                                        class="browser-default">

                                    <option value="1" @if($month == '1') selected @endif>Januari</option>

                                    <option value="2" @if($month == '2') selected @endif>Februari</option>

                                    <option value="3" @if($month == '3') selected @endif>Maart</option>

                                    <option value="4" @if($month == '4') selected @endif>April</option>

                                    <option value="5" @if($month == '5') selected @endif>Mei</option>

                                    <option value="6" @if($month == '6') selected @endif>Juni</option>

                                    <option value="7" @if($month == '7') selected @endif>Juli</option>

                                    <option value="8" @if($month == '8') selected @endif>Augustus</option>

                                    <option value="9" @if($month == '9') selected @endif>September</option>

                                    <option value="10" @if($month == '10') selected @endif>Oktober</option>

                                    <option value="11" @if($month == '11') selected @endif>November</option>

                                    <option value="12" @if($month == '12') selected @endif>December</option>

                                </select>

                            </div>

                            <div class="right">

                                <select onchange="changeTimeline()" name="timelineYear" id="timelineYear"
                                        class="browser-default">
                                    @foreach(range(2020, \Carbon\Carbon::now()->year) as $yearType)
                                        <option value="{{ $yearType }}" @if($year == $yearType) selected @endif>{{ $yearType }}</option>
                                    @endforeach
                                </select>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>


    <div class="col s12">

        <div class="card">

            <div class="card-content">

                <div class="row">

                    <div class="col s12 m6">

                        <div class="row">

                            <div class="col s12">

                                <div class="card-title left">

                                    Team overzicht

                                </div>

                                <div class="title right">

                                    @if(\App\Models\WorkOrder::getDeclarabelTeamHoursByMonthAndYear($month, $year) >= 80)

                                        <i class="material-icons fireworks">celebration</i>

                                    @endif

                                    {{ number_format(\App\Models\WorkOrder::getDeclarabelTeamHoursByMonthAndYear($month, $year), 0) }}
                                    % declarabel team percentage

                                </div>

                            </div>

                            <div class="col s12">

                                <div class="progress fullWidth">

                                    <div class="determinate"
                                         style="width:{{ \App\Models\WorkOrder::getDeclarabelTeamHours() }}%"></div>

                                </div>

                            </div>

                            @php($alle_eenmalige_bedragen = \Illuminate\Support\Facades\DB::table('eenmalige_bedragen')->whereBetween('datum', [\Carbon\Carbon::now('Europe/Amsterdam')->startOfMonth()->format('Y-m-d'), \Carbon\Carbon::today('Europe/Amsterdam')->endOfMonth()->format('Y-m-d')])->get())

                            @php($enmal = 0)

                            @foreach($alle_eenmalige_bedragen as $e)
                                @php($enmal += $e->prijs)
                            @endforeach

                            <div
                                    class="col s12 center targetStyle @if(\App\Models\WorkOrder::getAchievedTargetByMonthAndYear($month, $year) + $enmal < \App\Models\WorkOrder::getCombinedTarget($month, $year)) red-text @else green-text @endif">

                                €{{ number_format(\App\Models\WorkOrder::getAchievedTargetByMonthAndYear($month, $year) + $enmal, 2, ',', '.') }}
                                /
                                €{{ number_format(\App\Models\WorkOrder::getCombinedTarget($month, $year), 2, ',', '.') }}

                            </div>

                        </div>

                    </div>

                    <div class="col s12 m6">

                        <div class="row">

                            <div class="col s12">

                                <canvas id="teamIncomeChart" width="400" height="100"></canvas>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>


    @foreach($users as $user)

        <div class="col s12">

            <div class="card">

                <div class="card-content">

                    <div class="row">

                        <div class="col s12">

                            <table class="striped">

                                <thead class="blue white-text">

                                <tr>

                                    <td style="width: 100px; padding: 20px"><img src="{{ $user->getProfileImage() }}"
                                                                                 class="userImage" alt=""></td>

                                    <td class="userName" colspan="3">{{ $user->name }}</td>

                                    <td class="userName right mr-20">{{ number_format($user->getDeclarabelHours(), 0) }}
                                        %
                                    </td>

                                </tr>

                                <tr class="bg-dark">

                                    <td></td>

                                    <td colspan="99">Declarabele uren</td>

                                </tr>

                                </thead>

                                <tbody>

                                @foreach($user->projectsDateMonth(true, $month, $year) as $project)

                                    <tr class="clickable hoverable"
                                        onclick="$('#projectWorkorderD{{$project->id.'-'.$user->id}}').toggle()">

                                        <td></td>

                                        <td>{{ $project->title }}</td>

                                        <td>{{ $project->customer->company_name }}</td>

                                        <td>{{ $user->getWorkedHoursByProject(true, $project->id, $month, $year) }}
                                            uur
                                        </td>

                                        <td>
                                            €{{ number_format($user->getWorkedHoursByProjectPrice(true, $project->id, $month, $year), 2, ',', '.') }}</td>

                                    </tr>

                                <tbody id="projectWorkorderD{{$project->id.'-'.$user->id}}" class="hidden grey">

                                @foreach($user->workOrdersDateYear(true, $month, $year, $project->id) as $workorder)

                                    <tr onclick="openWorkOrderModal('{{ $workorder->id }}')"
                                        class="hoverable clickable">

                                        <td></td>

                                        <td></td>

                                        <td>{{ $workorder->getNiceDate() }}</td>

                                        <td>{{ $workorder->getTotalTime() . ' uur / ' .  date('H:i', strtotime($workorder->time_from)) }}
                                            - {{ date('H:i', strtotime($workorder->time_to)) }}</td>

                                        <td>€{{ number_format($workorder->getTotalPrice($user->id), 2, ',', '.') }}</td>

                                    </tr>

                                @endforeach

                                </tbody>

                                @endforeach

                                <tr>

                                    <td colspan="3"></td>

                                    <td><b>{{ $user->totalWorkedHoursThisMonthAndYear(true, $month, $year) }}
                                            declarabele uren</b></td>

                                    <td>
                                        <b>&euro;{{ number_format($user->totalUserMoneyThisMonthAndYear(true, $month, $year, $user->id), 2, ',', '.') }}</b>
                                    </td>

                                </tr>

                                </tbody>

                                <thead>

                                <tr class="bg-dark">

                                    <td></td>

                                    <td colspan="99" class="white-text">Niet declarabele uren</td>

                                </tr>

                                </thead>

                                <tbody>

                                @foreach($user->projectsDateMonth(false, $month, $year) as $project)

                                    <tr class="clickable hoverable"
                                        onclick="$('#projectWorkorderND{{$project->id.'-'.$user->id}}').toggle()">

                                        <td></td>

                                        <td>{{ $project->title }}</td>

                                        <td>{{ $project->customer->company_name }}</td>

                                        <td>{{ $user->getWorkedHoursByProject(false, $project->id, $month, $year) }}
                                            uur
                                        </td>

                                        <td>
                                            €{{ number_format($user->getWorkedHoursByProjectPrice(false, $project->id, $month, $year), 2, ',', '.') }}</td>

                                    </tr>

                                <tbody id="projectWorkorderND{{$project->id.'-'.$user->id}}" class="hidden grey">

                                @foreach($user->workOrdersDateYear(false, $month, $year, $project->id) as $workorder)

                                    <tr onclick="openWorkOrderModal('{{ $workorder->id }}')"
                                        class="hoverable clickable">

                                        <td></td>

                                        <td></td>

                                        <td>{{ $workorder->getNiceDate() }}</td>

                                        <td>{{ $workorder->getTotalTime() . ' uur / ' .  date('H:i', strtotime($workorder->time_from)) }}
                                            - {{ date('H:i', strtotime($workorder->time_to)) }}</td>

                                        <td>€{{ number_format($workorder->getTotalPrice($user->id), 2, ',', '.') }}</td>

                                    </tr>

                                @endforeach

                                </tbody>

                                @endforeach

                                <tr>

                                    <td colspan="3"></td>

                                    <td><b>{{ $user->totalWorkedHoursThisMonthAndYear(false, $month, $year) }} niet
                                            declarabele uren</b></td>

                                    <td>
                                        <b>&euro;{{ number_format($user->totalUserMoneyThisMonthAndYear(false, $month, $year, $user->id), 2, ',', '.') }}</b>
                                    </td>

                                </tr>

                                </tbody>

                                <thead>

                                <tr class="bg-dark">

                                    <td></td>

                                    <td colspan="99" class="white-text">Eenmalige bedragen</td>

                                </tr>

                                </thead>

                                <tbody>

                                @php($eenmalige_bedragen = \Illuminate\Support\Facades\DB::table('eenmalige_bedragen')->where('user_id', '=', $user->id)->whereBetween('datum', [\Carbon\Carbon::now('Europe/Amsterdam')->startOfMonth()->format('Y-m-d'), \Carbon\Carbon::today('Europe/Amsterdam')->endOfMonth()->format('Y-m-d')])->get())

                                @php($totaal = 0)

                                @foreach($eenmalige_bedragen as $bedrag)

                                    <tr class="hoverable">

                                        <td></td>

                                        <td>Eenmalig</td>

                                        <td>{{ $bedrag->bedrijfsnaam }}</td>

                                        <td>{{ $bedrag->datum }}</td>

                                        <td>&euro;{{ number_format($bedrag->prijs, 2, ',', '.') }}</td>

                                    </tr>

                                    @php($totaal += $bedrag->prijs)

                                @endforeach

                                <tr>

                                    <td></td>

                                    <td></td>

                                    <td></td>

                                    <td><b>Totaal</b></td>

                                    <td><b>&euro;{{ number_format($totaal, 2, ',', '.') }}</b></td>

                                </tr>

                                </tbody>

                                <thead>

                                <tr class="bg-dark">

                                    <td colspan="3"></td>

                                    <td class="white-text">Alles in totaal</td>

                                    <td colspan="99" class="white-text">
                                        &euro;<b>{{ number_format($user->totalUserMoneyThisMonthAndYear(true, $month, $year, $user->id), 2, ',', '.') }}</b></td>

                                </tr>

                                </thead>

                            </table>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    @endforeach

</div>


<script>

    var ctx = document.getElementById('teamIncomeChart').getContext('2d');

    var myChart = new Chart(ctx, {

        type: 'line',

        data: {

            labels: [

                @for ($i = 1; $i < 13; $i++)

                    '{{ \App\Models\Helper::getMonthByNumber($i) }}',

                @endfor

            ],

            {{--            datasets: [--}}

            {{--                {--}}

            {{--                    label: 'Declarabele uren van het jaar {{ $year }}',--}}

            {{--                    data: [--}}

            {{--                        @for ($i = 1; $i < 13; $i++)--}}

            {{--                            {{ $user->totalWorkedHoursThisMonthAndYear(true, $i, $year) }},--}}

            {{--                        @endfor--}}

            {{--                    ],--}}

            {{--                    backgroundColor: [--}}

            {{--                        @for ($i = 0; $i < 12; $i++)--}}

            {{--                            'green',--}}

            {{--                        @endfor--}}

            {{--                    ],--}}

            {{--                    borderColor: [--}}

            {{--                        @for ($i = 0; $i < 12; $i++)--}}

            {{--                            'green',--}}

            {{--                        @endfor--}}

            {{--                    ],--}}

            {{--                    borderWidth: 3--}}

            {{--                },--}}

            {{--            ]--}}

        },

        options: {

            scales: {

                y: {

                    beginAtZero: true

                }

            }

        }

    });

</script>

