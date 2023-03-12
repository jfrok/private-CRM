@extends('layouts.app')

@section('content')
    <div class="row bg-grey">
        <div class="col s12 m8">

            <div class="row" style="padding: 30px 20px">
{{--                <div class="card">--}}
{{--                    <div class="card-content">--}}
{{--                        <a id="trello" onclick="trelloShow()"--}}
{{--                           class="btn-floating modal-trigger right orange addButton"><i--}}
{{--                                    class="material-icons">hide_source</i></a>--}}
{{--                        <div class="show">--}}
{{--                        <blockquote class="trello-board-compact">--}}
{{--                            <a href="https://trello.com/b/q4f231Ul">Shops Board</a>--}}
{{--                        </blockquote>--}}
{{--                        <blockquote class="trello-board-compact">--}}
{{--                            <a href="https://trello.com/b/BHDKSNKG">CMS Board</a>--}}
{{--                        </blockquote>--}}
{{--                        <blockquote class="trello-board-compact">--}}
{{--                            <a href="https://trello.com/b/t5kgPBof">Keysoftware Board</a>--}}
{{--                        </blockquote>--}}
{{--                        <blockquote class="trello-board-compact">--}}
{{--                            <a href="https://trello.com/b/SnJk8m65">Safety Triggers Board</a>--}}
{{--                        </blockquote>--}}
{{--                        <blockquote class="trello-board-compact">--}}
{{--                            <a href="https://trello.com/b/Kn5LykKX">Vergadering Board</a>--}}
{{--                        </blockquote>--}}
{{--                        <script src="https://p.trellocdn.com/embed.min.js"></script>--}}
{{--                        </div>--}}
{{--                    </div>--}}

