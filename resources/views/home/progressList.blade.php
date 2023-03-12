@foreach(App\Models\User::all() as $user)
    <div class="col s12 m6">
        <div class="card">
            <div class="card-content">
                <div>
                    <h5>
                        <b>
                            {{ $user->name }}
                        </b>
                    </h5>
                </div>

                <br>

                <div>
                    @if(App\Models\Project::getUserRemainingHours($user->id) > 0 && $user->hours_a_dag >0)
                        <label>Vooruitgang
                            <b class="right">{{ App\Models\Project::getUserRemainingHours($user->id) }} uur resterend ({{ App\Models\Project::getUserDoneDate($user->id, App\Models\Project::getUserRemainingHours($user->id))->format('d M Y') }})</b>
                        </label>
                    @else
                        <label>Vooruitgang <b class="right">Klaar <i class="bi bi-check2"></i></b></label>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endforeach
