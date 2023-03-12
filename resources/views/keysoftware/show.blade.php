@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col s6 actionButton" onclick="loadNewPage('keysoftware.includes.ajax.main')">
            <div class="card">
                <div class="card-content purple lighten-2 center white-text">
                    <i class="material-icons">info</i>
                    <br>
                    <span>Makelaar gegevens</span>
                </div>
            </div>
        </div>
{{--        <div class="col s4 actionButton" onclick="loadNewPage('keysoftware.includes.ajax.invoices')">--}}
{{--            <div class="card">--}}
{{--                <div class="card-content yellow lighten-2 center black-text">--}}
{{--                    <i class="material-icons">list_alt</i>--}}
{{--                    <br>--}}
{{--                    <span>Facturatie gegevens</span>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
        <div class="col s6 actionButton" onclick="loadNewPage('keysoftware.includes.ajax.api')">
            <div class="card">
                <div class="card-content orange lighten-2 center white-text">
                    <i class="material-icons">api</i>
                    <br>
                    <span>Api call gegevens</span>
                </div>
            </div>
        </div>
    </div>
    <div id="pageContainer">
        @include('keysoftware.includes.ajax.main')
    </div>
@endsection
@section('scripts')
    <script>
        $(document).ready(function() {
        });

        function loadNewPage(view) {
            showPreloader();
            $('#pageContainer').empty();

            $.get('{{ url('/keysoftware/makelaar-bekijken/' . $customer->id . '/pagina-laden') }}' + '/' + view, function( data ) {
                $('#pageContainer').empty();
                $('#pageContainer').append(data);
                hidePreloader();
            });
        }

        function generateWebsiteUrl() {
            let company_name = $('#company_name').val();
            $('#company_website').val('https://www.' + company_name.replace(/[^A-Z0-9]/ig, "") + '.keysoftware.nl');
            generateApiToken();
        }

        function generateApiToken() {
            $.get('{{ url('keysoftware/genereer-api-token') }}', function (data) {
                $('#api_token').val(data);
            });
        }

        function copyApiToken() {
            document.getElementById('company_api_token').select();
            document.execCommand('copy');
        }

        function changeTimeline() {
            let year = $('#timelineYear').val();
            $.get('{{ url('/keysoftware/makelaar-bekijken/' . $customer->id . '/tijdlijn-aanpassen') }}' + '/' + year, function( data ) {
                loadNewPage('keysoftware.includes.ajax.api')
            });
        }

        function deleteCustomer(id) {
            Swal.fire({
                title: 'Weet je het zeker?',
                text: "Je kan de makelaar niet terughalen.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Verwijderen',
                cancelButtonText: 'Annuleren'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "{{ route('keysoftware.delete.makelaar', $customer->id) }}"
                }
            })
        }
    </script>
@endsection
