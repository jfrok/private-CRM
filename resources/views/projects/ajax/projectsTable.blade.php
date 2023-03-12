<div id="replaceProjectsTable">
    <table class="striped">
        <thead class="bg-dark white-text">
        <tr>
            <td>#</td>
            <td>Project naam</td>
            <td>Klant</td>
            <td>Projectleider</td>
            <td>Status</td>
            <td>Afgesproken uren</td>
            <td>Uurprijs</td>
            <td>Totaalprijs</td>
            <td>Aangemaakt op</td>
        </tr>
        </thead>
        <tbody>
        @if($projects->count() < 1)
            <tr>
                <td colspan="99" class="center">Geen projecten gevonden...</td>
            </tr>
        @else
            @foreach($projects as $project)
                <tr onclick="window.location.href='/projecten/bekijken/{{$project->id}}'" class="clickable hoverable">
                    <td>#{{ $project->id }}</td>
                    <td>{{ $project->title }}</td>
                    <td>{{ $project->customer->company_name }}</td>
                    <td>{{ ($project->user ? $project->user->name: "-") }}</td>
                    <td><b>{{ $project->status }}</b></td>
                    @if($project->set_hours == null)
                        <td>{{ $project->getWorkedHours() }} / - uur</td>
                    @else
                        <td>{{ $project->getWorkedHours() }} / {{ $project->set_hours }} uur</td>
                    @endif
                    <td>€{{ number_format($project->set_price, 2, ',', '.') }}</td>
                    <td>€{{ $project->currentCalculatedPrice() }} / {{ $project->calculatedPrice() }}</td>
                    <td>{{ $project->getNiceDate() }}</td>
                </tr>
            @endforeach
        @endif
        </tbody>
    </table>
</div>
