<div class="row">
    <div class="col s12 m4">
        <div class="card">
            <div class="card-content">
                <div class="row">
                    <div class="col s12">
                        <div class="title left">
                            Project
                        </div>
                        <div>
                            {{--                    {{dd($user->project->cost[1])}}--}}
                            <canvas id="myChart"></canvas>
                        </div>
                    </div>
                    <div class="col s12">
                        <br>
                        <table class="striped">
                            <thead>
                            <tr>
                                <td colspan="99" class="bg-dark white-text">Project gegevens</td>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td>Projectnaam</td>
                                <td><b>{{ $project->title }}</b></td>
                            </tr>
                            <tr>
                                <td>Klant</td>
                                <td><a class="tableLink"
                                       href="{{ url('/klanten/bekijken/'.$project->customer_id) }}"><b>{{ $project->customer->company_name }}</b></a>
                                </td>
                            </tr>
                            <tr>
                                <td>Status</td>
                                <td><b>{{ $project->status }}</b></td>
                            </tr>
                            <tr>
                                <td>Afgesproken uren</td>
                                @if($project->set_hours != null)
                                <td><b>{{ $project->set_hours }}</b></td>
                                @else
                                <td><b>-</b></td>
                                @endif
                            </tr>
                            <tr>
                                <td>Afgesproken prijs</td>
                                @if($project->set_hours != null)
                                <td><b>&euro; {{ $project->currentCalculatedPrice() }} /
                                        &euro; {{ $project->calculatedPrice() }}</b></td>
                                @else
                                <td><b>&euro; {{ $project->setPrice() }}</b></td>
                                @endif
                            </tr>
                            <tr>
                                <td>Aangemaakt op</td>
                                <td><b>{{ $project->getNiceDate() }}</b></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                {{--                Chart.line--}}


                <div class="row">
                    <div class="col s2">
                        <a class="btn orange white-text dropdown-trigger" data-target='statusDropdown'><i
                                    class="material-icons">arrow_drop_down</i></a>
                    </div>
                    <div class="col s10">
                        <a onclick="submitProjectForm()" class="btn fullWidth blue white-text">Wijzigingen
                            opslaan</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <ul id='statusDropdown' class='dropdown-content'>
        <li><a onclick="changeStatus('Open')"><i class="material-icons">send</i> Open</a></li>
        <li><a onclick="changeStatus('Afgerond')"><i class="material-icons">done</i> Afgerond</a></li>
        <li><a onclick="changeStatus('Verwijderd')"><i class="material-icons">delete</i> Verwijderd</a></li>
    </ul>

    <div class="col s12 m4">
        <form id="editProjectForm">
            @csrf
            <div class="card">
                <div class="card-content">
                    <div class="row">
                        <div class="col s12">
                            <div class="title left">
                                Informatie
                            </div>
                        </div>
                        <div class="col s12">
                            <br>
                            <label for="customer_id">Selecteer een klant</label>
                            <select name="customer_id" id="customer_id" class="browser-default" required>
                                <option value="" selected disabled>Selecteer een klant...</option>
                                @foreach(\App\Models\Customer::all() as $customer)
                                <option @if($customer->id == $project->customer_id) selected
                                    @endif value="{{ $customer->id }}">{{ $customer->company_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col s12">
                            <label for="user_id">Selecteer een projectleider</label>
                            <select name="user_id" id="user_id" required>
                                <option value="" selected disabled>Selecteer een projectleider...</option>
                                @foreach(\App\Models\User::all() as $user)
                                <option @if($user->id == $project->user_id) selected
                                    @endif value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col s12">
                            <label for="title">Projectnaam</label>
                            <input type="text" name="title" id="title" value="{{ $project->title }}" required>
                        </div>
                        <div class="col s12 m6">
                            <label for="set_price">Prijs per uur</label>
                            <input type="number" step="any" name="set_price" id="set_price"
                                   value="{{ $project->set_price }}" required>
                        </div>
                        <div class="col s12 m6">
                            <label for="set_price">Afgesproken uren</label>
                            <input type="number" step="any" name="set_hours" id="set_hours"
                                   value="{{ $project->set_hours }}" required>
                        </div>
                        <div class="col s12">
                            <label for="description">Omschrijving</label>
                            <textarea name="descriptionHolder"
                                      id="descriptionHolder">{!! $project->description !!}</textarea>
                            <textarea name="description" id="description" hidden></textarea>
                        </div>
                        <div class="col s12">
                            <br>
                            <p>
                                <label>
                                    <input type="checkbox" class="filled-in" name="include_count"
                                           @if($project->include_count == true) checked="checked" @endif />
                                    <span>Project mee rekenen</span>
                                </label>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <div class="col s12 m4">
        <div class="card">
            <div class="card-content">
                <div class="row">
                    <div class="col s12">
                        <div class="title left">
                            Logboek
                        </div>
                    </div>
                    <div class="col s12">
                        <br>
                        <table class="striped">
                            <thead class="bg-dark white-text">
                            <tr>
                                <td>Actie</td>
                                <td>Wanneer</td>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($project->last10Activities() as $act)
                            <tr>
                                <td>{{ $act->description }}</td>
                                <td>{{ date('H:i', strtotime($act->created_at)) . ', ' . date('d-m-Y', strtotime($act->created_at)) }}</td>
                            </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-content">
                <div>
                    <div class="title left">
                        Notities
                    </div>
                    <p>&nbsp;</p>
                    <p>&nbsp;</p>
                    @if(count(Illuminate\Support\Facades\DB::table('notes')->where('project_id', '=', $project->id)->get()) > 0)
                    @foreach(Illuminate\Support\Facades\DB::table('notes')->where('project_id', '=', $project->id)->orderBy('id', 'DESC')->get() as $note)
                    <br>
                    <p>
                        <small class="chip white-text"
                               style="background-color: {{ \App\Models\User::find($note->user_id)->color }} !important">
                            <a class="white-text"
                               href="{{ url('/gebruikers/bekijken') }}/{{ $note->user_id }}">Voor
                                - {{ \App\Models\User::find($note->user_id )->name }}</a>
                        </small>

                        {{ $note->title }}
                    </p>
                    @endforeach
                    @else
                    <div class="center">
                        <b>Geen notities voor dit project...</b>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@if(isset($chartData))
<script>
    const ctx = document.getElementById('myChart');

    new Chart(ctx, {
        type: 'bar',

        data: {

            labels: ['GWP', 'AFG(Prijs)',],
            datasets: [{
                label: '# to Totall Cost',
                data: [ {{ $chartData['WorkedHours'] }}, {{ $chartData['AgreedHours'] }}],
    //backgroundColor: black,
    borderWidth: 1,
        borderColor: '#FF6384',
        backgroundColor: '#fc5e4f'
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
@endif
