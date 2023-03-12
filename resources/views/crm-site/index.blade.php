@extends('layouts.app')

@section('content')
    <style>
        option[value=before] {
            display: none !important
        }
    </style>
    <div class="row">
        <div class="col s12">
            <h4>Klantcases</h4>
            <a href="#createCaseModal" class="btn waves-effect modal-trigger"><i class="material-icons left">add</i>Case
                toevoegen</a>

            <a href="javascript:void(0)" class="btn waves-effect modal-trigger">Total Cases {{$siteProjects->count()}}</a>
            <div id="tableWrapper">
                @include('crm-site.includes.table')
            </div>
        </div>
    </div>


    {{-- Create modal --}}
    <div id="createCaseModal" class="modal modal-fixed-footer">
        <form id="createCaseForm" enctype="multipart/form-data">
            @csrf
            <div class="modal-content">
                <div class="row">
                    <div class="col s12">
                        <h5>Case Toevoegen</h5>
                    </div>
                    <div class="col s12">
                        <label for="project">Selecteer een Project</label>
                        <select name="project" id="project" class="browser-default" required>
                            @foreach($projects as $project)
                                <option value="{{ serialize([$project->id, ($project->customer ? $project->customer->id : '')]) }}">{{ $project->title}} : {{ $project->customer ? $project->customer->company_name : ''}}</option>
                            @endforeach
                        </select>

                    </div>
                    <div class="col s12">
                        {{--                        <label for="customer">Selecteer een klant</label>--}}
                        {{--                        <select name="customer" id="customer" class="browser-default">--}}
                        {{--                            @foreach($customers as $customer)--}}
                        {{--                                <option value="{{ $customer->id }}">{{ $customer->company_name }}</option>--}}
                        {{--                            @endforeach--}}
                        {{--                        </select>--}}
                        <div class="row">
                            <div class="input-field col s6">
                                <input placeholder="Placeholder" name="title_top" id="title_top" type="text"
                                       class="validate">
                                <label for="title_top">Title</label>
                            </div>
                            <div class="input-field col s6">
                                <input id="date_top" name="date_top" type="date" class="validate">
                                <label for="date_top">Date</label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col s12">
                                <label for="customer">Type</label>
                                <select name="type_top" id="type_top" class="browser-default">
                                    <option value="Website">Website</option>
                                    <option value="Webwinkel">Webwinkel</option>
                                    <option value="Software">Software</option>
                                    <option value="Vormgeving">Vormgeving</option>
                                    <option value="Drukwerk">Drukwerk</option>
                                    <option value="Markting">Markting</option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col s12">
                                <div class="file-field input-field">
                                    <div class="btn">
                                        <span>File</span>
                                        <input name="thumbnail" type="file" required>
                                    </div>
                                    <div class="file-path-wrapper">
                                        <input class="file-path validate" type="text">
                                    </div>
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
    </div>



    {{--    edit model--}}
    <div id="editCaseModal" class="modal modal-fixed-footer">
        @include('crm-site.includes.edit-modal')
    </div>

@endsection

@section('scripts')
    <script>
        $('#customer').select2({
            dropdownParent: $('#createCaseModal'),
        });
        $('#project').select2({
            dropdownParent: $('#createCaseModal'),
        });
        $('#type_top').select2({
            dropdownParent: $('#createCaseModal'),
        });


        $('#createCaseForm').on('submit', function (e) {
            e.preventDefault();
            let formData = new FormData(this);

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                url: "{{ route('siteProjects.save') }}",
                method: "POST",
                data: formData,
                contentType: false,
                processData: false,
                success: function (data) {
                    $('#createCaseModal').modal('close');
                    getProjects();
                },
            })
        });


        function getProjects() {
            showPreloader();
            $.ajax({
                url: "/site-projecten/haal-projecten",
                method: "GET",
                success: function (data) {
                    $('#tableWrapper').empty().append(data);
                    hidePreloader();
                },
                error: function (data) {
                    alert('error:' + data.response);
                }
            })
        }

        function deleteCase(caseId) {
            Swal.fire({
                icon: "question",
                title: "Case verwijderen?",
                text: "Weet je zeker dat je deze case wilt verwijderen?",
                confirmButtonText: "Ja",
                confirmButtonColor: "red",
                showCancelButton: true,
                cancelButtonText: "Sluiten",
            }).then((result) => {
                if (result.isConfirmed) {
                    showPreloader();
                    $.get('/site-projecten/deleted-case/' + caseId, function (data) {

                        getProjects();
                        hidePreloader();
                    })
                }
            })
        }

        function editCase(caseId) {
            showPreloader();
            $.get('/site-projecten/wijzig-case', {caseId: caseId}, function (data) {
                $('#editCaseModal').empty().append(data).modal('open');
                M.updateTextFields();
                $('#edit_customer').select2({
                    dropdownParent: $('#editCaseModal'),
                });
                $('#edit_type_top').select2({
                    dropdownParent: $('#editCaseModal'),
                });

                hidePreloader();
            })
        }


        function showContent(siteId) {
            let wrapper = $('.siteContentWrapper[data-id="' + siteId + '"]');
            if (wrapper.hasClass('displayNone')) {
                wrapper.removeClass('displayNone');
            } else {
                wrapper.addClass('displayNone');
            }
        }
    </script>
@endsection
