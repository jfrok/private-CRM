<div class="row">

    <div class="col s12 m4">
        <div class="card">
            <div class="card-content">
                <div class="row">
                    <div class="col s12">
                        <div class="title left">
                            Afspraken
                        </div>
                    </div>
                    <div class="col s12">
                        <br>
                        <table class="striped">
                            <thead>
                            <tr>
                                <td colspan="99" class="bg-dark white-text">Afspraken</td>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td>Afgesproken uren</td>
                                @if($project->set_hours != null)
                                    @if($project->getWorkedHours() > $project->set_hours)
                                        <td class="red-text">
                                    @else
                                        <td class="green-text">
                                    @endif
                                        <b>
                                            {{ $project->getWorkedHours() }} / {{ $project->set_hours }} uur
                                        </b>
                                    </td>
                                @else
                                    <td><b>-</b></td>
                                @endif
                            </tr>
                            <tr>
                                <td>Afgesproken prijs</td>
                                @if($project->set_hours != null)
                                    <td><b>&euro; {{ $project->currentCalculatedPrice() }} / {{ $project->calculatedPrice() }}</b></td>
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
            </div>
        </div>
    </div>

    <div class="col s12 m8">
        <div class="card">
            <div class="card-content">
                <div class="row">
                    <div class="col s12">
                        <div class="title left">
                            Gemaakte uren
                        </div>
                    </div>
                    <div class="col s12">
                        <br>
                        <table class="striped">
                            <thead>
                            <tr>
                                <td colspan="99" class="bg-dark white-text">Gemaakte uren</td>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($project->workorders->sortByDesc('date') as $workorder)
                                <tr class="hoverable clickable" onclick="openWorkOrderModal('{{ $workorder->id }}')">
                                    <td>{{ $workorder->getNiceDate() }}</td>
                                    <td><b @if($workorder->status == 'Declarabel') class="green-text" @else class="red-text" @endif>{{ $workorder->status }}</b></td>
                                    <td>{{ date('H:i', strtotime($workorder->time_from)) }} / {{ date('H:i', strtotime($workorder->time_to)) }}</td>
                                    <td>{{ $workorder->getTotalTime() }} uur</td>
                                </tr>
                            @endforeach
                            <tr>
                                <td colspan="99" class="green lighten-3 white-text">
                                &euro; in totaal &euro; {{ $project->currentCalculatedPrice() }}</b></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
