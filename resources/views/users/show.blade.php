@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col m3 actionButton" onclick="loadNewPage('users.ajax.showMain')">
            <div class="card">
                <div class="card-content purple lighten-2 center white-text">
                    <i class="material-icons">info</i>
                    <br>
                    <span>Gebruiker gegevens</span>
                </div>
            </div>
        </div>
        <div class="col m3 actionButton" onclick="loadNewPage('users.ajax.showLogs')">
            <div class="card">
                <div class="card-content yellow lighten-2 center black-text">
                    <i class="material-icons">receipt_long</i>
                    <br>
                    <span>Logboek</span>
                </div>
            </div>
        </div>
        <div class="col m3 actionButton" onclick="loadNewPage('users.ajax.showWorkorders')">
            <div class="card">
                <div class="card-content red lighten-2 center white-text">
                    <i class="material-icons">pending_actions</i>
                    <br>
                    <span>Ingevulde uren</span>
                </div>
            </div>
        </div>
        <div class="col m3 actionButton" onclick="loadNewPage('users.ajax.showPerformance')">
            <div class="card">
                <div class="card-content green lighten-2 center white-text">
                    <i class="material-icons">insights</i>
                    <br>
                    <span>Prestaties</span>
                </div>
            </div>
        </div>
    </div>

    <div id="pageContainer">
        @include('users.ajax.showMain')
    </div>

    <!-- View hours modal -->
    <div id="viewHoursModal" class="modal modal-fixed-footer">
        @include('home.ajax.viewHoursModal')
    </div>
@endsection
@section('scripts')
    <script>
        $(document).ready(function () {
            CKEDITOR.replace('descriptionHolder');
        });

        function loadNewPage(view) {
            $('#pageContainer').empty().append('<div class="row"><div class="col s3"></div><div class="col s6 center-align"><div class="preloader-wrapper active"><div class="spinner-layer spinner-red-only"><div class="circle-clipper left"><div class="circle"></div></div><div class="gap-patch"><div class="circle"></div> </div><div class="circle-clipper right"> <div class="circle"></div></div></div></div></div><div class="col s3"></div></div>');
            $.get('/gebruikers/bekijken/{{ $user->id }}/pagina-laden/' + view, function (data) {
                $('#pageContainer').empty();
                $('#pageContainer').append(data);
                if (view == 'users.ajax.showMain') {
                    CKEDITOR.replace('descriptionHolder');
                }
            });
        }

        function deleteUser() {
            Swal.fire({
                icon: 'warning',
                title: 'Gebruiker verwijderen?',
                text: 'Weet je zeker dat je deze gebruiker wilt verwijderen?',
                confirmButtonText: 'Verwijderen',
                confirmButtonColor: '#E53935',
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '{{ url('/gebruikers/verwijderen/'.$user->id) }}'
                }
            })
        }

        function submitUserForm() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
            });
            $('#description').val(CKEDITOR.instances['descriptionHolder'].getData());

            $.post("/gebruikers/aanpassen/{{ $user->id }}", $("#editUserForm").serialize()).done(function (data) {
                $('#pageContainer').empty();
                $('#pageContainer').append(data);
                CKEDITOR.replace('descriptionHolder');
                Swal.fire({
                    icon: 'success',
                    title: 'Wijzigingen opgeslagen!',
                })
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
                loadNewPage('users.ajax.showWorkorders');
                Swal.fire({
                    title: 'Uren aangepast!',
                    icon: 'success',
                    iconColor: '#FA4D09',
                });
            });
        }

        function deleteWorkOrder(id) {
            $.get('/home/uren/verwijderen/' + id, function (data) {
                loadNewPage('users.ajax.showWorkorders');
                Swal.fire({
                    title: 'Uren verwijderd!',
                    icon: 'success',
                    iconColor: '#FA4D09',
                });
            });
        }

        function changeTimeline() {
            let month = $('#timelineMonth').val();
            let year = $('#timelineYear').val();

            $.get('/gebruikers/bekijken/{{$user->id}}/tijdlijn-aanpassen/' + month + '/' + year, function () {
                loadNewPage('users.ajax.showPerformance')
            });
        }
    </script>
@endsection
