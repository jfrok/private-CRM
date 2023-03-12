<div>
    <div><p>&nbsp;</p></div>

    <div class="wiki">
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
        </p>
        <br>
        {!! $note->body !!}
    </div>
</div>
