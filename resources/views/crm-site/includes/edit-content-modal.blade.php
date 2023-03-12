@isset($selectedCaseContent)
    <form id="EditTextForm" enctype="multipart/form-data">
        @csrf
        <div class="modal-content">
            <h5>Tekst edit</h5>
            @if($selectedCaseContent->type == "text")
                <div class="input-field col s6">
                    <input placeholder="Placeholder" name="edit_title" value="{{$selectedCaseContent->title}}" id="edit_title"
                           type="text"
                           class="validate">
                    <label for="title">Title</label>
                </div>


                <div class="row">
                    <label for="description">Description</label>
                    <textarea name="edit_description" >{{$selectedCaseContent->description}}</textarea>
                </div>
            @else
                <div class="file-field input-field">
                    <div class="btn">
                        <span>File</span>
                        <input name="edit_image_path" value="{{$selectedCaseContent->image_path}}" type="file">
                    </div>
                    <div class="file-path-wrapper">
                        <input class="file-path validate" value="{{$selectedCaseContent->image_path}}" type="text">
                    </div>
                </div>
            @endif
        </div>
        <div class="modal-footer">
            <button type="button" class="btn-flat waves-effect modal-close white-text">Sluiten</button>
            <button type="submit" class="btn-flat waves-effect white-text">Opslaan</button>
        </div>
    </form>


<script>
    $('#EditTextForm').on('submit', function (e) {
        e.preventDefault();
        let formData = new FormData(this);

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });


        $.ajax({
            url: "{{ route('saveEditContent.save', ['cId' => $selectedCaseContent->id]) }}",
            method: "POST",
            contentType: false,
            processData: false,
            data: formData,
            success: function (data) {
                $('#EditTextModal').modal('close');
                getProjectsContents()
                successMessage('Gelukt', data.response);
            },
            error: function (data) {
                alert('error:' + data.response);
            }
        })
    });
</script>

@endisset
