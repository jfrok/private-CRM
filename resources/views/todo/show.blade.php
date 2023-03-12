<h5>
    &nbsp;&nbsp; <i class="bi bi-card-list"></i> {{ $board->title }}
    <small class="right">&nbsp;&nbsp;&nbsp;</small>
    <a onclick="reloadBoards()" class="btn-floating btn waves-effect waves-light blue right">
        <b><i class="material-icons">arrow_back</i></b>
    </a>
    <small class="right">&nbsp;&nbsp;&nbsp;</small>
    <a onclick="loadFinished()" id="hideButton" class="btn-floating btn waves-effect waves-light blue right">
        <b><i class="material-icons">check</i></b>
    </a>
    <small class="right">&nbsp;&nbsp;&nbsp;</small>
    <a onclick="giveBoardId()" href="#listCreateModal"
       class="modal-trigger btn-floating btn waves-effect waves-light blue right">
        <b><i class="material-icons">add</i></b>
    </a>
</h5>

<div class="row bg-grey">
    <div id="todoLists">
        @include('todo.ajax.lists')
    </div>
</div>

<form id="deleteListForm">
    @csrf
    <input type="hidden" name="list_id" id="listId2" value="">
</form>

<form id="deleteTodoForm">
    @csrf
    <input type="hidden" name="todo_id" id="todoId" value="">
</form>


<script>

    $(document).ready(function () {

        $('#todoUser').select2({
            dropdownParent: $('#createTodoModal'),
            language: {
                "noResults": function () {
                    return "Geen resultaten gevonden...";
                }
            }
        });

        $('#todoKlant').select2({
            dropdownParent: $('#createTodoModal'),
            language: {
                "noResults": function () {
                    return "Geen resultaten gevonden...";
                }
            }
        });

        $('#todoProject').select2({
            dropdownParent: $('#createTodoModal'),
            language: {
                "noResults": function () {
                    return "Geen resultaten gevonden...";
                }
            }
        });

        $('#todoEditUser').select2({
            dropdownParent: $('#editTodoModal'),
            language: {
                "noResults": function () {
                    return "Geen resultaten gevonden...";
                }
            }
        });

        $('#project_id_uren').select2({
            dropdownParent: $('#finishTodoModal'),
            language: {
                "noResults": function () {
                    return "Geen resultaten gevonden...";
                }
            }
        });

        $('#disableCalendar').on('click', function () {
            $("input[type='time']").attr('disabled', $(this).is(':checked'));
            $("input[type='date']").attr('disabled', $(this).is(':checked'));
        });

        $('#disableWorkorder').on('click', function () {
            $("input[type='time']").attr('disabled', $(this).is(':checked'));
            $("input[type='date']").attr('disabled', $(this).is(':checked'));
            $("#project_id_uren").attr('disabled', $(this).is(':checked'));
            $("#status").attr('disabled', $(this).is(':checked'));
        });

        // maak event

        $('#eventInfo').select2({
            dropdownParent: $('#eventsModal'),
            language: {
                "noResults": function () {
                    return "Geen resultaten gevonden...";
                }
            }
        });

        $('#eventInfo2').select2({
            dropdownParent: $('#eventsModal'),
            language: {
                "noResults": function () {
                    return "Geen resultaten gevonden...";
                }
            }
        });

        $('#eventInfo3').select2({
            dropdownParent: $('#eventsModal'),
            language: {
                "noResults": function () {
                    return "Geen resultaten gevonden...";
                }
            }
        });
    })

</script>

