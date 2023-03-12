@isset($selectedCase)
    <form id="editCaseForm" enctype="multipart/form-data" >
        @csrf
        <div class="modal-content">
            <div class="row">
                <div class="col s12">
                    <h5>Case wijzigen</h5>
                </div>
                <div class="col s12">
{{--                    <label for="customer">Selecteer een klant</label>--}}
{{--                    <select name="edit_customer" id="edit_customer" class="browser-default">--}}
{{--                        @foreach($customers as $customer)--}}
{{--                            <option--}}
{{--                                value="{{ $customer->id }}" {{ ($selectedCase->customer_id == $customer->id ? "selected" : "") }}>{{ $customer->company_name }}</option>--}}
{{--                        @endforeach--}}
{{--                    </select>--}}
                    <label for="customer">Selecteer een klant</label>
                    <select name="editproject" id="edit_customer" class="browser-default">
                        @php($getProject = \App\Models\Project::find($selectedCase->project->id))
                        <option value="{{ $selectedCase->project->id }}">
                            {{$getProject->title }} | {{$selectedCase->project->customer->company_name}}
                        </option>
                        @foreach($projects as $project)

                            <option value="{{ $project->id }}">
{{--                                {{ dd($getProject-) }}--}}
                                {{ $project->title}} : {{ $project->title ? $project->customer->company_name : ''}}
                            </option>
                        @endforeach
                    </select>
                    <div class="row">
                        <div class="input-field col s6">
                            <input placeholder="Placeholder" name="edit_title_top" id="edit_title_top" type="text"
                                   value="{{ $selectedCase->title }}" class="validate">
                            <label for="edit_title_top">Title</label>
                        </div>
                        <div class="input-field col s6">
                            <input id="edit_date_top" name="edit_date_top" value="{{ $selectedCase->date }}" type="date"
                                   class="validate">
                            <label for="date_top">Date</label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col s12">
                            <label for="edit_type_top">Type</label>
                            <select name="edit_type_top" id="edit_type_top" class="browser-default">
                                <option value="Website"{{ ($selectedCase->type == 'Website' ? "selected" : "") }}>
                                    Website
                                </option>
                                <option value="Webwinkel"{{ ($selectedCase->type == 'Webwinkel' ? "selected" : "") }}>
                                    Webwinkel
                                </option>
                                <option value="Software"{{ ($selectedCase->type == 'Software' ? "selected" : "") }}>
                                    Software
                                </option>
                                <option value="Vormgeving"{{ ($selectedCase->type == 'Vormgeving' ? "selected" : "") }}>
                                    Vormgeving
                                </option>
                                <option value="Drukwerk"{{ ($selectedCase->type == 'Drukwerk' ? "selected" : "") }}>
                                    Drukwerk
                                </option>
                                <option value="Markting"{{ ($selectedCase->type == 'Markting' ? "selected" : "") }}>
                                    Markting
                                </option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col s12">
                        <div class="file-field input-field">
                            <div class="btn">
                                <span>File</span>
                                <input name="edit_thumbnail" type="file" value="{{ $selectedCase->thumbnail }}">
                            </div>
                            <div class="file-path-wrapper">
                                <input class="file-path validate" type="text"
                                     name="edit_thumbnail_path"  value="{{ $selectedCase->thumbnail }}">
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn-flat waves-effect modal-close  white-text">Sluiten</button>
            <button type="submit" class="btn-flat waves-effect  white-text"><i
                    class="material-icons left white-text">save</i>Opslaan
            </button>
        </div>
    </form>

    <script>
        $('#editCaseForm').on('submit', function (e) {
            e.preventDefault();
            let formData = new FormData(this);
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });


            $.ajax({
                url: "{{ route('siteProjects.saveEdit', ['caseId' => $selectedCase->id]) }}",
                method: "POST",
                contentType: false,
                processData: false,
                data: formData,
                success: function (data) {
                    $('#editCaseModal').modal('close');
                    getProjects();
                    successMessage('Gelukt', data.response);
                },
                error: function (data) {
                    alert('error:' + data.response);
                }
            })
        });
    </script>
@endisset
