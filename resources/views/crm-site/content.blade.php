@extends('layouts.app')

@section('content')

    <div class="row">
        <div class="col s12">
            <h4>Content aanpassen</h4>
            <p>Pas hieronder de content van deze klanten case aan.</p>

            <div class="right-align">
                <a href="#addImageModal" class="modal-trigger btn-flat waves-effect"><i
                        class="material-icons left">add_circle_outline</i>Afbeelding toevoegen</a>
                <a href="#addTextModal" class="modal-trigger btn-flat waves-effect"><i
                        class="material-icons left">add_circle_outline</i>Tekst toevoegen</a>
            </div>
            <div id="tableContentWrapper">
                @include('crm-site.includes.table-content')
            </div>
        </div>
    </div>
    <div id="EditTextModal" class="modal modal-fixed-footer">
        @include('crm-site.includes.edit-content-modal')
    </div>
    <div id="addTextModal" class="modal modal-fixed-footer">
        <form action="{{route('siteProjectsContentText.save',['siteId'=>$siteId])}}" method="POST">
            @csrf
            <div class="modal-content">
                <h5>Tekst toevoegen</h5>
                <div class="input-field col s6">
                    <input placeholder="Placeholder" name="title" id="title" type="text"
                           class="validate">
                    <label for="title">Title</label>
                </div>
                <input placeholder="Placeholder" name="type" value="text" type="hidden"
                       class="validate">

                <div class="row">
                    <label for="title">Description</label>
                    <textarea name="description" id="editor1"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-flat waves-effect modal-close white-text">Sluiten</button>
                <button type="submit" class="btn-flat waves-effect white-text">Opslaan</button>
            </div>
        </form>
    </div>

    <div id="addImageModal" class="modal modal-fixed-footer">
        <form action="{{route('siteProjectsContentFoto.save',['siteId'=>$siteId])}}" method="POST"
              enctype="multipart/form-data">
            @csrf
            <div class="modal-content">
                <h5>Afbeelding toevoegen</h5>

                <div class="row">
                    <div class="col s12">
                        <input placeholder="Placeholder" name="type" value="foto" type="hidden"
                               class="validate">
                        <div class="file-field input-field">
                            <div class="btn">
                                <span>File</span>
                                <input name="image_path" type="file">
                            </div>
                            <div class="file-path-wrapper">
                                <input class="file-path validate" type="text">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-flat waves-effect modal-close white-text">Sluiten</button>
                <button type="submit" class="btn-flat waves-effect white-text">Opslaan</button>
            </div>
        </form>
    </div>

@endsection

@section('scripts')
    <script src="https://cdn.ckeditor.com/4.20.0/standard/ckeditor.js"></script>
    <script>

        function getProjectsContents() {
            $.ajax({
                url: "/site-projecten/haal-projecten-content/{{ $siteId }}",
                method: "GET",
                success: function (data) {
                    $('#tableContentWrapper').empty().append(data);
                    hidePreloader();
                },
                error: function (data) {
                    alert('error:' + data.response);
                }
            });
        }

        $('#sortable').sortable({
            placeholder: "ui-state-highlight",
            update: function () {
                let contentIds = [];
                $('input[name="contentIds[]"]').each(function () {
                    contentIds.push($(this).val());
                });

                // Get request to save sort
                $.get('/site-projecten/edit-sort/content', {contentIds:contentIds});
            }
        });

        function deleteCaseContent(cId) {
            Swal.fire({
                icon: "question",
                title: "Case verwijderen?",
                text: "Weet je zeker dat je deze content wilt verwijderen?",
                confirmButtonText: "Ja",
                confirmButtonColor: "red",
                showCancelButton: true,
                cancelButtonText: "Sluiten",
            }).then((result) => {
                if (result.isConfirmed) {
                    showPreloader();
                    $.get('/site-projecten/deleted-content/' + cId, function () {
                        getProjectsContents();
                        hidePreloader();
                    })
                }
            })
        }



        function editCaseContent(cId) {
            $.get('/site-projecten/wijzig-case-content', {cId: cId}, function (data) {
                $('#EditTextModal').empty().append(data).modal('open');
                M.updateTextFields();
                CKEDITOR.replace('edit-editor');
            })
        }
        // function editCaseFotoContent(cId) {
        //     $.get('/site-projecten/wijzig-case-content-foto-', {cId: cId}, function (data) {
        //         $('#EditTextModal').empty().append(data).modal('open');
        //         M.updateTextFields();
        //         CKEDITOR.replace('edit-editor');
        //     })
        // }

        // showPreloader();


        CKEDITOR.replace('editor1');
    </script>
@endsection
