@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col s12 m9">
            <div class="card">
                <div class="card-content">
                    <div class="title left">
                        Gebruikers
                    </div>
                    <a href="#createUserModal" class="btn btn-floating orange right tooltipped modal-trigger"
                       data-tooltip="Gebruiker aanmaken" data-position="left"><i class="material-icons">add</i></a>
                    <br><br><br>
                    @include('users.ajax.usersTable')
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
                            <input type="text" name="search" id="searchQuery" onkeyup="searchUsers()">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- CreateCustomerModal Structure -->
    <div id="createUserModal" class="modal modal-fixed-footer roundedModal">
        <form id="createUsersForm" method="post" enctype="multipart/form-data">
            @csrf
            <div class="modal-content">
                <div class="row">
                    <div class="col s12">
                        <span class="subtitle">Gebruiker gegevens</span>
                    </div>
                    <div class="col s12">
                        <label for="name">{{ __('Naam') }}</label>
                        <input id="name" type="text" name="name" required>
                    </div>
                    <div class="col s12">
                        <label for="email">E-mailadres</label>
                        <input type="email" id="email" name="email" required>
                    </div>
                    <div class="col s12 m6">
                        <label for="password">Wachtwoord</label>
                        <input type="password" id="password" name="password" required>
                    </div>
                    <div class="col s12 m6">
                        <label for="password_veri">Wachtwoord (verificatie )</label>
                        <input type="password" id="password_veri" name="password_veri" required>
                    </div>
                    <div class="col s12 m6">
                        <label for="min_income">Target in €</label>
                        <input type="number" id="min_income" name="min_income" step="any" required>
                    </div>
                    <div class="col s12 m6">
                        <label for="hourly_costs">Kost prijs in €</label>
                        <input type="number" id="hourly_costs" name="hourly_costs" step="any" required>
                    </div>
                    <div class="col s12">
                        <label for="description">Extra informatie</label>
                        <textarea id="descriptionHolder" class="ckEditor" cols="30" rows="10"></textarea>
                        <textarea name="description" id="description" cols="30" rows="10" class="hidden"></textarea>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <a href="#!" class="modal-close waves-effect waves-green btn-flat">Sluiten</a>
                <a onclick="submitCustomerForm()" class="waves-effect waves-green btn-flat white-text">Toevoegen</a>
            </div>
        </form>
    </div>
@endsection
@section('scripts')
    <script>
        $(document).ready(function () {
            CKEDITOR.replace('descriptionHolder');
        });

        function submitCustomerForm() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
            });

            $('#description').val(CKEDITOR.instances['descriptionHolder'].getData());

            $('#replaceCustomersTable').append('<div class="row"><div class="col s3"></div><div class="col s6 center-align"><div class="preloader-wrapper active"><div class="spinner-layer spinner-red-only"><div class="circle-clipper left"><div class="circle"></div></div><div class="gap-patch"><div class="circle"></div> </div><div class="circle-clipper right"> <div class="circle"></div></div></div></div></div><div class="col s3"></div></div>');
            $.post("/gebruikers/aanmaken", $("#createUsersForm").serialize()).done(function (data) {
                $('#replaceUsersTable').empty();
                $('#replaceUsersTable').append(data);
                $('.modal').modal('close');
                $('#createUsersForm').trigger("reset");
                CKEDITOR.instances['descriptionHolder'].setData('');
                Swal.fire(
                    'Gebruiker aangemaakt!',
                    'success'
                )
            });
        }

        function searchUsers() {
            let searchQuery = $('#searchQuery').val();

            $.get('/gebruikers/zoeken', {searchQuery: searchQuery}, function (data) {
                $('#replaceUsersTable').empty();
                $('#replaceUsersTable').append(data);
            });
        }
    </script>
@endsection
