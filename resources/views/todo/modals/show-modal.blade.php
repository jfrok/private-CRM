<div id="editListModal" class="modal">
    <div class="modal-content">

        <form id="editListForm">
            @csrf

            <div class="row">
                <div class="col s6">
                    <label>Titel</label>
                    <input type="text" name="title" id="listEditTitle" required>
                </div>

                <div class="col s6">
                    <label>Project</label>
                    <select id="project_id_board-edit" class="browser-default" name="project_id">
                        <option value="">Geen project</option>
                        @foreach($projects as $project)
                            <option value="{{ $project->project_id }}">{{ $project->company_name }}
                                | {{ $project->title }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <input type="hidden" name="list_id" id="listEditId" value="">

        </form>

        <button onclick="editList()"
                class="btn waves-effect waves-light bg-light black-text">
            <b>Bewerk lijst</b>
        </button>
    </div>
</div>

<div id="listCreateModal" class="modal">
    <div class="modal-content">

        <form id="createListForm">
            @csrf

            <div class="row">
                <div class="col s6">
                    <label>Titel</label>
                    <input type="text" name="title" id="listTitle" required>
                </div>

                <div class="col s6">
                    <label>Project</label>
                    <select id="project_id_board" class="browser-default" name="project_id">
                        <option value="">Geen project</option>
                        @foreach($projects as $project)
                            <option value="{{ $project->project_id }}">{{ $project->company_name }}
                                | {{ $project->title }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <input type="hidden" id="boardCreateId" name="board_id" value="">
        </form>

        <button onclick="submitList()"
                class="btn waves-effect waves-dark bg-light black-text">
            <b>+ Nieuwe lijst</b>
        </button>

    </div>
</div>

<div id="editTodoModal" class="modal">
    <div class="modal-content">

        <form id="editTodoForm">
            @csrf

            <div>
                <label>Titel</label>
                <input type="text" name="title" id="todoEditTitle" required>
            </div>

            <div class="row">
                <div>
                    <label>Gebruiker</label>
                    <select class="browser-default" id="todoEditUser" name="user_id">
                        @foreach(\App\Models\User::all() as $user)
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <input type="hidden" name="todo_id" id="todoEditId" value="">

        </form>

        <button onclick="editTodo()"
                class="btn waves-effect waves-dark bg-light black-text">
            <b>Bewerk todo</b>
        </button>
        <small>&nbsp;&nbsp;&nbsp;</small>
        <button onclick="deleteTodo()"
                class="btn waves-effect waves-dark red">
            <b>Verwijder todo</b>
        </button>
    </div>
</div>

<div id="createTodoModal" class="modal">
    <div class="modal-content">

        <form id="createTodoForm">
            @csrf

            <div class="row">
                <div class="col s12">
                    <label>Titel</label>
                    <input type="text" id="todoTitle" name="title" required>
                </div>
            </div>

            <div class="row">
                <div class="col s4">
                    <label>Gebruiker</label>
                    <select id="todoUser" class="browser-default" name="user_id">
                        @foreach(\App\Models\User::all() as $user)
                            @if($user->id == Auth::user()->id)
                                <option value="{{ $user->id }}" selected>{{ $user->name }}</option>
                            @else
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>

                <div class="col s4">
                    <label>Project</label>
                    <select id="todoProject" class="browser-default" name="project_id">
                        <option value="0">Geen project</option>
                        @foreach($projects as $project)
                            <option value="{{ $project->project_id }}">{{ $project->company_name }}
                                | {{ $project->title }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col s4">
                    <label>Klant</label>
                    <select id="todoKlant" class="browser-default" name="klant_id">
                        <option value="0">Geen klant</option>
                        @foreach(Illuminate\Support\Facades\DB::table('customers')->get() as $klant)
                            <option value="{{ $klant->id }}">{{ $klant->company_name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="row">
                <div class="col s2 switch">
                    <label>Toevoegen aan agenda</label><br>
                    <p></p>
                    <label>
                        Ja
                        <input id="disableCalendar" type="checkbox">
                        <span class="lever"></span>
                        Nee
                    </label>
                </div>

                <div class="col s3">
                    <label>Vanaf</label>
                    <input type="date" name="datum_vanaf"
                           value="{{ Carbon\Carbon::today('Europe/Amsterdam')->format('Y-m-d') }}" required>
                </div>

                <div class="col s3">
                    <label>Tot</label>
                    <input type="date" name="datum_tot"
                           value="{{ Carbon\Carbon::today('Europe/Amsterdam')->format('Y-m-d') }}" required>
                </div>

                <div class="col s2">
                    <label>Vanaf</label>
                    <input type="time" name="tijd_vanaf"
                           value="{{ Carbon\Carbon::now('Europe/Amsterdam')->format('H:i') }}" required>
                </div>

                <div class="col s2">
                    <label>Tot</label>
                    <input type="time" name="tijd_tot"
                           value="{{ Carbon\Carbon::now('Europe/Amsterdam')->add('hour', 1)->format('H:i') }}" required>
                </div>
            </div>

            <input type="hidden" name="list_id" id="listId" value="">

        </form>

        <button onclick="submitTodo()"
                class="btn waves-effect waves-dark bg-light black-text">
            <b>Maak todo</b>
        </button>
    </div>
</div>

<div id="finishTodoModal" class="modal">
    <div class="modal-content">

        <form id="finishTodoForm">
            @csrf

            <div class="row">
                <div class="col s2 switch">
                    <label>Toevoegen aan uren</label><br>
                    <p></p>
                    <label>
                        Ja
                        <input id="disableWorkorder" type="checkbox">
                        <span class="lever"></span>
                        Nee
                    </label>
                </div>

                <div class="col s2">
                    <label>Project</label>
                    <select id="project_id_uren" class="browser-default" name="project_id_uren">
                        @foreach($projects as $project)
                            <option value="{{ $project->project_id }}">{{ $project->company_name }}
                                | {{ $project->title }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col s2">
                    <label>Datum</label>
                    <input type="date" id="datumVanafUren" name="datum" required>
                </div>

                <div class="col s2">
                    <label>Vanaf</label>
                    <input type="time" id="tijdVanafUren" name="tijd_vanaf_uren" required>
                </div>

                <div class="col s2">
                    <label>Tot</label>
                    <input type="time" id="tijdTotUren" name="tijd_tot_uren" required>
                </div>

                <div class="col s2">
                    <label>Status</label>
                    <select class="browser-default" id="status" name="status_uren">
                        <option value="Declarabel">Declarabel</option>
                        <option value="Niet Declarabel">Niet Declarabel</option>
                    </select>
                </div>
            </div>

            <input type="hidden" id="userUren" name="user_id_uren">
            <input type="hidden" id="datumVanafUren" name="datum_vanaf_uren">

            <input type="hidden" id="finishTodoId" name="todo_id">

        </form>

        <button onclick="finishTodo()"
                class="btn waves-effect waves-dark bg-light black-text">
            <b>Afronden</b>
        </button>
    </div>
</div>
