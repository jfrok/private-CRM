<table class="striped">
    <thead class="bg-dark white-text">
    <tr>
        <td>Factureren</td>
        <td>Project naam</td>
        <td>Niet declarabele uren</td>
        <td>Declarabele uren</td>
        <td>&euro; in totaal</td>
    </tr>
    </thead>

    <tbody>

    @foreach(\Illuminate\Support\Facades\DB::table('projects')->where('customer_id', '!=', 170)->get() as $project)

        @if(count(\Illuminate\Support\Facades\DB::table('work_orders')->where('project_id', $project->id)->where('deleted_at', null)->whereMonth('date', $month)->get()) > 0)

            @if(count(\Illuminate\Support\Facades\DB::table('gefactureerd')->where('project_id', $project->id)->whereMonth('date_checked', $month)->get()) > 0)

                <tr class="crossed-text hoverable clickable">
                    <td>
                        <label>
                            <input onclick="uncheckProject({{ $project->id }})" type="checkbox" checked>
                            <span></span>
                        </label>
                    </td>

                    <td>
                        {{ Illuminate\Support\Facades\DB::table('customers')->where('id', $project->customer_id)->first()->company_name }}
                        | {{ $project->title }}
                    </td>
                    <td>
                        {{ $user->getAllWorkedHoursByProject(false, $project->id, $month, Carbon\Carbon::now()->year) }}
                        uur
                    </td>
                    <td>
                        {{ $user->getAllWorkedHoursByProject(true, $project->id, $month, Carbon\Carbon::now()->year) }}
                        uur
                    </td>
                    <td>
                        &euro;{{ number_format($user->getProjectPriceByMonth(true, $project->id, $month, Carbon\Carbon::now()->year), 2, ',', '.') }}
                    </td>
                </tr>

            @endif

            @if(count(\Illuminate\Support\Facades\DB::table('gefactureerd')->where('project_id', $project->id)->orderBy('id', 'DESC')->get()) > 0)

                @php($last_fact_date = \Illuminate\Support\Facades\DB::table('gefactureerd')->where('project_id', $project->id)->orderBy('id', 'DESC')->first()->date_checked)

                @if(count(\Illuminate\Support\Facades\DB::table('gefactureerd')->where('project_id', $project->id)->whereDate('date_checked', '>', $last_fact_date)->orderBy('id', 'DESC')->get()) == 0)

                    @if(count(\Illuminate\Support\Facades\DB::table('work_orders')->where('project_id', $project->id)->where('deleted_at', null)->whereDate('date', '>', $last_fact_date)->get()) > 0)

                        <tr class="hoverable clickable">
                            <td>
                                <label>
                                    <input
                                        onclick="checkProject({{ $project->id }}, '{{ number_format($user->getProjectPriceByMonth(true, $project->id, $last_fact_date, Carbon\Carbon::now()->year), 2, ',', '') }}')"
                                        type="checkbox">
                                    <span></span>
                                </label>
                            </td>

                            <td>
                                {{ Illuminate\Support\Facades\DB::table('customers')->where('id', $project->customer_id)->first()->company_name }}
                                | {{ $project->title }}
                            </td>
                            <td>
                                {{ $user->getAllWorkedHoursByProjectByDate(false, $project->id, $last_fact_date) }}
                                uur
                            </td>
                            <td>
                                {{ $user->getAllWorkedHoursByProjectByDate(true, $project->id, $last_fact_date) }}
                                uur
                            </td>
                            <td>
                                &euro;{{ number_format($user->getProjectPriceByMonth(true, $project->id, $last_fact_date, Carbon\Carbon::now()->year), 2, ',', '.') }}
                            </td>
                        </tr>

                    @endif

                @endif

            @else

                <tr class="hoverable clickable">
                    <td>
                        <label>
                            <input
                                onclick="checkProject({{ $project->id }}, '{{ number_format($user->getProjectPriceByMonth(true, $project->id, Carbon\Carbon::now()->month, Carbon\Carbon::now()->year), 2, ',', '') }}')"
                                type="checkbox">
                            <span></span>
                        </label>
                    </td>

                    <td>
                        {{ Illuminate\Support\Facades\DB::table('customers')->where('id', $project->customer_id)->first()->company_name }}
                        | {{ $project->title }}
                    </td>
                    <td>
                        {{ $user->getAllWorkedHoursByProject(false, $project->id, $month, Carbon\Carbon::now()->year) }}
                        uur
                    </td>
                    <td>
                        {{ $user->getAllWorkedHoursByProject(true, $project->id, $month, Carbon\Carbon::now()->year) }}
                        uur
                    </td>

                    <td>
                        &euro;{{ number_format($user->getProjectPriceByMonth(true, $project->id, $month, Carbon\Carbon::now()->year), 2, ',', '.') }}
                    </td>
                </tr>

            @endif

        @endif

    @endforeach
    </tbody>
</table>
