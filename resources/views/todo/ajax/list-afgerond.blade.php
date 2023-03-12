@foreach($lists as $list)
    <div class="col s6">
        <div class="card">
            <div class="card-content">
                <h6>
                    <b>{{ $list->title }}</b>
                </h6>

                <br>

                @php($count2 = Illuminate\Support\Facades\DB::table('todo')->where('list_id', '=', $list->id)->where('status', '=', 'Open')->get())

                @if(count($count2) == 0)
                    <p class="align-middle">
                        <i>Nog geen to-do's in deze lijst.</i>
                    </p>
                    <br>
                @endif

                @foreach(Illuminate\Support\Facades\DB::table('todo')->where('list_id', '=', $list->id)->where('status', '=', 'Open')->get() as $todo)
                    <div>
                        <p>
                            <small class="chip white-text"
                                   style="background-color: {{ \App\Models\User::find($todo->user_id)->color }} !important">
                                <a class="white-text"
                                   href="{{ url('/gebruikers/bekijken') }}/{{ $todo->user_id }}">{{ \App\Models\User::find($todo->user_id)->name }}</a>
                            </small>

                            {{ $todo->title }}

                            @if(Illuminate\Support\Facades\DB::table('events')->where('titel', '=', $todo->title)->first() != null)
                                <a onclick="fillFinish({{ $todo->id }}, {{ $todo->user_id }}, '{{ Illuminate\Support\Facades\DB::table('events')->where('titel', '=', $todo->title)->first()->datum_vanaf }}', '{{ Illuminate\Support\Facades\DB::table('events')->where('titel', '=', $todo->title)->first()->tijd_vanaf }}', '{{ Illuminate\Support\Facades\DB::table('events')->where('titel', '=', $todo->title)->first()->tijd_tot }}', {{ Illuminate\Support\Facades\DB::table('events')->where('titel', '=', $todo->title)->first()->project_id }})"
                                   href="#finishTodoModal"
                                   class="modal-trigger btn waves-effect waves-dark bg-light black-text right">
                                    <i class="bi bi-check2-circle"></i>
                                </a>
                            @else
                                <a onclick="fillFinish({{ $todo->id }}, {{ $todo->user_id }})"
                                   href="#finishTodoModal"
                                   class="modal-trigger btn waves-effect waves-dark bg-light black-text right">
                                    <i class="bi bi-check2-circle"></i>
                                </a>
                            @endif

                            <a onclick="fillEditTodo({{ $todo->id }}, '{{ $todo->title }}', {{ $todo->user_id }})"
                               href="#editTodoModal"
                               class="modal-trigger btn waves-effect waves-dark bg-light black-text right">
                                <i class="bi bi-pencil"></i>
                            </a>
                        </p>
                    </div>

                    <br>
                @endforeach

                @php($count = Illuminate\Support\Facades\DB::table('todo')->where('list_id', '=', $list->id)->where('status', '=', 'Afgerond')->get())

                @if(count($count) == 1)
                    <h6>
                        <b>{{count($count)}} to-do afgerond in deze lijst.</b>
                    </h6>
                    <br>
                @elseif(count($count) > 1)
                    <h6>
                        <b>{{count($count)}} to-do's afgerond in deze lijst.</b>
                    </h6>
                    <br>
                @endif

                @foreach(Illuminate\Support\Facades\DB::table('todo')->where('list_id', '=', $list->id)->where('status', '=', 'Afgerond')->get() as $todo)
                    <div>
                        <p>
                            <small class="chip white-text"
                                   style="background-color: {{ \App\Models\User::find($todo->user_id)->color }} !important">
                                <a class="white-text"
                                   href="{{ url('/gebruikers/bekijken') }}/{{ $todo->user_id }}">{{ \App\Models\User::find($todo->user_id)->name }}</a>
                            </small>

                            {{ $todo->title }}

                            <a onclick="deleteTodo({{ $todo->id }})"
                               class="btn waves-effect waves-dark bg-light black-text right">
                                <i class="bi bi-trash"></i>
                            </a>
                        </p>
                    </div>

                    <br>
                @endforeach

                <div>
                    <a onclick="todoId({{ $list->id }})" href="#createTodoModal"
                       class="btn waves-effect waves-dark bg-light black-text modal-trigger">
                        <b>+ Nieuwe to-do</b>
                    </a>

                    <a onclick="fillEditList({{ $list->id }}, '{{ $list->title }}')"
                       href="#editListModal" class="modal-trigger btn waves-effect waves-dark bg-light black-text">
                        <i class="bi bi-pencil"></i>
                    </a>

                    <a onclick="deleteList({{ $list->id }})" class="btn waves-effect waves-dark bg-light black-text">
                        <i class="bi bi-trash"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
@endforeach
