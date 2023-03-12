<div id="createNote">
    <form id="createNoteForm">
        @csrf

        <div class="row">
            <div class="col s12">
                <label>Titel</label>
                <input type="text" name="title" id="noteTitle" required>
            </div>
        </div>

        <div class="row">
            <div class="col s6">
                <label>Project</label>
                <select id="noteProject" class="browser-default" name="project_id">
                    @foreach($projects as $project)
                        <option
                            value="{{ $project->id }}">{{ Illuminate\Support\Facades\DB::table('customers')->where('id', '=', $project->customer_id)->first()->company_name }}
                            | {{ $project->title }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col s6">
                <label>Gebruiker</label>
                <select id="noteUser" class="browser-default" name="user_id">
                    @foreach(\App\Models\User::all() as $user)
                        @if($user->id == $chosenUser->id)
                            <option value="{{ $user->id }}" selected>{{ $user->name }}</option>
                        @else
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                        @endif
                    @endforeach
                </select>
            </div>
        </div>

        <div class="row">
            <div class="col s12">
                <label>Body</label>
                <input id="editor" required>
            </div>
        </div>

        <input type="hidden" id="noteBody" name="body">

    </form>

    <button onclick="createNote()"
            class="btn waves-effect waves-dark bg-light black-text">
        <b>Maak een notitie</b>
    </button>

    <script src="https://cdn.ckeditor.com/4.17.2/standard/ckeditor.js"></script>

    <script>
        CKEDITOR.replace('editor');
    </script>

    <div><p>&nbsp;</p></div>

</div>

<div class="row">
    <div class="col s12">
        <div id="noteList">
            @include('home.ajax.notitie-list')
        </div>
    </div>
</div>

<div id="editNoteModal" class="modal">
    <div class="modal-content">

        <form id="editNoteForm">
            @csrf

            <div class="row">
                <div class="col s12">
                    <label>Titel</label>
                    <input type="text" name="title" id="noteTitleEdit" required>
                </div>
            </div>

            <div class="row">
                <div class="col s12 m6">
                    <label>Project</label>
                    <select id="noteProjectEdit" class="browser-default" name="project_id">
                        <option value="">Geen project</option>
                        @foreach($projects as $project)
                            <option
                                value="{{ $project->id }}">{{ Illuminate\Support\Facades\DB::table('customers')->where('id', '=', $project->customer_id)->first()->company_name }}
                                | {{ $project->title }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col s12 m6">
                    <label>Gebruiker</label>
                    <select id="noteUserEdit" class="browser-default" name="user_id">
                        @foreach(\App\Models\User::all() as $user)
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="row">
                <div class="col s12">
                    <label>Body</label>
                    <input id="editor2" required>
                </div>
            </div>

            <input type="hidden" id="noteId" name="id" value="">
            <input type="hidden" id="noteBodyEdit" name="body">

        </form>

        <script>
            CKEDITOR.replace('editor2');
        </script>

        <button onclick="editNote()"
                class="btn waves-effect waves-dark bg-light black-text">
            <b>Pas notitie aan</b>
        </button>
    </div>
</div>

<form id="deleteNoteForm">
    @csrf
    <input type="hidden" id="noteIdDelete" name="id">
</form>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"
        integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>

<script>
    $(document).ready(function () {

        $('#createNote').hide();
        $('#backButton').hide();

        $('#noteProject').select2({
            language: {
                "noResults": function () {
                    return "Geen resultaten gevonden...";
                }
            }
        });

        $('#noteUser').select2({
            language: {
                "noResults": function () {
                    return "Geen resultaten gevonden...";
                }
            }
        });

        $('#noteProjectEdit').select2({
            dropdownParent: $('#editNoteModal'),
            language: {
                "noResults": function () {
                    return "Geen resultaten gevonden...";
                }
            }
        });

        $('#noteUserEdit').select2({
            dropdownParent: $('#editNoteModal'),
            language: {
                "noResults": function () {
                    return "Geen resultaten gevonden...";
                }
            }
        });

    })
</script>

<script>

    function unhideCreateForm() {
        $('#createNote').show();
        $("#addButton").attr("onclick", "hideCreateForm()");
    }

    function hideCreateForm() {
        $('#createNote').hide();
        $("#addButton").attr("onclick", "unhideCreateForm()");
    }

    function reloadNotes() {
        $.ajax({
            method: 'GET',
            url: '{{ url('/note-reload') }}/',
            success: function (data) {
                $('#backButton').hide();

                $('#noteList').empty();
                $('#noteList').append(data);
            }
        });
    }

    function viewNote(id) {
        $.ajax({
            method: 'GET',
            url: '{{ url('/note-view') }}/' + id,
            success: function (data) {
                $('#backButton').show();

                $('#noteList').empty();
                $('#noteList').append(data);
            }
        });
    }

    function createNote() {
        var editor = CKEDITOR.instances.editor.getData();
        $("#noteBody").val(editor);

        var form = $('#createNoteForm')[0];
        let formData = new FormData(form);

        $("#noteTitle").val('');

        $.ajax({
            method: 'POST',
            url: '{{ url('/note-create') }}',
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            success: function () {
                reloadNotes();
                hideCreateForm();

                Swal.fire({
                    title: 'Notitie aangemaakt!',
                    icon: 'success',
                    iconColor: '#FA4D09',
                })
            },
        });
    }

    function fillEditForm(id, title, user, project, body) {
        $("#noteId").val(id);
        $("#noteTitleEdit").val(title);

        CKEDITOR.instances.editor2.setData(body);

        $('#noteUserEdit').val(user);
        $('#noteUserEdit').trigger('change');

        $('#noteProjectEdit').val(project);
        $('#noteProjectEdit').trigger('change');
    }

    function editNote() {
        var editor = CKEDITOR.instances.editor2.getData();
        $("#noteBodyEdit").val(editor);

        var form = $('#editNoteForm')[0];
        let formData = new FormData(form);

        $.ajax({
            method: 'POST',
            url: '{{ url('/note-edit') }}',
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            success: function () {
                reloadNotes();

                Swal.fire({
                    title: 'Notitie aangepast!',
                    icon: 'success',
                    iconColor: '#FA4D09',
                })
            },
        });
    }

    function deleteNote(id) {
        $("#noteIdDelete").val(id);

        var form = $('#deleteNoteForm')[0];
        let formData = new FormData(form);

        Swal.fire({
            title: 'Wil je deze notitie verwijderen?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#260089',
            cancelButtonColor: '#f44336',
            confirmButtonText: 'Ja, ik weet het zeker'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    method: 'POST',
                    url: '{{ url('/note-delete') }}',
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function () {
                        reloadNotes();

                        Swal.fire({
                            title: 'Notitie verwijderd!',
                            icon: 'success',
                            iconColor: '#FA4D09',
                        });
                    },
                });
            }
        })
    }

</script>
