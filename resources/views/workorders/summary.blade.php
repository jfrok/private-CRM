@extends('layouts.app')

@section('content')

    <div class="row">

        @include('workorders.ajax.workOrderList')

    </div>



    <!-- View hours modal -->

    <div id="viewHoursModal" class="modal modal-fixed-footer roundedModal">

        @include('home.ajax.viewHoursModal')

    </div>



    <script>

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

                reloadSummaryList();

                Swal.fire({

                    title: 'Uren aangepast!',

                    icon: 'success',

                    iconColor: '#FA4D09',

                });

            });

        }



        function deleteWorkOrder(id) {

            $.get('/home/uren/verwijderen/'+id, function( data ) {

                reloadSummaryList();

                Swal.fire({

                    title: 'Uren verwijderd!',

                    icon: 'success',

                    iconColor: '#FA4D09',

                });

            });

        }



        function reloadSummaryList() {

            $.get('/uren/overzicht-laden', function( data ) {

                $('#replaceWorkOrderList').empty();

                $('#replaceWorkOrderList').append(data);

            });

        }



        function changeTimeline() {

            let month = $('#timelineMonth').val();

            let year = $('#timelineYear').val();



            $.get('/uren/overzicht/tijdlijn-aanpassen/'+month+'/'+year, function() {

                reloadSummaryList();

            });

        }

    </script>

@endsection

