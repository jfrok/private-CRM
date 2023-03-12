@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col s4 m4 actionButton" onclick="loadNewPage('projects.ajax.showMain')">
            <div class="card">
                <div class="card-content red lighten-2 center white-text">
                    <i class="material-icons">info</i>
                    <br>
                    <span>Project gegevens</span>
                </div>
            </div>
        </div>
        <div class="col s4 m4 actionButton" onclick="window.location.href='{{ url('/klanten/bekijken/'. $project->customer_id) }}'">
            <div class="card">
                <div class="card-content purple lighten-2 center white-text">
                    <i class="material-icons">people</i>
                    <br>
                    <span>Naar klant</span>
                </div>
            </div>
        </div>
        <div class="col s4 m4 actionButton" onclick="loadNewPage('projects.ajax.showGraphs')">
            <div class="card">
                <div class="card-content green lighten-2 center white-text">
                    <i class="material-icons">insights</i>
                    <br>
                    <span>Financiële overzichten</span>
                </div>
            </div>
        </div>
    </div>
    <div id="pageContainer">
{{--        @include('projects.ajax.showMain')--}}
    </div>

    <div id="todoModal" class="modal modal-fixed-footer roundedModal">
        @include('projects.ajax.todoModal')
    </div>

    <!-- View hours modal -->
    <div id="viewHoursModal" class="modal modal-fixed-footer roundedModal">
        @include('home.ajax.viewHoursModal')
    </div>
@endsection
@section('scripts')
    <script>
        $(document).ready(function() {
            loadNewPage('projects.ajax.showMain');
        });

        function loadNewPage(view) {
            $('#pageContainer').empty().append('<div class="row"><div class="col s3"></div><div class="col s6 center-align"><div class="preloader-wrapper active"><div class="spinner-layer spinner-red-only"><div class="circle-clipper left"><div class="circle"></div></div><div class="gap-patch"><div class="circle"></div> </div><div class="circle-clipper right"> <div class="circle"></div></div></div></div></div><div class="col s3"></div></div>');
            $.get('/projecten/bekijken/{{ $project->id }}/pagina-laden/'+view, function( data ) {
                $('#pageContainer').empty();
                $('#pageContainer').append(data);
                if(view == 'projects.ajax.showMain') {
                    initHomePage();
                } else if(view == 'projects.ajax.showTodos') {
                    initTodoPage();
                } else if(view == 'projects.ajax.showTimeline') {
                    initTimelinePage();
                }
            });
        }

        function initHomePage() {
            $('select').formSelect();
            $('#customer_id').select2({
                tags: false,
                language: {
                    "noResults": function(){
                        return "Geen resultaten gevonden...";
                    }
                }
            });
            $('.dropdown-trigger').dropdown({
                constrainWidth:false,
            });
            CKEDITOR.replace( 'descriptionHolder' );
        }

        function initTodoPage() {
            $('.tooltipped').tooltip();
            CKEDITOR.replace( 'descriptionHolder' );
            $('#category_name').select2({
                tags: true,
                language: {
                    "noResults": function(){
                        return "Geen resultaten gevonden...";
                    }
                }
            });
        }

        function initTimelinePage() {

        }

        function finishTodo(id, el) {
            if(el.is(":checked")) {
                $.get('/projecten/bekijken/{{ $project->id }}/taak-afronden/'+id, function(data) {
                    $('#pageContainer').empty();
                    $('#pageContainer').append(data);
                    initTodoPage();
                });
            } else {
                $.get('/projecten/bekijken/{{ $project->id }}/taak-openen/'+id, function(data) {
                    $('#pageContainer').empty();
                    $('#pageContainer').append(data);
                    initTodoPage()
                });
            }
        }

        function submitTodoForm() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $('#description').val(CKEDITOR.instances['descriptionHolder'].getData());

            $.post( "/projecten/bekijken/{{ $project->id }}/taak-aanmaken", $( "#createTodoForm" ).serialize()).done( function(data) {
                $('#pageContainer').empty();
                $('#pageContainer').append(data);
                initTodoPage();
            });
        }

        function showTodoModal(id) {
            $.get('/projecten/bekijken/{{ $project->id }}/taak-bekijken/'+id, function( data ) {
                $('#replaceTodoModal').empty();
                $('#replaceTodoModal').append(data);
                CKEDITOR.replace( 'editDescriptionHolder' );
                $('select').formSelect();
                $('#edit_category_name').select2({
                    dropdownParent: '#todoModal',
                    tags: true,
                    language: {
                        "noResults": function(){
                            return "Geen resultaten gevonden...";
                        }
                    }
                });
                $('#todoModal').modal('open');
            });
        }

        function editTodoForm(id) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $('#editDescription').val(CKEDITOR.instances['editDescriptionHolder'].getData());

            $.post( "/projecten/bekijken/{{ $project->id }}/taak-aanpassen/"+id, $( "#editTodoForm" ).serialize()).done( function(data) {
                $('.modal').modal('close');
                $('#pageContainer').empty();
                $('#pageContainer').append(data);
                initTodoPage();
            });
        }

        function changeTimeline() {
            let month = $('#timelineMonth').val();
            let year = $('#timelineYear').val();

            $.get('/projecten/bekijken/{{$project->id}}/tijdlijn-aanpassen/'+month+'/'+year, function() {
                loadNewPage('projects.ajax.showTimeline')
            });
        }

        function openWorkOrderModal(id) {
            $.get('/home/uren/bekijken/'+id, function( data ) {
                $('#replaceViewHoursModal').empty();
                $('#replaceViewHoursModal').append(data);
                $('select').formSelect();
                CKEDITOR.replace( 'editDescription' );
                $('#editProjectId').select2({
                    'dropdownParent': $('#viewHoursModal'),
                    language: {
                        "noResults": function(){
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

            $.get('/home/uren/wijzigen/'+id, {from:fromTime,to:toTime,project:project,status:status,description:description,date:date}, function() {
                loadNewPage('projects.ajax.showTimeline');
                Swal.fire({
                    title: 'Uren aangepast!',
                    icon: 'success',
                    iconColor: '#FA4D09',
                });
            });
        }

        function deleteWorkOrder(id) {
            $.get('/home/uren/verwijderen/'+id, function( data ) {
                loadNewPage('projects.ajax.showTimeline');
                Swal.fire({
                    title: 'Uren verwijderd!',
                    icon: 'success',
                    iconColor: '#FA4D09',
                });
            });
        }

        function submitProjectForm() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $('#description').val(CKEDITOR.instances['descriptionHolder'].getData());

            $('#pageContainer').append('<div class="row"><div class="col s3"></div><div class="col s6 center-align"><div class="preloader-wrapper active"><div class="spinner-layer spinner-red-only"><div class="circle-clipper left"><div class="circle"></div></div><div class="gap-patch"><div class="circle"></div> </div><div class="circle-clipper right"> <div class="circle"></div></div></div></div></div><div class="col s3"></div></div>');
            $.post( "/projecten/aanpassen/{{ $project->id }}", $( "#editProjectForm" ).serialize()).done( function(data) {
                loadNewPage('projects.ajax.showMain');
                Swal.fire({
                    icon: 'success',
                    title: 'Project aangepast!',
                    showDenyButton: true,
                    showCancelButton: false,
                    denyButtonText: `Sluiten`,
                    denyButtonColor: '#260089',
                })
            });
        }

        function changeStatus(status) {
            $.get('/projecten/bekijken/{{ $project->id }}/status-veranderen/'+status, function( data ) {
                loadNewPage('projects.ajax.showMain');
            });
        }
    </script>
@endsection
