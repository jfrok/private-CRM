@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col s12 m9">
            <div class="card">
                <div class="card-content">
                    <div class="title left">
                        Keysoftware Klanten
                    </div>
                    <a href="#createCustomerModal" class="btn btn-floating orange right tooltipped modal-trigger"
                       data-tooltip="Klant aanmaken" data-position="left"><i class="material-icons">add</i></a>
                    <a href="#productPricesModal" class="btn btn-floating orange right tooltipped modal-trigger mr-10"
                       data-tooltip="Prijzen bewerken" data-position="left"><i class="material-icons">price_change</i></a>
                    <br><br><br>

                    <div id="replaceCustomersTable">
                        @include('keysoftware.includes.customersTable')
                    </div>
                </div>
            </div>
        </div>
        <div class="col s12 m3">
            <div class="card">
                <div class="card-content">
                    <div class="title left">
                        Filters & zoeken
                    </div>
                    <div class="row">
                        <div class="col s12 input-field">
                            <label for="search">Zoeken</label>
                            <input type="text" name="search" id="searchQuery" onkeyup="searchCustomers()">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('keysoftware.includes.createCustomerModal')
    @include('keysoftware.includes.productPricesModal')
@endsection
@section('scripts')
    <script>
        function searchCustomers() {
            let searchQuery = $('#searchQuery').val();

            showPreloader();
            $.get('keysoftware/makelaars-zoeken', {searchQuery: searchQuery}, function (data) {
                $('#replaceCustomersTable').empty();
                $('#replaceCustomersTable').append(data);
                hidePreloader();
            });
        }

        function goToMakelaar(id) {
            window.location.href = '{{ url('keysoftware/makelaar-bekijken') }}' + '/' + id;
        }

        function generateWebsiteUrl() {
            let name = $('#company_name').val();
            $('#company_website').val('https://www.' + name.replace(/[^A-Z0-9]/ig, "") + '.nl');
            generateApiToken();
        }

        function generateApiToken() {
            $.get('{{ url('keysoftware/genereer-api-token') }}', function (data) {
                $('#api_token').val(data);
            });
        }

        function copyKeysoftwareApiToken() {
            document.getElementById('api_token').select();
            document.execCommand('copy');
        }
    </script>
@endsection