<script>

    function giveBoardId() {
        $("#boardCreateId").val({{ $board->id }});
    }

    function reloadBoards() {
        $.ajax({
            method: 'GET',
            url: '{{ url('/todo-reload-boards-list') }}',
            success: function (data) {
                $('#todoBoards').empty();
                $('#todoBoards').append(data);
            }
        });
    }

    function loadFinished() {
        $.ajax({
            method: 'GET',
            url: '{{ url('/todo-finished-lists') }}/{{ $board->id }}',
            success: function (data) {
                $('#todoLists').empty();
                $('#todoLists').append(data);
                $("#hideButton").attr("onclick", "reloadLists()");
            }
        });
    }

    function reloadLists() {
        $.ajax({
            method: 'GET',
            url: '{{ url('/todo-reload-lists') }}/{{ $board->id }}',
            success: function (data) {
                $('#todoLists').empty();
                $('#todoLists').append(data);
                $("#hideButton").attr("onclick", "loadFinished()");
            }
        });
    }

    function submitList() {
        var form = $('#createListForm')[0];
        let formData = new FormData(form);

        $.ajax({
            method: 'POST',
            url: '{{ url('/todo-create-list') }}',
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            success: function () {
                reloadLists();

                Swal.fire({
                    title: 'Lijst aangemaakt!',
                    icon: 'success',
                    iconColor: '#FA4D09',
                })

                $("#listTitle").val('');
            },
        });
    }

    function fillEditList(id, title, project) {
        $("#listEditId").val(id);
        $("#listEditTitle").val(title);

        $('#project_id_board-edit').val(project);
        $('#project_id_board-edit').trigger('change');
    }

    function editList() {
        var form = $('#editListForm')[0];
        let formData = new FormData(form);

        $.ajax({
            method: 'POST',
            url: '{{ url('/todo-edit-list') }}',
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            success: function () {
                reloadLists();

                Swal.fire({
                    title: 'Lijst aangepast!',
                    icon: 'success',
                    iconColor: '#FA4D09',
                })
            },
        });
    }

    function deleteList(id) {
        $("#listId2").val(id);

        var form = $('#deleteListForm')[0];
        let formData = new FormData(form);

        Swal.fire({
            title: 'Wil je deze lijst verwijderen?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#260089',
            cancelButtonColor: '#f44336',
            confirmButtonText: 'Ja, ik weet het zeker'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    method: 'POST',
                    url: '{{ url('/todo-delete-list') }}',
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function () {
                        reloadLists();

                        Swal.fire({
                            title: 'Lijst verwijderd!',
                            icon: 'success',
                            iconColor: '#FA4D09',
                        });
                    },
                });
            }
        })
    }

    function fillEditTodo(id, title, user) {
        $("#todoEditId").val(id);
        $("#todoEditTitle").val(title);
        $("#eventTitle").val(title);

        $('#todoEditUser').val(user);
        $('#todoEditUser').trigger('change');

        $('#eventInfo3').val(user);
        $('#eventInfo3').trigger('change');

        $('#eventInfo2').val({{ $board->project_id }});
        $('#eventInfo2').trigger('change');
    }

    function fillFinish(id, user, datumVanaf, tijdVanaf, tijdTot, project) {
        $("#finishTodoId").val(id);
        $("#userUren").val(user);
        $("#datumVanafUren").val(datumVanaf);
        $("#tijdVanafUren").val(tijdVanaf);
        $("#tijdTotUren").val(tijdTot);

        $('#project_id_uren').val(project);
        $('#project_id_uren').trigger('change');
    }

    function editTodo() {
        var form = $('#editTodoForm')[0];
        let formData = new FormData(form);

        $.ajax({
            method: 'POST',
            url: '{{ url('/todo-edit') }}',
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            success: function () {
                reloadLists();

                Swal.fire({
                    title: 'Todo aangepast!',
                    icon: 'success',
                    iconColor: '#FA4D09',
                })
            },
        });
    }

    function finishTodo() {
        var form = $('#finishTodoForm')[0];
        let formData = new FormData(form);

        $.ajax({
            method: 'POST',
            url: '{{ url('/todo-finish') }}',
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            success: function () {
                reloadLists();
                reload();

                Swal.fire({
                    title: 'Todo afgerond!',
                    icon: 'success',
                    iconColor: '#FA4D09',
                })
            },
        });
    }

    function submitTodo() {
        var form = $('#createTodoForm')[0];
        let formData = new FormData(form);

        $("#todoTitle").val('');

        $.ajax({
            method: 'POST',
            url: '{{ url('/todo-create') }}',
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            success: function () {
                reloadLists();
                reload();

                Swal.fire({
                    title: 'Todo aangemaakt!',
                    icon: 'success',
                    iconColor: '#FA4D09',
                })
            },
        });
    }

    function deleteTodo() {
        $("#todoId").val($("#todoEditId").val());

        var form = $('#deleteTodoForm')[0];
        let formData = new FormData(form);

        Swal.fire({
            title: 'Wil je deze todo verwijderen?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#260089',
            cancelButtonColor: '#f44336',
            confirmButtonText: 'Ja, ik weet het zeker'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    method: 'POST',
                    url: '{{ url('/todo-delete') }}',
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function () {
                        reloadLists();
                        reload();

                        Swal.fire({
                            title: 'Todo verwijderd!',
                            icon: 'success',
                            iconColor: '#FA4D09',
                        });
                    },
                });
            }
        })
    }

    function todoId(id, project) {
        $("#listId").val(id);

        $('#todoProject').val(project);
        $('#todoProject').trigger('change');
    }

</script>
