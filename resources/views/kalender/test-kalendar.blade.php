<link href='https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@5.13.1/css/all.css' rel='stylesheet'>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/main.min.js"></script>

<div id="filterKalenderModal" class="modal">
    <div class="modal-content">
        <div class="col s1 m4">
            Filteren op gebruiker:
            <select id="eventGebruiker" name="user">
                <option value="0" selected>Iedereen</option>
                @foreach(\App\Models\User::all() as $user)
                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                @endforeach
                <option value="5ubh5ecf6bttch05in1l1k4224@group.calendar.google.com">Voipzo - Google Calendar
                </option>
            </select>
        </div>
    </div>
</div>

<div>
    <div id="createEventModal" class="modal container">
        <div class="modal-content">

            <form id="eventsForm">
                @csrf

                <h4>Maak een event</h4>

                <div class="row">
                    <div class="col s12">
                        <label>Titel</label>
                        <input type="text" name="titel" required>
                    </div>
                </div>

                <div class="row">
                    <div class="col s4">
                        <label>Project</label>
                        <select id="eventInfo2" class="browser-default" name="project_id">
                            <option value="0">Geen project</option>
                            @foreach($projects as $project)
                                <option value="{{ $project->project_id }}">{{ $project->company_name }}
                                    | {{ $project->title }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col s4">
                        <label>Klant</label>
                        <select id="eventInfo" class="browser-default" name="customer_id">
                            <option value="0">Geen klant</option>
                            @foreach($klanten as $klant)
                                <option value="{{ $klant->id }}">{{ $klant->company_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col s4">
                        <label>Gebruiker</label>
                        <select id="eventInfo3" class="browser-default" name="user_name">
                            <option value="Iedereen">Iedereen</option>
                            @foreach(\App\Models\User::all() as $user)
                                @if($user->id == Auth::user()->id)
                                    <option value="{{ $user->name }}" selected>{{ $user->name }}</option>
                                @else
                                    <option value="{{ $user->name }}">{{ $user->name }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="row">
                    <div class="col s2 switch">
                        <label>Hele dag</label><br>
                        <p></p>
                        <label>
                            Nee
                            <input id="allDay" type="checkbox">
                            <span class="lever"></span>
                            Ja
                        </label>
                    </div>

                    <div class="col s3">
                        <label>Vanaf</label>
                        <input type="date" name="datum_vanaf" required>
                    </div>

                    <div class="col s3">
                        <label>Tot</label>
                        <input type="date" name="datum_tot" required>
                    </div>

                    <div class="col s2">
                        <label>Vanaf</label>
                        <input type="time" name="tijd_vanaf" required>
                    </div>

                    <div class="col s2">
                        <label>Tot</label>
                        <input type="time" name="tijd_tot" required>
                    </div>
                </div>

                <div class="row">
                    <div class="col s2 switch">
                        <label>Todo</label><br>
                        <p></p>
                        <label>
                            Nee
                            <input id="todo" name="todocheck" type="checkbox" checked>
                            <span class="lever"></span>
                            Ja
                        </label>
                    </div>

                    <div class="col s10">
                        <label>List</label>
                        <select id="eventInfo4" class="browser-default" name="list_id">
                            @foreach(Illuminate\Support\Facades\DB::table('todo')->select('list_id')->where('user_id', '=', Auth::user()->id)->distinct()->get() as $todo)
                                @if(Illuminate\Support\Facades\DB::table('todo_list')->where('id', '=', $todo->list_id)->first() != null)
                                    <option value="{{ $todo->list_id }}">{{ Illuminate\Support\Facades\DB::table('todo_list')->where('id', '=', $todo->list_id)->first()->title }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                </div>

            </form>

            <div>
                <button onclick="submitEvent()" class="blue waves-effect waves-light text-white btn">
                    <b>Submit</b>
                </button>
            </div>

        </div>
    </div>


    <div class="container">
        <div id="editEventModal" class="modal modal-fixed-footer container">
            <div class="modal-content">

                <form id="eventsEditForm">
                    @csrf

                    <h4>Bewerk event</h4>

                    <div class="row">
                        <div class="col s12">
                            <label>Titel</label>
                            <input type="text" name="titel_edit" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col s4">
                            <label>Project</label>
                            <select id="eventEdit" class="browser-default" name="project_id_edit">
                                <option value="0">Geen project</option>
                                @foreach($projects as $project)
                                    <option value="{{ $project->project_id }}">{{ $project->company_name }}
                                        | {{ $project->title }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col s4">
                            <label>Klant</label>
                            <select id="eventEdit2" class="browser-default" name="customer_id_edit">
                                <option value="0">Geen klant</option>
                                @foreach($klanten as $klant)
                                    <option value="{{ $klant->id }}">{{ $klant->company_name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col s4">
                            <label>Gebruiker</label>
                            <select id="eventEdit3" class="browser-default" name="user_name_edit">
                                <option value="Iedereen">Iedereen</option>
                                @foreach(\App\Models\User::all() as $user)
                                    <option value="{{ $user->name }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col s2 switch">
                            <label>Hele dag</label><br>
                            <p></p>
                            <label>
                                Nee
                                <input id="allDay2" type="checkbox">
                                <span class="lever"></span>
                                Ja
                            </label>
                        </div>

                        <div class="col s3">
                            <label>Vanaf</label>
                            <input type="date" name="datum_vanaf_edit" required>
                        </div>

                        <div class="col s3">
                            <label>Tot</label>
                            <input type="date" name="datum_tot_edit" required>
                        </div>

                        <div class="col s2">
                            <label>Vanaf</label>
                            <input type="time" name="tijd_vanaf_edit" required>
                        </div>

                        <div class="col s2">
                            <label>Tot</label>
                            <input type="time" name="tijd_tot_edit" required>
                        </div>
                    </div>

                    <input type="hidden" name="id">

                    <div class="row">

                    </div>

                </form>

                <div>
                    <button onclick="editEvent()" class="blue waves-effect waves-light text-white btn">
                        <b>Bewerk</b>
                    </button>
                </div>

                <br>

                <h5>Toevoegen aan uren</h5>
                <p id="invalid">Om uren in te vullen moet je event of een <i>tijd periode</i> hebben, een
                    <i>project</i> en het mag <i>niet langer dan een dag</i> duren.</p>

                <form id="uren">
                    @csrf

                    <div class="row">
                        <div class="col s6">
                            <label>Project</label>
                            <select id="eventEdit4" class="browser-default" name="project_id_uren">
                                @foreach($projects as $project)
                                    <option value="{{ $project->project_id }}">{{ $project->company_name }}
                                        | {{ $project->title }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col s6">
                            <label>Status</label>
                            <select class="browser-default" name="status_uren">
                                <option value="Declarabel">Declarabel</option>
                                <option value="Niet Declarabel">Niet Declarabel</option>
                            </select>
                        </div>
                    </div>

                    <input type="hidden" name="user_name_uren">
                    <input type="hidden" name="datum_vanaf_uren">
                    <input type="hidden" name="tijd_vanaf_uren">
                    <input type="hidden" name="tijd_tot_uren">

                    <input type="hidden" name="titel_uren">

                </form>

                <button onclick="submitHours()" id="submit"
                        class="blue btn-floating waves-effect waves-light text-white">
                    <i class="material-icons">save</i>
                </button>

                <div><p>&nbsp;</p></div>

                <h5>Eenmalig bedrag</h5>

                <form id="uren" action="{{ route('home.eenmalig') }}" method="post">
                    @csrf

                    <div class="row">
                        <div class="col s6">
                            <label>Project</label>
                            <select id="eventEdit5" class="browser-default" name="bedrijf">
                                @foreach($projects as $project)
                                    <option value="{{ $project->company_name }}
                                            | {{ $project->title }}">{{ $project->company_name }}
                                        | {{ $project->title }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col s3">
                            <label>Datum</label>
                            <input type="date" class="bate" name="datum"
                                   value="{{ \Carbon\Carbon::today('Europe/Amsterdam')->format('Y-m-d') }}"
                                   required>
                        </div>

                        <div class="col s3">
                            <label>Bedrag</label>
                            <input type="number" name="prijs">
                        </div>

                        <input type="hidden" name="user_id"
                               value="{{ Illuminate\Support\Facades\Auth::user()->id }}">
                    </div>

                    <button id="submit" class="blue btn-floating waves-effect waves-light text-white">
                        <i class="material-icons">save</i>
                    </button>

                </form>
            </div>

            <div class="modal-footer">
                <form id="eventsDeleteForm">
                    @csrf
                    <input type="hidden" name="event_id">
                </form>
                <a onclick="deleteEvent()"
                   class="text-white modal-close waves-effect waves-white btn-flat">Verwijder
                    event
                </a>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"
        integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>

<script>

    $(document).ready(function () {

        $('#allDay').on('click', function () {
            $("input[type='time']").attr('disabled', $(this).is(':checked'));
        });

        $('#allDay2').on('click', function () {
            $("input[type='time']").attr('disabled', $(this).is(':checked'));
        });

        $('#todo').on('click', function () {
            $("eventEdit4").attr('disabled', $(this).is(':checked'));
        });

        $('#eventGebruiker').on('change', function () {
            $.ajax({
                method: 'GET',
                url: '{{ url('/kalender-filter') }}/' + this.value,
                success: function (data) {
                    $('#cal-script').empty();
                    $('#cal-script').append(data);

                    calendar.render();
                }
            });
        });

        // maak event

        $('#eventInfo').select2({
            dropdownParent: $('#createEventModal'),
            language: {
                "noResults": function () {
                    return "Geen resultaten gevonden...";
                }
            }
        });

        $('#eventInfo2').select2({
            dropdownParent: $('#createEventModal'),
            language: {
                "noResults": function () {
                    return "Geen resultaten gevonden...";
                }
            }
        });

        $('#eventInfo3').select2({
            dropdownParent: $('#createEventModal'),
            language: {
                "noResults": function () {
                    return "Geen resultaten gevonden...";
                }
            }
        });

        $('#eventInfo4').select2({
            dropdownParent: $('#createEventModal'),
            language: {
                "noResults": function () {
                    return "Geen resultaten gevonden...";
                }
            }
        });


        // edit event

        $('#eventEdit').select2({
            dropdownParent: $('#editEventModal'),
            language: {
                "noResults": function () {
                    return "Geen resultaten gevonden...";
                }
            }
        });

        $('#eventEdit2').select2({
            dropdownParent: $('#editEventModal'),
            language: {
                "noResults": function () {
                    return "Geen resultaten gevonden...";
                }
            }
        });

        $('#eventEdit3').select2({
            dropdownParent: $('#editEventModal'),
            language: {
                "noResults": function () {
                    return "Geen resultaten gevonden...";
                }
            }
        });

        $('#eventEdit4').select2({
            dropdownParent: $('#editEventModal'),
            language: {
                "noResults": function () {
                    return "Geen resultaten gevonden...";
                }
            }
        });

        $('#eventEdit5').select2({
            dropdownParent: $('#editEventModal'),
            language: {
                "noResults": function () {
                    return "Geen resultaten gevonden...";
                }
            }
        });

        $('#project_id_board').select2({
            dropdownParent: $('#listCreateModal'),
            language: {
                "noResults": function () {
                    return "Geen resultaten gevonden...";
                }
            }
        });

        $('#project_id_board-edit').select2({
            dropdownParent: $('#editListModal'),
            language: {
                "noResults": function () {
                    return "Geen resultaten gevonden...";
                }
            }
        });

    })
</script>

<script>

    function show() {
        $('#invalid').show();
        $('#uren').show();
    }

    function filter(user) {
        $.ajax({
            method: 'GET',
            url: '{{ url('/kalender-filter') }}/' + user,
            success: function (data) {
                $('#cal-script').empty();
                $('#cal-script').append(data);

                calendar.render();
            }
        });
    }

    function submitEvent() {
        var form = $('#eventsForm')[0];
        let formData = new FormData(form);

        $.ajax({
            method: 'POST',
            url: '{{ url('/kalender') }}',
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            success: function () {
                $('#createEventModal').modal('close');

                show();
                reload();

                Swal.fire({
                    title: 'Event opgeslagen!',
                    icon: 'success',
                    iconColor: '#FA4D09',
                })
            },
        });
    }

    function editEvent() {
        var form = $('#eventsEditForm')[0];
        let formData = new FormData(form);

        $.ajax({
            method: 'POST',
            url: '{{ url('/kalender-edit') }}',
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            success: function () {
                $('#editEventModal').modal('close');

                show();
                reload();

                Swal.fire({
                    title: 'Event bijgewerkt!',
                    icon: 'success',
                    iconColor: '#FA4D09',
                });
            },
        });
    }

    function deleteEvent() {
        var form = $('#eventsDeleteForm')[0];
        let formData = new FormData(form);

        Swal.fire({
            title: 'Wil je deze event verwijderen?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#260089',
            cancelButtonColor: '#f44336',
            confirmButtonText: 'Ja, ik weet het zeker'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    method: 'POST',
                    url: '{{ url('/kalender-delete') }}',
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function () {
                        $('#editEventModal').modal('close');

                        show();
                        reload();

                        Swal.fire({
                            title: 'Event verwijderd!',
                            icon: 'success',
                            iconColor: '#FA4D09',
                        });
                    },
                });
            }
        })
    }

    function submitHours() {
        var form = $('#uren')[0];
        let formData = new FormData(form);

        $.ajax({
            method: 'POST',
            url: '{{ url('/kalender-workorder') }}',
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            success: function () {
                $('#createEventModal').modal('close');

                show();
                reload();

                Swal.fire({
                    title: 'Uren opgeslagen!',
                    icon: 'success',
                    iconColor: '#FA4D09',
                })
            },
        });
    }

    function closeModal() {
        $('#createEventModal').modal('close');
    }

    function closeModal2() {
        $('#editEventModal').modal('close');
        show();
    }

</script>

{{--<div id="todoBoards">--}}
{{--    @include('todo.index')--}}
{{--</div>--}}

{{--@include('todo.modals.show-modal')--}}
{{--@include('todo.modals.index-modal')--}}

<div><p>&nbsp;</p></div>

<small>&nbsp;&nbsp;</small>

@foreach(\App\Models\User::all() as $user)
    <small class="chip white-text" style="background-color: {{ $user->color }} !important">
        <a class="white-text clickable" onclick="filter({{ $user->id }})">{{ $user->name }}</a>
    </small>
@endforeach

<small class="chip white-text" style="background-color: #54B8A2 !important">
    <a class="white-text clickable"
       onclick="filter('5ubh5ecf6bttch05in1l1k4224@group.calendar.google.com')">Voipzo</a>
</small>

<small class="right">&nbsp;&nbsp;&nbsp;</small>
<a class="btn-floating btn waves-effect waves-light blue right modal-trigger" href="#filterKalenderModal">
    <b><i class="material-icons">filter_alt</i></b>
</a>
<small class="right">&nbsp;&nbsp;&nbsp;</small>
<a class="btn-floating btn waves-effect waves-light blue right" onclick="reload()">
    <b><i class="material-icons">refresh</i></b>
</a>

<div class="row bg-grey" id="cal-script">
    @include('kalender.scripts.kalender')
</div>
