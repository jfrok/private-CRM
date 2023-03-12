@if(count(Illuminate\Support\Facades\DB::table('notes')->where('user_id', '=', $chosenUser->id)->get()) > 0)
    @foreach(Illuminate\Support\Facades\DB::table('notes')->where('user_id', '=', $chosenUser->id)->orderBy('id', 'DESC')->get() as $note)
        <br>
        <p>
            <small class="chip white-text"
                   style="background-color: {{ \App\Models\User::find($note->user_id)->color }} !important">
                <a class="white-text" href="{{ url('/gebruikers/bekijken') }}/{{ $note->user_id }}">
                    {{ Illuminate\Support\Facades\DB::table('customers')->where('id', '=', Illuminate\Support\Facades\DB::table('projects')->where('id', '=', $note->project_id)->first()->customer_id)->first()->company_name }}
                    | {{ Illuminate\Support\Facades\DB::table('projects')->where('id', '=', $note->project_id)->first()->title }}
                    - {{ $note->date_added }}
                </a>
            </small>

            <b>{{ $note->title }}</b>

            <a onclick="deleteNote({{ $note->id }})"
               class="hide-on-small-only modal-trigger btn waves-effect waves-dark bg-light black-text right">
                <i class="bi bi-trash"></i>
            </a>

            <a onclick="fillEditForm({{ $note->id }}, '{{ $note->title }}', {{ $note->user_id }}, {{ $note->project_id }}, '{!! $note->body !!}')"
               href="#editNoteModal"
               class="hide-on-small-only modal-trigger btn waves-effect waves-dark bg-light black-text right">
                <i class="bi bi-pencil"></i>
            </a>

            <a onclick="viewNote({{ $note->id }})"
               class="hide-on-small-only modal-trigger btn waves-effect waves-dark bg-light black-text right">
                <i class="bi bi-sticky"></i>
            </a>
        </p>
    @endforeach
@else
    <div class="center">
        <b>Geen notities voor deze gebruiker...</b>
    </div>
@endif

