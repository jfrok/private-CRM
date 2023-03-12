<h5>&nbsp;&nbsp;<i class="bi bi-calendar-check"></i> &nbsp;&nbsp;Kalender en To-do's
    <small class="right">&nbsp;&nbsp;&nbsp;</small>
    <a class="btn-floating btn waves-effect waves-light blue right modal-trigger" href="#createBoardModal">
        <b><i class="material-icons">add</i></b>
    </a>
    <small class="right">&nbsp;&nbsp;&nbsp;</small>
    <a class="btn-floating btn waves-effect waves-light blue right modal-trigger" href="#filterModal">
        <b><i class="material-icons">filter_alt</i></b>
    </a>
    <small class="right">&nbsp;&nbsp;&nbsp;</small>
    <a id="hideButton" onclick="hideBoards()" class="btn-floating btn waves-effect waves-light blue right">
        <b><i class="material-icons">hide_source</i></b>
    </a>
</h5>

<div class="row bg-grey">
    <div id="boardsList">
        @include('todo.ajax.boards')
    </div>
</div>

<form id="deleteBoardForm">
    @csrf
    <input type="hidden" name="board_id" id="boardId" value="">
</form>

<script>

    $(document).ready(function () {

        $('#boardProject').select2({
            dropdownParent: $('#createBoardModal'),
            language: {
                "noResults": function () {
                    return "Geen resultaten gevonden...";
                }
            }
        });

        $('#boardEditStatus').select2({
            dropdownParent: $('#editBoardModal'),
            language: {
                "noResults": function () {
                    return "Geen resultaten gevonden...";
                }
            }
        });

        $('#boardEditProject').select2({
            dropdownParent: $('#editBoardModal'),
            language: {
                "noResults": function () {
                    return "Geen resultaten gevonden...";
                }
            }
        });

        $('#boardsStatus').on('change', function () {
            $.ajax({
                method: 'GET',
                url: '{{ url('/todo-status-boards') }}/' + this.value,
                success: function (data) {
                    $('#boardsList').empty();
                    $('#boardsList').append(data);
                }
            });
        });
    })

</script>

<script>

    function hideBoards() {
        $('#boardsList').empty();
        $("#hideButton").attr("onclick", "reloadBoards()");
    }

    function reloadBoards() {
        $.ajax({
            method: 'GET',
            url: '{{ url('/todo-reload-boards') }}',
            success: function (data) {
                $('#boardsList').empty();
                $('#boardsList').append(data);
                $("#hideButton").attr("onclick", "hideBoards()");
            }
        });
    }

    function viewBoard(id, project) {
        $("#projectId").val(project);

        $.ajax({
            method: 'GET',
            url: '{{ url('/todo-board') }}/' + id,
            success: function (data) {
                $('#todoBoards').empty();
                $('#todoBoards').append(data);
            }
        });
    }

    function submitBoard() {
        var form = $('#createBoardForm')[0];
        let formData = new FormData(form);

        $("#boardTitle").val('');

        $.ajax({
            method: 'POST',
            url: '{{ url('/todo-create-board') }}',
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            success: function () {
                reloadBoards();

                Swal.fire({
                    title: 'Board aangemaakt!',
                    icon: 'success',
                    iconColor: '#FA4D09',
                })
            },
        });
    }

    function fillEditBoard(id, title, status, project) {
        $("#boardEditId").val(id);
        $("#boardEditTitle").val(title);

        $('#boardEditStatus').val(status);
        $('#boardEditStatus').trigger('change');

        $('#boardEditProject').val(project);
        $('#boardEditProject').trigger('change');
    }

    function editBoard() {
        var form = $('#editBoardForm')[0];
        let formData = new FormData(form);

        $.ajax({
            method: 'POST',
            url: '{{ url('/todo-edit-board') }}',
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            success: function () {
                reloadBoards();

                Swal.fire({
                    title: 'Board aangepast!',
                    icon: 'success',
                    iconColor: '#FA4D09',
                })
            },
        });
    }

    function deleteBoard(id) {
        $("#boardId").val(id);

        var form = $('#deleteBoardForm')[0];
        let formData = new FormData(form);

        Swal.fire({
            title: 'Wil je deze board verwijderen?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#260089',
            cancelButtonColor: '#f44336',
            confirmButtonText: 'Ja, ik weet het zeker'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    method: 'POST',
                    url: '{{ url('/todo-delete-board') }}',
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function () {
                        reloadBoards();

                        Swal.fire({
                            title: 'Board verwijderd!',
                            icon: 'success',
                            iconColor: '#FA4D09',
                        });
                    },
                });
            }
        })
    }

</script>
