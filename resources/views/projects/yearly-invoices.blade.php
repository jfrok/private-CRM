@extends('layouts.app')
@section('content')
    <div class="row mt-20">
        <div class="col s12">
            <h3 class="heading">Overzicht van openstaande jaarfacturen</h3>
        </div>

        <form id="yearlyInvoicesForm" action="{{ route('projects.save-yearly-invoices') }}" method="POST">
            @csrf
            @php($totalHours = 0)
            @php($totalPrice = 0)
            @foreach($customers as $c)
                <div class="col s12">
                    <table class="striped">
                        <thead class="blue white-text">
                        <tr>
                            <td colspan="5"><b>{{ $c->company_name }}</b>
                                <i id="hideProjectsBtn-{{ $c->id }}" class="material-icons right clickable displayNone" onclick="hideProjects('{{ $c->id }}')">visibility_off</i>
                                <i id="showProjectsBtn-{{ $c->id }}" class="material-icons right clickable" onclick="showProjects('{{ $c->id }}')">visibility</i>
                                <i class="material-icons right clickable" onclick="createYearlyInvoice('{{ $c->id }}')">receipt</i>
                            </td>
                        </tr>
                        </thead>
                        @foreach($c->getYearlyProjects($year) as $p)
                            <tbody id="customer-{{ $c->id }}" class="displayNone">
                            <tr>
                                <td width="5%"></td>
                                <td width="25%"><b>Project</b></td>
                                <td width="25%"><b>Uren</b></td>
                                <td width="25%"><b>Uurprijs</b></td>
                                <td width="20%"><b>Totaalprijs</b></td>
                            </tr>
                            @php($projectHours = 0)
                            @foreach($p->getYearlyWorkOrders($year) as $w)
                                @php($projectHours += $w->getTotalTime())
                                @php($totalHours += $w->getTotalTime())
                                @php($totalPrice += $w->getTotalPrice())
                                <tr onclick="openWorkOrderModal('{{ $w->id }}')" class="hoverable clickable">
                                    <td width="5%">
                                        <label>
                                            <input type="checkbox" class="filled-in hoursCheckbox" name="workOrders[]" value="{{ $w->id }}">
                                            <span></span>
                                        </label>
                                    </td>
                                    <td width="25%">{{ $p->title }}</td>
                                    <td width="25%">{{ $w->getTotalTime() }} uur</td>
                                    <td width="25%">€ {{ number_format($p->set_price, 2, ",", ".") }}</td>
                                    <td width="20%">€ {{ number_format($w->getTotalPrice(), 2, ",", ".") }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                            <tr>
                                <td width="70%" colspan="4" class="grey darken-2 white-text"><b>{{ $projectHours }} uur</b></td>
                                <td class="grey darken-2 white-text"><b>€ {{ $p->totalYearlyPrice($year) }}</b></td>
                            </tr>
                        @endforeach
                    </table>
                </div>
            @endforeach
        </form>

        <div class="col s12 mt-20">
            <h3 class="heading">Totalen {{ $year }}</h3>
            <h3 class="heading font-18">{{ $totalHours }} uur / € {{ number_format($totalPrice, 2, ",", ".") }}</h3>
        </div>
    </div>

    <div class="fixed-action-btn">
        <a href="javascript:void(0)" onclick="markAsDone()" class="btn-floating btn-large green tooltipped" data-tooltip="Markeren als declarabel" data-position="left">
            <i class="large material-icons white-text">check</i>
        </a>
    </div>

    <div id="viewHoursModal" class="modal modal-fixed-footer roundedModal">
        @include('home.ajax.viewHoursModal')
    </div>
@endsection

@section('scripts')
    <script>
        function hideProjects(customerId) {
            $('#customer-' + customerId).hide();
            $('#showProjectsBtn-' + customerId).show();
            $('#hideProjectsBtn-' + customerId).hide();
        }

        function showProjects(customerId) {
            $('#customer-' + customerId).show();
            $('#showProjectsBtn-' + customerId).hide();
            $('#hideProjectsBtn-' + customerId).show();
        }

        function createYearlyInvoice(customerId) {
            errorMessage(null, 'Deze functie moet nog gemaakt worden.');
        }

        function markAsDone() {
            let counter = 0;
            $('.hoursCheckbox').each(function () {
                if (this.checked)
                    counter++;
            });

            if (counter > 0) {
                Swal.fire({
                    icon: "question",
                    title: "Gefactureerd?",
                    text: "Zijn de geselecteerde uren gefactureerd?",
                    confirmButtonColor: "green",
                    confirmButtonText: "Ja!",
                    showCancelButton: true,
                    cancelButtonText: "Nee.."
                }).then((result) => {
                    if (result.isConfirmed) {
                        $('#yearlyInvoicesForm').submit();
                    }
                });
            } else {
                Swal.fire({
                    icon: "error",
                    title: "Nope..",
                    text: "Selecteer min. 1 item!",
                    confirmButtonText: "Oke",
                })
            }
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
    </script>
@endsection
