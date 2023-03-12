<div class="row">
    <div class="col s12">
        <div class="card">
            <div class="card-content">
                <div class="row">
                    <div class="col s12">
                        <div class="title left">
                            Ingevulde uren van {{ $user->name }}
                        </div>
                    </div>
                    <div class="col s12">
                        <br>
                        <table class="striped">
                            <thead class="bg-dark white-text">
                                <tr>
                                    <td>Project</td>
                                    <td>Klant</td>
                                    <td>Tijd & datum</td>
                                    <td>Uren</td>
                                    <td>Prijs</td>
                                    <td>Status</td>
                                </tr>
                            </thead>
                            <tbody>
                                @if($user->workorders->count() < 1)
                                    <tr>
                                        <td colspan="99" class="center">Nog geen ingevulde uren van {{ $user->name }}...</td>
                                    </tr>
                                @else
                                    @foreach($user->workorders->sortByDesc('id') as $workorder)
                                        <tr class="hoverable clickable" onclick="openWorkOrderModal('{{ $workorder->id }}')">
                                            <td>{{ $workorder->project->title }}</td>
                                            <td>{{ $workorder->project->customer->company_name }}</td>
                                            <td><b>{{ date('H:i', strtotime($workorder->time_from)) . ' - ' . date('H:i', strtotime($workorder->time_to)) . ' / ' . $workorder->getNiceDate() }}</b></td>
                                            <td>{{ $workorder->getTotalTime() . ' uur' }}</td>
                                            <td>€ {{ number_format($workorder->getTotalPrice($workorder->user_id), 2, ',', '.') }}</td>
                                            <td>
                                                @if($workorder->status == 'Declarabel')
                                                    <b class="green-text">{{ $workorder->status }}</b>
                                                @else
                                                    <b class="red-text">{{ $workorder->status }}</b>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
