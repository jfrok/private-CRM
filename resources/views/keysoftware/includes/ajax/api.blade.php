<div class="row">
    <div class="col s12">
        <div class="card">
            <div class="card-content">
                <div class="row">
                    <div class="col s12">
                        <div class="title left">
                            Api Calls
                        </div>
                        <div class="right">
                            <div class="right">
                                @inject("carbon", "\Carbon\Carbon")
                                <select onchange="changeTimeline()" name="timelineYear"  id="timelineYear" class="browser-default">
                                    <option value="{{ $carbon->now()->subYear(1)->format('Y') }}" {{ $year ===  $carbon->now()->subYear()->format('Y') ? 'selected' : ''}}>{{ $carbon->now()->subYear()->format('Y') }}</option>
                                    <option value="{{ $carbon->now()->format('Y') }}" {{ $year ===  $carbon->now()->format('Y') ? 'selected' : ''}}>{{ $carbon->now()->format('Y') }}</option>
                                    <option value="{{ $carbon->now()->addYears(1)->format('Y') }}" {{ $year ===  $carbon->now()->addYears(1)->format('Y') ? 'selected' : ''}}>{{ $carbon->now()->addYears(1)->format('Y') }}</option>
                                    <option value="{{ $carbon->now()->addYears(2)->format('Y') }}" {{ $year ===  $carbon->now()->addYears(2)->format('Y') ? 'selected' : ''}}>{{ $carbon->now()->addYears(2)->format('Y') }}</option>
                                    <option value="{{ $carbon->now()->addYears(3)->format('Y') }}" {{ $year ===  $carbon->now()->addYears(3)->format('Y') ? 'selected' : ''}}>{{ $carbon->now()->addYears(3)->format('Y') }}</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col s12">
                        <canvas id="apiCallsPeformanceChart" width="500" height="125"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-content">
                <div class="row">
                    <div class="col s12">
                        <div class="title left">
                            Api Calls Uitgebreid
                        </div>
                    </div>
                    <div class="col s12">
                        <table class="striped">
                            <thead class="secondary white-text">
                            <tr>
                                <td></td>
                                <td>Product</td>
                                <td>Calls</td>
                                <td>Kadasterprijs per call</td>
                                <td>Planviewerprijs per call</td>
                                <td>Totaalprijs excl btw deze maand</td>
                                <td>Totaalprijs incl btw deze maand</td>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($customer->getProductsThisMonth() as $pr)
                                <tr>
                                    <td class="cursor p-5"></td>
                                    <td class="cursor p-5">{{ $pr->name }}</td>
                                    <td>{{ $pr->count }}</td>
                                    <td>€ {{ $pr->getKadasterPrice() }}</td>
                                    <td>€ {{ $pr->getPlanviewerPrice() }}</td>
                                    <td class="cursor p-5">€ {{ number_format($pr->getProductPrice(), 2, ',', '.') }}</td>
                                    <td class="cursor p-5">€ {{ number_format($pr->getProductPriceInclVat(), 2, ',', '.') }}</td>
                                </tr>
                            @endforeach
                                <tr>
                                    <td></td>
                                    <td>Totaalprijs</td>
                                    <td colspan="4">{{ $customer->apiCallsThisMonth() ?? 0 }}</td>
                                    <td>€ {{ $customer->getTotalProductPriceThisMonth() }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    var ctx = document.getElementById('apiCallsPeformanceChart').getContext('2d');
    var myChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: [
                @for ($i = 1; $i < 13; $i++)
                    '{{ \App\Models\Helper::getMonthByNumber($i) }}',
                @endfor
            ],
            datasets: [{
                label: 'Api calls van het jaar {{ $year }} per maand',
                data: [
                    @for ($i = 1; $i < 13; $i++)
                        {{ $customer->getApiCallsByYearAndMonth($i, $year) }},
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
</script>
