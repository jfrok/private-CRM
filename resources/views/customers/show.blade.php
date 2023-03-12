@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col s4 m2 actionButton" onclick="loadNewPage('customers.ajax.showMain')">
            <div class="card">
                <div class="card-content purple lighten-2 center white-text">
                    <i class="material-icons">info</i>
                    <br>
                    <span>Klant gegevens</span>
                </div>
            </div>
        </div>
        <div class="col s4 m2 actionButton" onclick="loadNewPage('customers.ajax.showCalls')">
            <div class="card">
                <div class="card-content yellow lighten-2 center black-text">
                    <i class="material-icons">contact_phone</i>
                    <br>
                    <span>Belletjes & to-do's</span>
                </div>
            </div>
        </div>
        <div class="col s4 m2 actionButton" onclick="loadNewPage('customers.ajax.showProjects')">
            <div class="card">
                <div class="card-content orange lighten-2 center white-text">
                    <i class="material-icons">assignment</i>
                    <br>
                    <span>Projecten</span>
                </div>
            </div>
        </div>
        {{--        <div class="col s4 m2 actionButton" onclick="loadNewPage('customers.ajax.showCalendar')">--}}
        <div class="col s4 m2 actionButton">
            <div class="card">
                <div class="card-content grey darken-2 center grey-text">
                    <i class="material-icons">event</i>
                    <br>
                    <span>Agenda</span>
                </div>
            </div>
        </div>
        {{--        <div class="col s4 m2 actionButton" onclick="loadNewPage('customers.ajax.showProducts')">--}}
        <div class="col s4 m2 actionButton">
            <div class="card">
                <div class="card-content grey darken-2 center grey-text">
                    <i class="material-icons">shopping_cart</i>
                    <br>
                    <span>Producten & abbo's</span>
                </div>
            </div>
        </div>
        {{--        <div class="col s4 m2 actionButton" onclick="loadNewPage('customers.ajax.showGraphs')">--}}
        <div class="col s4 m2 actionButton">
            <div class="card">
                <div class="card-content grey darken-2 center grey-text">
                    <i class="material-icons">insights</i>
                    <br>
                    <span>Financiële overzichten</span>
                </div>
            </div>
        </div>
    </div>
    <div id="pageContainer">
        @include('customers.ajax.showMain')
    </div>
@endsection
@section('scripts')
    <script>
        $(document).ready(function () {
            checkButtons();
            CKEDITOR.replace('descriptionHolder');
        });

        function loadNewPage(view) {
            $('#pageContainer').empty().append('<div class="row"><div class="col s3"></div><div class="col s6 center-align"><div class="preloader-wrapper active"><div class="spinner-layer spinner-red-only"><div class="circle-clipper left"><div class="circle"></div></div><div class="gap-patch"><div class="circle"></div> </div><div class="circle-clipper right"> <div class="circle"></div></div></div></div></div><div class="col s3"></div></div>');
            $.get('/klanten/bekijken/{{ $customer->id }}/pagina-laden/' + view, function (data) {
                $('#pageContainer').empty();
                $('#pageContainer').append(data);
                if (view == 'customers.ajax.showMain') {
                    checkButtons();
                    $('select').formSelect();
                    CKEDITOR.replace('descriptionHolder');
                } else if (view == 'customers.ajax.showMain') {
                    $('.tooltipped').tooltip();
                }
            });
        }

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

        function deleteCustomer() {
            Swal.fire({
                icon: 'warning',
                title: 'Klant verwijderen?',
                text: 'Weet je zeker dat je deze klant wilt verwijderen?',
                confirmButtonText: 'Verwijderen',
                confirmButtonColor: '#E53935',
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '{{ url('/klanten/verwijderen/'.$customer->id) }}'
                }
            })
        }

        function showAllAddresses() {
            $('#showAddresses').modal('open');
        }

        function deleteAddress(id, el) {
            Swal.fire({
                icon: 'warning',
                title: 'Adres verwijderen?',
                text: 'Weet je zeker dat je dit adres wilt verwijderen?',
                confirmButtonText: 'Verwijderen',
                confirmButtonColor: '#E53935',
            }).then((result) => {
                if (result.isConfirmed) {
                    $.get('/klanten/bekijken/{{ $customer->id }}/adressen/verwijderen' + '/' + id, function () {
                        $(el).parent().parent().remove();
                        checkButtons();
                    });
                }
            });
        }

        function deleteContact(id, el) {
            Swal.fire({
                icon: 'warning',
                title: 'Contactpersoon verwijderen?',
                text: 'Weet je zeker dat je dit contactpersoon wilt verwijderen?',
                confirmButtonText: 'Verwijderen',
                confirmButtonColor: '#E53935',
            }).then((result) => {
                if (result.isConfirmed) {
                    $.get('/klanten/bekijken/{{ $customer->id }}/contactpersonen/verwijderen' + '/' + id, function () {
                        $(el).parent().parent().remove();
                        checkButtons();
                    });
                }
            });
        }

        function submitCustomerForm() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $('#description').val(CKEDITOR.instances['descriptionHolder'].getData());

            $('#pageContainer').append('<div class="row"><div class="col s3"></div><div class="col s6 center-align"><div class="preloader-wrapper active"><div class="spinner-layer spinner-red-only"><div class="circle-clipper left"><div class="circle"></div></div><div class="gap-patch"><div class="circle"></div> </div><div class="circle-clipper right"> <div class="circle"></div></div></div></div></div><div class="col s3"></div></div>');
            $.post("/klanten/aanpassen/{{ $customer->id }}", $("#editCustomerForm").serialize()).done(function (data) {
                $('#pageContainer').empty();
                $('#pageContainer').append(data);
                checkButtons();
                $('select').formSelect();
                CKEDITOR.replace('descriptionHolder');
                Swal.fire({
                    icon: 'success',
                    title: 'Wijzigingen opgeslagen!',
                })
            });
        }
    </script>
@endsection
