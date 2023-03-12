@extends('layouts.app')

@section('content')
    <h5>&nbsp;&nbsp;<i class="bi bi-credit-card-2-front"></i> &nbsp;&nbsp;WeFact</h5>

    <div class="card">
        <div class="card-content">
            <h6>
                <b>Factuur uploaden</b>
            </h6>

            <div>
                <p>Upload hier je CSV bestanden.</p>
            </div>

            <div><p>&nbsp;</p></div>

            <form id="csvUpload" enctype="multipart/form-data">

                @csrf
                <div class="file-field input-field">

                    <div class="btn waves-effect waves-light blue">
                        <b>Upload CSV</b>
                        <input id="file" type="file" name="file">
                    </div>

                    <div class="file-path-wrapper">
                        <input class="file-path validate" type="text">
                    </div>

                </div>

            </form>

            <div>
                <button onclick="csvUpload()" class="btn waves-effect waves-light blue">
                    <b>Upload</b>
                </button>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-content">
            <h6>
                <b>Lijst van facturen</b>
            </h6>
            <div id="factuurLijst">
                @include('wefact.ajax.facturen')
            </div>
        </div>
    </div>

    <script>

        function csvUpload() {
            var form = $('#csvUpload')[0];
            let formData = new FormData(form);

            $.ajax({
                method: 'POST',
                url: '{{ url('/upload-factuur') }}',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: function () {
                    reload();

                    Swal.fire({
                        title: 'Bestand geupload!',
                        icon: 'success',
                        iconColor: '#FA4D09',
                    })
                },
            });
        }

        function csvExport() {
            var form = $('#csvExport')[0];
            let formData = new FormData(form);

            Swal.fire({
                title: 'Wil je deze factuur uploaden naar WeFact?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#260089',
                cancelButtonColor: '#f44336',
                confirmButtonText: 'Ja, ik weet het zeker'
            }).then((result) => {
                if (result.isConfirmed) {
                    showPreloader();
                    $.ajax({
                        method: 'POST',
                        url: '{{ url('/export-factuur') }}',
                        data: formData,
                        cache: false,
                        contentType: false,
                        processData: false,
                        success: function () {
                            reload();
                            hidePreloader();

                            Swal.fire({
                                title: 'Factuur geüpload!',
                                icon: 'success',
                                iconColor: '#FA4D09',
                            })
                        },
                        error: function (data) {
                            console.log(data);
                        }
                    });
                }
            })
        }

        function csvDelete() {
            var form = $('#csvDelete')[0];
            let formData = new FormData(form);

            Swal.fire({
                title: 'Wil je deze factuur verwijderen?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#260089',
                cancelButtonColor: '#f44336',
                confirmButtonText: 'Ja, ik weet het zeker'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        method: 'POST',
                        url: '{{ url('/delete-factuur') }}',
                        data: formData,
                        cache: false,
                        contentType: false,
                        processData: false,
                        success: function () {
                            reload();

                            Swal.fire({
                                title: 'Factuur verwijderd!',
                                icon: 'success',
                                iconColor: '#FA4D09',
                            })
                        },
                    });
                }
            })
        }

        function reload() {
            $.ajax({
                method: 'GET',
                url: '{{ url('/reload-facturen') }}',
                success: function (data) {
                    $('#factuurLijst').empty();
                    $('#factuurLijst').append(data);
                }
            });
        }

    </script>
@endsection
