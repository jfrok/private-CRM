@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col s12 m9">
            <div class="card">
                <div class="card-content">
                    <div class="title left">
                        Klanten
                    </div>
                    <a href="#createCustomerModal" class="btn btn-floating orange right tooltipped modal-trigger"
                       data-tooltip="Klant aanmaken" data-position="left"><i class="material-icons">add</i></a>
                    <br><br><br>
                    @include('customers.ajax.customersTable')
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
                        <div class="col s12">
                            <p>
                                <label>
                                    <input type="checkbox" id="only_company" onchange="searchCustomers()"
                                           class="filled-in"/>
                                    <span>Alleen bedrijven</span>
                                </label>
                            </p>
                        </div>
                        <div class="col s12">
                            <p>
                                <label>
                                    <input type="checkbox" id="only_private" onchange="searchCustomers()"
                                           class="filled-in"/>
                                    <span>Alleen particulieren</span>
                                </label>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- CreateCustomerModal Structure -->
    <div id="createCustomerModal" class="modal modal-fixed-footer roundedModal">
        <form id="createCustomerForm" method="post" enctype="multipart/form-data">
            @csrf
            <div class="modal-content">
                <div class="row">
                    <div class="col s12">
                        <span class="subtitle">Klant gegevens</span>
                    </div>
                    <div class="col s12 m9">
                        <label for="name">{{ __('Bedrijfsnaam / naam') }}</label>
                        <input id="name" type="text" name="name" required>
                    </div>
                    <div class="col s12 m3 center">
                        <div class="switch customerSwitch">
                            <label>
                                Particulier
                                <input type="checkbox" checked name="is_company">
                                <span class="lever"></span>
                                Bedrijf
                            </label>
                        </div>
                    </div>
                    <div class="col s12">
                        <span class="subtitle">Adressen toevoegen</span>
                        <table class="striped">
                            <thead class="blue white-text">
                            <tr>
                                <td>Status</td>
                                <td>Adres + nr.</td>
                                <td>Postcode</td>
                                <td>Plaats</td>
                                <td></td>
                            </tr>
                            </thead>
                            <tbody id="cloneAddressesHere">
                            <tr id="cloneAddress" class="hidden">
                                <td>
                                    <select name="address_status[]" id="addressStatus" class="browser-default">
                                        <option value="Hoofdlocatie">Hoofdlocatie</option>
                                        <option value="Afleveradres">Afleveradres</option>
                                        <option value="Factuuradres">Factuuradres</option>
                                    </select>
                                </td>
                                <td><input type="text" name="address_address[]"></td>
                                <td><input type="text" name="address_zip_code[]"></td>
                                <td><input type="text" name="address_place[]"></td>
                                <td>
                                    <a onclick="$(this).parent().parent().remove(); checkButtons()"
                                       class="btn btn-floating addressDeleteButton orange"><i class="material-icons">delete</i></a>
                                    <a onclick="cloneAddress()" class="btn btn-floating blue"><i class="material-icons">add</i></a>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <select name="address_status[]" id="addressStatus">
                                        <option value="Hoofdlocatie">Hoofdlocatie</option>
                                        <option value="Afleveradres">Afleveradres</option>
                                        <option value="Factuuradres">Factuuradres</option>
                                    </select>
                                </td>
                                <td><input type="text" name="address_address[]"></td>
                                <td><input type="text" name="address_zip_code[]"></td>
                                <td><input type="text" name="address_place[]"></td>
                                <td>
                                    <a onclick="$(this).parent().parent().remove(); checkButtons()"
                                       class="btn btn-floating addressDeleteButton orange"><i class="material-icons">delete</i></a>
                                    <a onclick="cloneAddress()" class="btn btn-floating blue"><i class="material-icons">add</i></a>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="col s12">
                        <span class="subtitle">Contactpersonen toevoegen</span>
                        <table class="striped">
                            <thead class="blue white-text">
                            <tr>
                                <td>Functie</td>
                                <td>Voornaam</td>
                                <td>Achternaam</td>
                                <td>E-mail</td>
                                <td>Telefoon nr.</td>
                                <td></td>
                            </tr>
                            </thead>
                            <tbody id="cloneContactsHere">
                            <tr id="cloneContact" class="hidden">
                                <td>
                                    <select name="contact_function[]" id="contactFunction" class="browser-default">
                                        <option value="Contactpersoon">Contactpersoon</option>
                                        <option value="Medewerker">Medewerker</option>
                                        <option value="Eigenaar">Eigenaar</option>
                                        <option value="Facturatie">Facturatie</option>
                                    </select>
                                </td>
                                <td><input type="text" name="contact_first_name[]"></td>
                                <td><input type="text" name="contact_last_name[]"></td>
                                <td><input type="text" name="contact_email[]"></td>
                                <td><input type="text" name="contact_phone[]"></td>
                                <td>
                                    <a onclick="$(this).parent().parent().remove(); checkButtons()"
                                       class="btn btn-floating contactDeleteButton orange"><i class="material-icons">delete</i></a>
                                    <a onclick="cloneContact()" class="btn btn-floating blue"><i class="material-icons">add</i></a>
                                </td>
                            </tr>
                            <tr id="cloneContact">
                                <td>
                                    <select name="contact_function[]" id="contactFunction">
                                        <option value="Contactpersoon">Contactpersoon</option>
                                        <option value="Medewerker">Medewerker</option>
                                        <option value="Eigenaar">Eigenaar</option>
                                        <option value="Facturatie">Facturatie</option>
                                    </select>
                                </td>
                                <td><input type="text" name="contact_first_name[]"></td>
                                <td><input type="text" name="contact_last_name[]"></td>
                                <td><input type="text" name="contact_email[]"></td>
                                <td><input type="text" name="contact_phone[]"></td>
                                <td>
                                    <a onclick="$(this).parent().parent().remove(); checkButtons()"
                                       class="btn btn-floating contactDeleteButton orange"><i class="material-icons">delete</i></a>
                                    <a onclick="cloneContact()" class="btn btn-floating blue"><i class="material-icons">add</i></a>
                                </td>
                            </tr>
                            </tbody>
                        </table>
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
            checkButtons();
            CKEDITOR.replace('descriptionHolder');
        });

        function cloneAddress() {
            let clone = $('#cloneAddress').clone();
            clone.removeClass('hidden');
            let cloned = clone.appendTo('#cloneAddressesHere');
            cloned.find('select').removeClass('browser-default').formSelect();
            checkButtons()
        }

        function cloneContact() {
            let clone = $('#cloneContact').clone();
            clone.removeClass('hidden');
            let cloned = clone.appendTo('#cloneContactsHere');
            cloned.find('select').removeClass('browser-default').formSelect();
            checkButtons()
        }

        function checkButtons() {
            let addressButtons = $('.addressDeleteButton');
            if (addressButtons.length > 2) {
                addressButtons.show();
            } else {
                addressButtons.hide();
            }

            let contactButtons = $('.contactDeleteButton');
            if (contactButtons.length > 2) {
                contactButtons.show();
            } else {
                contactButtons.hide();
            }
        }

        function submitCustomerForm() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $('#description').val(CKEDITOR.instances['descriptionHolder'].getData());

            $('#replaceCustomersTable').append('<div class="row"><div class="col s3"></div><div class="col s6 center-align"><div class="preloader-wrapper active"><div class="spinner-layer spinner-red-only"><div class="circle-clipper left"><div class="circle"></div></div><div class="gap-patch"><div class="circle"></div> </div><div class="circle-clipper right"> <div class="circle"></div></div></div></div></div><div class="col s3"></div></div>');
            $.post("/klanten/aanmaken", $("#createCustomerForm").serialize()).done(function (data) {
                $('#replaceCustomersTable').empty();
                $('#replaceCustomersTable').append(data);
                $('.modal').modal('close');
                $('#createCustomerForm').trigger("reset");
                CKEDITOR.instances['descriptionHolder'].setData('');
                Swal.fire(
                    'Klant aangemaakt!',
                    'success'
                )
            });
        }

        function searchCustomers() {
            let searchQuery = $('#searchQuery').val();
            let onlyCompany;
            let onlyPrivate;
            if ($('#only_company').prop('checked') == true) {
                onlyCompany = true;
            } else {
                onlyCompany = false;
            }
            if ($('#only_private').prop('checked') == true) {
                onlyPrivate = true;
            } else {
                onlyPrivate = false;
            }

            $.get('/klanten/zoeken', {
                searchQuery: searchQuery,
                onlyCompany: onlyCompany,
                onlyPrivate: onlyPrivate
            }, function (data) {
                $('#replaceCustomersTable').empty();
                $('#replaceCustomersTable').append(data);
            });
        }
    </script>
@endsection
