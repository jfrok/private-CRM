@if(\Illuminate\Support\Facades\Cookie::get('userPerformanceMonth') != null)
    @php($month = \Illuminate\Support\Facades\Cookie::get('userPerformanceMonth'))
@else
    @php($month = \Carbon\Carbon::today('Europe/Amsterdam')->format('m'))
@endif

@if(\Illuminate\Support\Facades\Cookie::get('userPerformanceYear') != null)
    @php($year = \Illuminate\Support\Facades\Cookie::get('userPerformanceYear'))
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
                            Prestaties
                        </div>
                        <div class="right">
{{--                            <div class="left">--}}
{{--                                <select onchange="changeTimeline()" name="timelineMonth" id="timelineMonth" class="browser-default">--}}
{{--                                    <option value="1" @if($month == '1') selected @endif>Januari</option>--}}
{{--                                    <option value="2" @if($month == '2') selected @endif>Februari</option>--}}
{{--                                    <option value="3" @if($month == '3') selected @endif>Maart</option>--}}
{{--                                    <option value="4" @if($month == '4') selected @endif>April</option>--}}
{{--                                    <option value="5" @if($month == '5') selected @endif>Mei</option>--}}
{{--                                    <option value="6" @if($month == '6') selected @endif>Juni</option>--}}
{{--                                    <option value="7" @if($month == '7') selected @endif>Juli</option>--}}
{{--                                    <option value="8" @if($month == '8') selected @endif>Augustus</option>--}}
{{--                                    <option value="9" @if($month == '9') selected @endif>September</option>--}}
{{--                                    <option value="10" @if($month == '10') selected @endif>Oktober</option>--}}
{{--                                    <option value="11" @if($month == '11') selected @endif>November</option>--}}
{{--                                    <option value="12" @if($month == '12') selected @endif>December</option>--}}
{{--                                </select>--}}
{{--                            </div>--}}
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
                    <div class="col s12 m6">
                        <canvas id="declYearPerformance" width="400" height="150"></canvas>
                    </div>
                    <div class="col s12 m6">
                        <canvas id="incomePerformanceChart" width="400" height="150"></canvas>
                    </div>
                    <div class="col s12 m6">
                        <canvas id="workedHoursChart" width="400" height="150"></canvas>
                    </div>
                    <div class="col s12 m6">
                        <canvas id="totalCostsChart" width="400" height="150"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    var ctx = document.getElementById('declYearPerformance').getContext('2d');
    var myChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: [
                @for ($i = 1; $i < 13; $i++)
                    '{{ \App\Models\Helper::getMonthByNumber($i) }}',
                @endfor
            ],
            datasets: [{
                label: 'Declarabele percentages van het jaar {{ $year }} in %',
                data: [
                    @for ($i = 1; $i <= 12; $i++)
                        {{ number_format($user->getPerformanceByYearAndMonth($i, $year), 0) }},
                    @endfor
                ],
                backgroundColor: [
                    @for ($i = 0; $i < 12; $i++)
                        'rgb(38, 0, 137, 0.2)',
                    @endfor
                ],
                borderColor: [
                    @for ($i = 0; $i < 12; $i++)
                        'rgb(250, 77, 9, 1)',
                    @endfor
                ],
                borderWidth: 3
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    var ctx = document.getElementById('incomePerformanceChart').getContext('2d');
    var myChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: [
                @for ($i = 1; $i < 13; $i++)
                    '{{ \App\Models\Helper::getMonthByNumber($i) }}',
                @endfor
            ],
            datasets: [{
                label: 'Inkomen van het jaar {{ $year }} in €',
                data: [
                    @for ($i = 1; $i < 13; $i++)
                        {{ $user->getIncomeByYearAndMonth($i, $year) }},
                    @endfor
                ],
                backgroundColor: [
                    @for ($i = 0; $i < 12; $i++)
                        'rgb(250, 77, 9, 0.2)',
                    @endfor
                ],
                borderColor: [
                    @for ($i = 0; $i < 12; $i++)
                        'rgb(38, 0, 137, 1)',
                    @endfor
                ],
                borderWidth: 3
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    var ctx = document.getElementById('workedHoursChart').getContext('2d');
    var myChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: [
                @for ($i = 1; $i < 13; $i++)
                    '{{ \App\Models\Helper::getMonthByNumber($i) }}',
                @endfor
            ],
            datasets: [
                {
                    label: 'Declarabele uren van het jaar {{ $year }}',
                    data: [
                        @for ($i = 1; $i < 13; $i++)
                            {{ $user->totalWorkedHoursThisMonthAndYear(true, $i, $year) }},
                        @endfor
                    ],
                    backgroundColor: [
                        @for ($i = 0; $i < 12; $i++)
                            'green',
                        @endfor
                    ],
                    borderColor: [
                        @for ($i = 0; $i < 12; $i++)
                            'green',
                        @endfor
                    ],
                    borderWidth: 3
                },

                {
                    label: 'Niet declarabele uren van het jaar {{ $year }}',
                    data: [
                        @for ($i = 1; $i < 13; $i++)
                            {{ $user->totalWorkedHoursThisMonthAndYear(false, $i, $year) }},
                        @endfor
                    ],
                    backgroundColor: [
                        @for ($i = 0; $i < 12; $i++)
                            'red',
                        @endfor
                    ],
                    borderColor: [
                        @for ($i = 0; $i < 12; $i++)
                            'red',
                        @endfor
                    ],
                    borderWidth: 3
                }
            ]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    var ctx = document.getElementById('totalCostsChart').getContext('2d');
    var myChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: [
                @for ($i = 1; $i < 13; $i++)
                    '{{ \App\Models\Helper::getMonthByNumber($i) }}',
                @endfor
            ],
            datasets: [
                {
                    label: 'Kosten van het jaar {{ $year }} in €',
                    data: [
                        @for ($i = 1; $i < 13; $i++)
                            {{ $user->getCostsByMonthAndYear($i, $year) }},
                        @endfor
                    ],
                    backgroundColor: [
                        @for ($i = 0; $i < 12; $i++)
                            'rgb(250, 77, 9, 0)',
                        @endfor
                    ],
                    borderColor: [
                        @for ($i = 0; $i < 12; $i++)
                            'rgb(250, 77, 9, 1)',
                        @endfor
                    ],
                    borderWidth: 3
                },
                {
                    label: 'Inkomen van het jaar {{ $year }} in €',
                    data: [
                        @for ($i = 1; $i < 13; $i++)
                        {{ $user->getIncomeByYearAndMonth($i, $year) }},
                        @endfor
                    ],
                    backgroundColor: [
                        @for ($i = 0; $i < 12; $i++)
                            'rgb(250, 77, 9, 0)',
                        @endfor
                    ],
                    borderColor: [
                        @for ($i = 0; $i < 12; $i++)
                            'rgb(38, 0, 137, 1)',
                        @endfor
                    ],
                    borderWidth: 3
                }
            ],
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
