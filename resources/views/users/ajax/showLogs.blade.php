<div class="row">
    <div class="col s12">
        <div class="card">
            <div class="card-content">
                <div class="row">
                    <div class="col s12">
                        <div class="title left">
                            Logboek van {{ $user->name }}
                        </div>
                    </div>
                    <div class="col s12">
                        <br>
                        <table class="striped">
                            <thead class="bg-dark white-text">
                                <tr>
                                    <td>#</td>
                                    <td>Actie</td>
                                    <td>Betreft</td>
                                    <td>Datum</td>
                                </tr>
                            </thead>
                            <tbody>
                                @if($user->activities()->count() < 1)
                                    <tr>
                                        <td colspan="99" class="center">Geen logboek gegevens van {{ $user->name }}...</td>
                                    </tr>
                                @else
                                    @foreach($user->activities() as $activity)
                                        <tr>
                                            <td>{{ $activity->id }}</td>
                                            <td><b>{{ $activity->description }}</b></td>
                                            <td>{{ $activity->subject_type }}\{{ $activity->subject_id }}</td>
                                            <td>{{ date('d-m-Y', strtotime($activity->created_at)) . ' / ' . date('H:i', strtotime($activity->created_at))  }}</td>
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
