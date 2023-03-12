@if(\Illuminate\Support\Facades\Cookie::get('timelineMonth') != null)
    @php($month = \Illuminate\Support\Facades\Cookie::get('timelineMonth'))
@else
    @php($month = \Carbon\Carbon::today('Europe/Amsterdam')->format('m'))
@endif

@if(\Illuminate\Support\Facades\Cookie::get('timelineYear') != null)
    @php($year = \Illuminate\Support\Facades\Cookie::get('timelineYear'))
@else
    @php($year = \Carbon\Carbon::today('Europe/Amsterdam')->format('Y'))
@endif
<div class="row">
    <div class="col s12">
        <div class="card">
            <div class="card-content">
                <div class="row">
                    <div class="col s12">
                        <div class="title left">
                            Tijdlijn van dit project
                        </div>
                        <div class="right">
                            <div class="left">
                                <select onchange="changeTimeline()" name="timelineMonth" id="timelineMonth" class="browser-default">
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
                                <select onchange="changeTimeline()" name="timelineYear"  id="timelineYear" class="browser-default">
                                    <option value="2021" @if($year == '2021') selected @endif>2021</option>
                                    <option value="2022" @if($year == '2022') selected @endif>2022</option>
                                    <option value="2023" @if($year == '2023') selected @endif>2023</option>
                                    <option value="2024" @if($year == '2024') selected @endif>2024</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    @php($timeline = false)
                    @foreach(\App\Models\User::all() as $user)
                        @foreach($project->userWorkordersByDate($user->id, $month, $year) as $workorder)
                            @php($timeline = true)
                        @endforeach
                    @endforeach
                    @if($timeline == true)
                        <div class="col s12">
                            <br>
                            <div id="timeline" style="height: 180px;"></div>
                        </div>
                    @else
                        <div class="col s12">
                            <h6><i>Geen uren gevonden in het ingevulde tijdsvak...</i></h6>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-content">
                <div class="row">
                    <div class="col s12">
                        <br>
                        <table class="striped">
                            <thead class="bg-dark white-text">
                                <tr>
                                    <td>Datum</td>
                                    <td>Gebruiker</td>
                                    <td>Status</td>
                                    <td>Ingevulde uren</td>
                                    <td>Opgeteld</td>
                                    <td>Bedrag</td>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($project->workorders->sortByDesc('date') as $workorder)
                                    <tr class="hoverable clickable" onclick="openWorkOrderModal('{{ $workorder->id }}')">
                                        <td>{{ $workorder->getNiceDate()  }}</td>
                                        <td>{{ $workorder->user->name }}</td>
                                        <td><b @if($workorder->status == 'Declarabel') class="green-text" @else class="red-text" @endif>{{ $workorder->status }}</b></td>
                                        <td>{{ date('H:i', strtotime($workorder->time_from)) }} / {{ date('H:i', strtotime($workorder->time_to)) }}</td>
                                        <td>{{ $workorder->getTotalTime() }} uur</td>
                                        <td>€{{ number_format($workorder->getTotalPrice($workorder->user_id), 2, ',', '.') }}</td>
                                    </tr>
                                @endforeach
                                <tr class="green lighten-3">
                                    <td colspan="3"></td>
                                    <td>Declarabel:</td>
                                    <td>{{ $project->getWorkedHours() }} uur</td>
                                    <td>€{{ $project->currentCalculatedPrice() }}</td>
                                </tr>
                                <tr class="red lighten-3">
                                    <td colspan="3"></td>
                                    <td>Niet declarabel:</td>
                                    <td>{{ $project->getNotDeclarabelHours() }} uur</td>
                                    <td>€{{ $project->notDeclarabelPrice() }}</td>
                                </tr>
                                <tr class="bg-dark white-text">
                                    <td colspan="3"></td>
                                    <td>Totaal:</td>
                                    <td>{{ $project->getNotDeclarabelHours() + $project->getWorkedHours() }} uur</td>
                                    <td>€{{ $project->getAllTotalPrice() }} / €{{ number_format($project->set_price*$project->set_hours, 2, ',', '.') }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    console.log(new Date(1789, 3, 30));
    console.log(new Date('{{ date('Y-m-d', strtotime($project->created_at)) }}'));
    google.charts.load('current', {'packages':['timeline']});
    google.charts.setOnLoadCallback(drawChart);
    function drawChart() {
        var container = document.getElementById('timeline');
        var chart = new google.visualization.Timeline(container);
        const dataTable = new google.visualization.DataTable();

        dataTable.addColumn({ type: 'string', id: 'Gebruikers' });
        dataTable.addColumn({ type: 'string', id: 'Label'});
        dataTable.addColumn({ type: 'string', role: 'Tooltip'});
        dataTable.addColumn({ type: 'date', id: 'Start' });
        dataTable.addColumn({ type: 'date', id: 'End' });
        dataTable.addRows([
            @foreach(\App\Models\User::all() as $user)
                @foreach($project->userWorkordersByDate($user->id, $month, $year) as $workorder)
                    [
                        '{{ $user->name }}',
                        '{{ $workorder->getNiceDate() . ' - ' . $workorder->getTotalTime() . ' uur' }}',
                        '{{ $workorder->id }}',
                        new Date('{{ date('Y-m-d', strtotime($workorder->date)) }}T{{ date('H:i:s', strtotime($workorder->time_from)) }}'),
                        new Date('{{ date('Y-m-d', strtotime($workorder->date)) }}T{{ date('H:i:s', strtotime($workorder->time_to)) }}'),
                    ],
                @endforeach
            @endforeach
        ]);

        var options = {
            is3D: true,
            fontName: 'Roboto',
            tooltip: { isHtml: true },
            animation: {"startup": true},
            colors:[
                @foreach(\App\Models\User::all() as $user)
                    @foreach($project->userWorkordersByDate($user->id, $month, $year) as $workorder)
                        @if($workorder->status == 'Declarabel')
                            'green',
                        @elseif($workorder->status == 'Niet declarabel')
                            'red',
                        @else
                            'grey',
                        @endif
                    @endforeach
                @endforeach
            ]
        };

        chart.draw(dataTable, options);

        google.visualization.events.addListener(chart, 'select', function() {
            setSelection(chart.getSelection());
        });

        function setSelection(e) {
            for (var i = 0; i < e.length; i++) {
                var item = e[i];
                var id = dataTable.getFormattedValue(item.row, 2);
            }
            openWorkOrderModal(id);
        }
    }
</script>