{{--                </div>--}}
                @include('kalender.test-kalendar')
            </div>

            @include('home.progressList')

        </div>
        <div class="col s12 m4">
            <div class="card">
                <div class="card-content">
                    <div class="card-title left" id="hoursListTitle">
                        <i class="bi bi-clock-history"></i> &nbsp;&nbsp;Uren administratie
                    </div>
                    <div class="right">
                        <a href="#openHoursModal" class="btn-floating modal-trigger right orange addButton"><i
                                class="material-icons">add</i></a>
                    </div>
                    <div class="right">
                        <a id="hideHoursList" onclick="hideHoursList()"
                           class="btn-floating modal-trigger right orange addButton"><i
                                class="material-icons">hide_source</i></a>
                    </div>
                    <div id="hoursList">
                        @include('home.hoursList')
                    </div>
                    <script>
                        function hideHoursList() {
                            $('#hoursList').hide();
                            $("#hideHoursList").attr("onclick", "showCallList()");
                        }

                        function showHoursList() {
                            $('#hoursList').show();
                            $("#hideHoursList").attr("onclick", "hideHoursList()");
                        }
                    </script>
                </div>
            </div>
        </div>
    </div>

    <!-- Hours modal -->
    <div class="hoursModal">
        <div id="openHoursModal" class="modal modal-fixed-footer">
            @include('home.ajax.hoursModal')
        </div>
    </div>

    <!-- View hours modal -->
    <div id="viewHoursModal" class="modal modal-fixed-footer">
        @include('home.ajax.viewHoursModal')
    </div>

    {{-- Create todos modal --}}
    <div id="createTodoModal" class="modal modal-fixed-footer">
        <form action="{{ route('home.todos.save') }}" method="post">
            @csrf
            <div class="modal-content">
                <div class="row">
                    <div class="col s12">
                        <div class="title left">
                            Nieuwe to-do
                        </div>
                    </div>
                    <div id="todoProjectWrapper" class="col s12">
                        <br>
                        <label for="todo_project_name">To-do toevoegen project</label>
                        <select name="todo_project_name" id="todo_project_name" class="browser-default"
                                onchange="getProjectCategories($(this).val())" required>
                            <option disabled selected>Toevoegen aan</option>
                            <option value="other">Losse todo</option>
                            @foreach($searchProjects as $sp)
                                <option
                                    value="{{ $sp->id }}">{{ ($sp->customer ? $sp->customer->company_name . " | " : "") . $sp->title }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div id="todoCategoryWrapper" class="col s12 m6 displayNone">
                        <br>
                        <label for="todo_category_name">Project categorie</label>
                        <select name="todo_category_name" id="todo_category_name" class="browser-default">
                            <option disabled selected>Toevoegen aan</option>
                        </select>
                    </div>
                    <div class="col s12 m6 mt-10">
                        <label for="todo_name">Naam van de to-do</label>
                        <input type="text" id="todo_name" name="todo_name" required>
                    </div>
                    <div class="col s12 m6 mt-10">
                        <label for="todo_user">Voor wie is de todo?</label>
                        <select name="todo_user" id="todo_user">
                            @foreach($users as $user)
                                <option value="{{ $user->id }}"
                                        {{ ($user->id == auth()->id() ? "selected" : "") }} data-icon="{{ asset($user->getProfileImage()) }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col s12">
                        <label for="description">Omschrijving</label>
                        <textarea name="todo_description" id="todo_description" cols="30" rows="10"></textarea>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-flat waves-effect modal-close white-text">Sluiten</button>
                <button type="submit" class="orange white-text btn btn-flat right">To-do aanmaken</button>
            </div>
        </form>
    </div>

    {{-- Show todos modal --}}
    <div id="showTodoModal" class="modal modal-fixed-footer">
        @include('home.ajax.todo-modal')
    </div>

@endsection
@section('scripts')
    <script>
        $(document).ready(function () {
            if (/Android|webOS|iPhone|iPad|Mac|Macintosh|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)) {
                //Set mobile icons
                $('#callsListTitle').empty().append('<i class="material-icons icons40">phone_android</i>');
                $('#todosListTitle').empty().append('<i class="material-icons icons40">task_alt</i>');
                $('#hoursListTitle').empty().append('<i class="material-icons icons40">schedule</i>');
            }

            $('#todo_category_name').select2({
                tags: true,
                dropdownParent: $('#createTodoModal'),
                language: {
                    "noResults": function () {
                        return "Geen resultaten gevonden...";
                    }
                }
            });

            $('#todo_project_name').select2({
                dropdownParent: $('#createTodoModal'),
                language: {
                    "noResults": function () {
                        return "Geen resultaten gevonden...";
                    }
                }
            });

            //Initialize required elements
            CKEDITOR.replace('descriptionHolder');
            CKEDITOR.replace('todo_description');
            CKEDITOR.replace('customDescription');
            $('#customerSelectCalls').select2({
                'dropdownParent': $('#createCallModal'),
                'tags': true,
                language: {
                    "noResults": function () {
                        return "Geen resultaten gevonden...";
                    }
                }
            });

            $('#userSelectCalls').select2({
                'dropdownParent': $('#createCallModal'),
                language: {
                    "noResults": function () {
                        return "Geen resultaten gevonden...";
                    }
                }
            });

            $('#customCustomerId').select2({
                'dropdownParent': $('#openHoursModal'),
                language: {
                    "noResults": function () {
                        return "Geen resultaten gevonden...";
                    }
                }
            });

            $('#customProjectId').select2({
                'dropdownParent': $('#openHoursModal'),
                language: {
                    "noResults": function () {
                        return "Geen resultaten gevonden...";
                    }
                }
            });

            $('#customProjectIdEenmalig').select2({
                'dropdownParent': $('#openHoursModal'),
                language: {
                    "noResults": function () {
                        return "Geen resultaten gevonden...";
                    }
                }
            });
        });

        function checkCallNote(el) {
            if (el.is(':checked')) {
                $('.notificationTimes').slideDown(100);
            } else {
                $('.notificationTimes').slideUp(100);
            }
        }
        function trelloShow() {
            $('.show').hide();
            $('#trello').show(100);
        }
        function showDetailCall(id) {
            $('.detailCalls').hide();
            $('#detailCall' + id).show(100);
        }

        function createCallForm() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $('#description').val(CKEDITOR.instances['descriptionHolder'].getData());

            $('#replaceCallList').append('<div class="row"><div class="col s3"></div><div class="col s6 center-align"><div class="preloader-wrapper active"><div class="spinner-layer spinner-red-only"><div class="circle-clipper left"><div class="circle"></div></div><div class="gap-patch"><div class="circle"></div> </div><div class="circle-clipper right"> <div class="circle"></div></div></div></div></div><div class="col s3"></div></div>');
            $.post("/home/belnotities/aanmaken", $("#createCallForm").serialize()).done(function (data) {
                $('#replaceCallList').empty();
                $('#replaceCallList').append(data);

                $('.modal').modal();
                //Initialize required elements
                CKEDITOR.replace('descriptionHolder');
                $('#customerSelectCalls').select2({
                    'dropdownParent': $('#createCallModal'),
                    'tags': true,
                    language: {
                        "noResults": function () {
                            return "Geen resultaten gevonden...";
                        }
                    }
                });
                $('#userSelectCalls').select2({
                    'dropdownParent': $('#createCallModal'),
                    language: {
                        "noResults": function () {
                            return "Geen resultaten gevonden...";
                        }
                    }
                });
            });
        }

        function deleteCall(id) {
            Swal.fire({
                title: 'Belnotitie afronden?',
                icon: 'warning',
                text: 'Weet je zeker dat je deze belnotitie wilt afronden?',
                inputPlaceholder: '{{ $chosenUser->name }}',
                showCancelButton: true,
                iconColor: '#FA4D09',
                confirmButtonText: 'Afronden',
                cancelButtonText: 'Annuleren',
                confirmButtonColor: '#FA4D09',
                cancelButtonColor: '#260089',
            }).then((result) => {
                if (result.isConfirmed) {
                    $.get('/home/belnotities/verwijderen' + '/' + id, function (data) {
                        $('#replaceCallList').empty();
                        $('#replaceCallList').append(data);

                        $('.modal').modal();
                        //Initialize required elements
                        CKEDITOR.replace('descriptionHolder');
                        $('#customerSelectCalls').select2({
                            'dropdownParent': $('#createCallModal'),
                            'tags': true,
                            language: {
                                "noResults": function () {
                                    return "Geen resultaten gevonden...";
                                }
                            }
                        });
                        $('#userSelectCalls').select2({
                            'dropdownParent': $('#createCallModal'),
                            language: {
                                "noResults": function () {
                                    return "Geen resultaten gevonden...";
                                }
                            }
                        });
                    });
                }
            });
        }

        function saveHours(projId, el) {
            let fromTime = el.find('.fromTimeHolder').find('.fromTime').val();
            let toTime = el.find('.toTimeHolder').find('.toTime').val();

            $.get('/home/uren/opslaan/' + projId + '/' + fromTime + '/' + toTime, function (data) {
                $('#replaceHoursModal').empty();
                $('#replaceHoursModal').append(data);
                $('#customProjectId').select2({
                    'dropdownParent': $('#openHoursModal'),
                    language: {
                        "noResults": function () {
                            return "Geen resultaten gevonden...";
                        }
                    }
                });

                CKEDITOR.replace('customDescription');
                reloadHoursList();
                Swal.fire({
                    title: 'Uren ingevuld!',
                    icon: 'success',
                    iconColor: '#FA4D09',
                });
            });
        }

        function saveCustomHours(el) {
            let fromTime = el.find('.customHolder').find('.fromTimeHolder').find('.fromTime').val();
            let toTime = el.find('.customHolder').find('.toTimeHolder').find('.toTime').val();
            let date = el.find('.customHolder').find('.dateHolder').find('.date').val();
            let project = el.find('.customHolder').find('.projectHolder').find('#customProjectId').val();
            let status = el.find('.customHolder').find('.statusHolder').find('#status').val();
            let description = CKEDITOR.instances['customDescription'].getData();

            $.get('/home/uren/custom-opslaan/', {
                from: fromTime,
                to: toTime,
                project: project,
                status: status,
                description: description,
                date: date
            }, function (data) {
                $('#replaceHoursModal').empty();
                $('#replaceHoursModal').append(data);
                $('#customProjectId').select2({
                    'dropdownParent': $('#openHoursModal'),
                    language: {
                        "noResults": function () {
                            return "Geen resultaten gevonden...";
                        }
                    }
                });
                CKEDITOR.replace('customDescription');
                reloadHoursList();
                Swal.fire({
                    title: 'Uren ingevuld!',
                    icon: 'success',
                    iconColor: '#FA4D09',
                });
            });
        }

        function reloadHoursList() {
            $.get('/home/uren/laden', function (data) {
                $('#replaceHoursList').empty();
                $('#replaceHoursList').append(data);
            });
        }

        function changeDate(position) {
            let date = $('#hoursInput').val();
            $.get('/home/uren/datum-veranderen/' + date + '/' + position, function () {
                $.get('/home/uren/laden', function (data) {
                    $('#replaceHoursList').empty();
                    $('#replaceHoursList').append(data);
                });
            });
        }

        function openWorkOrderModal(id) {
            $.get('/home/uren/bekijken/' + id, function (data) {
                $('#replaceViewHoursModal').empty();
                $('#replaceViewHoursModal').append(data);
                $('select').formSelect();
                CKEDITOR.replace('editDescription');
                $('#editProjectId').select2({
                    'dropdownParent': $('#viewHoursModal'),
                    language: {
                        "noResults": function () {
                            return "Geen resultaten gevonden...";
                        }
                    }
                });
                $('#viewHoursModal').modal('open');
            });
        }

        function editCustomHours(id) {
            let fromTime = $('.editFromTime').val();
            let toTime = $('.editToTime').val();
            let date = $('.editDate').val();
            let project = $('.editProjectId').val();
            let status = $('.editStatus').val();
            let description = CKEDITOR.instances['editDescription'].getData();

            $.get('/home/uren/wijzigen/' + id, {
                from: fromTime,
                to: toTime,
                project: project,
                status: status,
                description: description,
                date: date
            }, function () {
                reloadHoursList();
                Swal.fire({
                    title: 'Uren aangepast!',
                    icon: 'success',
                    iconColor: '#FA4D09',
                });
            });
        }

        function deleteWorkOrder(id) {
            $.get('/home/uren/verwijderen/' + id, function (data) {
                $('#replaceHoursModal').empty();
                $('#replaceHoursModal').append(data);
                $('#customProjectId').select2({
                    'dropdownParent': $('#openHoursModal'),
                    language: {
                        "noResults": function () {
                            return "Geen resultaten gevonden...";
                        }
                    }
                });
                CKEDITOR.replace('customDescription');
                reloadHoursList();
                Swal.fire({
                    title: 'Uren verwijderd!',
                    icon: 'success',
                    iconColor: '#FA4D09',
                });
            });
        }

        function getProjectCategories(projectId, type = "create") {

            if (type === "create") {
                let projectWrapper = $('#todoProjectWrapper');
                let categoryWrapper = $('#todoCategoryWrapper');

                if (projectId !== "other") {
                    $.get('/home/todos/haal-categorieen', {projectId: projectId}, function (data) {
                        $('#todo_category_name').find('option').remove();
                        $(data).each(function () {
                            let newOption = new Option($(this)[0].category_name);
                            $('#todo_category_name').append(newOption).trigger('change');
                        });
                        projectWrapper.addClass('m6');
                        categoryWrapper.removeClass('displayNone');
                    });
                } else {
                    $('#todo_category_name').find('option').remove();
                    projectWrapper.removeClass('m6');
                    categoryWrapper.addClass('displayNone');
                }
            } else {
                let projectWrapper = $('#editTodoProjectWrapper');
                let categoryWrapper = $('#editTodoCategoryWrapper');

                if (projectId !== "other") {
                    $.get('/home/todos/haal-categorieen', {projectId: projectId}, function (data) {
                        $('#edit_todo_category_name').find('option').remove();
                        $(data).each(function () {
                            let newOption = new Option($(this)[0].category_name);
                            $('#edit_todo_category_name').append(newOption).trigger('change');
                        });
                        projectWrapper.addClass('m6');
                        categoryWrapper.removeClass('displayNone');
                    });
                } else {
                    $('#edit_todo_category_name').find('option').remove();
                    projectWrapper.removeClass('m6');
                    categoryWrapper.addClass('displayNone');
                }
            }
        }

        function filterTodos() {
            let options = {};
            let projects = [];

            $.get('/home/todos/filter-get-projects', function (data) {
                $(data).each(function () {
                    projects.push({id: $(this)[0].id, name: $(this)[0].name});
                });

                $.map(projects,
                    function (o) {
                        options[o.id] = o.name;
                    }
                );

                Swal.fire({
                    title: "Kies een project",
                    text: "Alle onderstaande projecten hebben to-do's die op jouw naam staan, kies één.",
                    input: 'select',
                    inputOptions: options,
                    confirmButtonText: "Filter",
                    confirmButtonColor: "#FA4D09",
                    showDenyButton: true,
                    denyButtonText: "Reset (losse todo's)",
                    denyButtonColor: "#260089",
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.get('/home/todos/filter', {projectId: result.value}, function (data) {
                            $('#replaceTodosList').empty().append(data);
                            $('.tooltipped').tooltip();
                        });
                    }

                    if (result.isDenied) {
                        $.get('/home/todos/filter', {projectId: null}, function (data) {
                            $('#replaceTodosList').empty().append(data);
                            $('.tooltipped').tooltip();
                        });
                    }
                });
            });
        }


        function finishTodo(id) {
            $.get('/home/todos/taak-afronden/' + id, function (data) {
                if (data.status === "OK") {
                    $('#showTodoModal').modal('close');
                    $.get('/home/todos/filter', {projectId: null}, function (data) {
                        $('#replaceTodosList').empty().append(data);
                        Swal.fire({
                            icon: "success",
                            text: "To-do afgerond!",
                            confirmButtonColor: "#FA4D09",
                            confirmButtonText: "Oké",
                        });
                    });
                }
            });
        }


        function showTodoModal(id) {
            $.get('/home/todos/bekijken/' + id, function (data) {
                $('#replaceTodoModal').empty();
                $('#replaceTodoModal').append(data);
                CKEDITOR.replace('edit_todo_description');
                $('#edit_todo_project_name').select2({
                    dropdownParent: $('#showTodoModal'),
                    language: {
                        "noResults": function () {
                            return "Geen resultaten gevonden...";
                        }
                    }
                });
                $('#edit_todo_category_name').select2({
                    dropdownParent: $('#showTodoModal'),
                    tags: true,
                    language: {
                        "noResults": function () {
                            return "Geen resultaten gevonden...";
                        }
                    }
                });
                $('#showTodoModal').modal('open');
            });
        }

        $("#customerSelectCalls").on('change', function() {
            $.ajax({
                method: 'GET',
                url: '{{ url('/home/belnotities/autofill') }}/' + this.value,
                success: function (phone) {
                    $('#phoneNumber').val(phone)
                }
            });
        });
    </script>
@endsection
