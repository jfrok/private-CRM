@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col s12 m9">
            <div class="card">
                <div class="card-content">
                    <div class="title left">
                        Projecten
                    </div>
                    <a href="#createProjectModal" class="btn btn-floating orange right tooltipped modal-trigger" data-tooltip="Project aanmaken" data-position="left"><i class="material-icons">add</i></a>
                    <br><br><br>
                    @include('projects.ajax.projectsTable')
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
                        <div class="col s12">
                            <label for="search">Zoeken</label>
                            <input type="text" name="search_query" id="searchQuery" onkeyup="searchProjects()">
                        </div>
                        <div class="col s12">
                            <label for="status">Status</label>
                            <select name="search_status" id="searchStatus" onchange="searchProjects()">
                                <option value="Alle statussen">Alle statussen</option>
                                <option value="Open" selected>Open</option>
                                <option value="Afgerond">Afgerond</option>
                                <option value="Geannuleerd">Geannuleerd</option>
                            </select>
                        </div>

                    </div>
                </div>
            </div>

            {{-- Overview of all open year invoices --}}
            <a href="javascript:void(0)" onclick="yearlyInvoices()" class="btn-flat waves-effect waves-light orange white-text center-align fullWidth"><i class="material-icons left">file_download</i> Jaarfacturen overzicht</a>
            <a href="javascript:void(0);" onclick="weFactSync()" class="btn-flat waves-effect waves-light orange white-text center-align fullWidth mt-10"><i class="material-icons left">sync</i> WeFact sync.</a>
        </div>
    </div>

    <!-- CreateProjectModal Structure -->
    <div id="createProjectModal" class="modal modal-fixed-footer roundedModal">
        <form id="createProjectForm" method="post" enctype="multipart/form-data">
            @csrf
            <div class="modal-content">
                <div class="row">
                    <div class="col s12">
                        <div class="title left">
                            Project aanmaken
                        </div>
                    </div>
                    <div class="col s12">
                        <label for="customer_id">Selecteer een klant</label>
                        <select name="customer_id" id="customer_id" class="browser-default" required>
                            <option value="" selected disabled>Selecteer een klant...</option>
                            @foreach($customers as $customer)
                                <option value="{{ $customer->id }}">{{ $customer->company_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col s12">
                        <label for="user_id">Selecteer een projectleider</label>
                        <select name="user_id" id="user_id" required>
                            <option value="" selected disabled>Selecteer een projectleider...</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col s12">
                        <label for="title">Projectnaam</label>
                        <input type="text" name="title" id="title" required>
                    </div>
                    <div class="col s12 m8">
                        <label for="description">Omschrijving</label>
                        <textarea name="descriptionHolder" id="descriptionHolder"></textarea>
                        <textarea name="description" id="description" hidden></textarea>
                    </div>
                    <div class="col s12 m4">
                        <label for="set_price">Prijs per uur</label>
                        <input type="number" step="any" name="set_price" id="set_price" required value="75.00">
                    </div>
                    <div class="col s12 m4">
                        <label for="set_price">Afgesproken uren</label>
                        <input type="number" step="any" name="set_hours" id="set_hours" required>
                    </div>
                    <div class="col s12 m4">
                        <p>
                            <label>
                                <input type="checkbox" class="filled-in" name="include_count" checked="checked"/>
                                <span>Project mee rekenen</span>
                            </label>
                        </p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <a href="#!" class="modal-close waves-effect waves-green btn-flat">Sluiten</a>
                <a onclick="submitProjectForm()" class="waves-effect waves-green btn-flat white-text">Toevoegen</a>
            </div>
        </form>
    </div>
@endsection
@section('scripts')
    <script>
        $(document).ready(function () {
            CKEDITOR.replace('descriptionHolder');
            $('#customer_id').select2({
                'dropdownParent': $('#createProjectModal'),
                language: {
                    "noResults": function () {
                        return "Geen resultaten gevonden...";
                    }
                }
            });
        });

        function submitProjectForm() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $('#description').val(CKEDITOR.instances['descriptionHolder'].getData());

            $('#replaceProjectsTable').append('<div class="row"><div class="col s3"></div><div class="col s6 center-align"><div class="preloader-wrapper active"><div class="spinner-layer spinner-red-only"><div class="circle-clipper left"><div class="circle"></div></div><div class="gap-patch"><div class="circle"></div> </div><div class="circle-clipper right"> <div class="circle"></div></div></div></div></div><div class="col s3"></div></div>');
            $.post("/projecten/aanmaken", $("#createProjectForm").serialize()).done(function (data) {
                $('#replaceProjectsTable').empty();
                $('#replaceProjectsTable').append(data);
                $('.modal').modal('close');

                $('#createProjectForm').trigger("reset");
                $('#customer_id').select2({
                    'dropdownParent': $('#createProjectModal'),
                    language: {
                        "noResults": function () {
                            return "Geen resultaten gevonden...";
                        }
                    }
                });
                CKEDITOR.instances['descriptionHolder'].setData('');
                Swal.fire({
                    icon: 'success',
                    title: 'Project aangemaakt!',
                    showDenyButton: true,
                    showCancelButton: false,
                    confirmButtonText: `Project bezoeken`,
                    confirmButtonColor: '#FA4D09',
                    denyButtonText: `Verder gaan`,
                    denyButtonColor: '#260089',
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.get('/projecten/laatst-aangemaakt', function (data) {
                            window.location.href = '/projecten/bekijken/' + data;
                        });
                    }
                })
            });
        }

        function searchProjects() {
            let searchQuery = $('#searchQuery').val();
            let searchStatus = $('#searchStatus').val();
            $.get('/projecten/zoeken', {searchQuery: searchQuery, searchStatus: searchStatus}, function (data) {
                $('#replaceProjectsTable').empty();
                $('#replaceProjectsTable').append(data);
            });
        }

        function yearlyInvoices() {
            Swal.fire({
                icon: "question",
                title: "Van welk jaar?",
                input: 'select',
                inputOptions: {
                    @foreach(range(2020, \Carbon\Carbon::now()->year) as $year)
                            '{{ $year }}': '{{ $year }}',
                    @endforeach
                },
                confirmButtonText: "Bekijken",
                showCancelButton: true,
                cancelButtonText: "Sluiten",
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "/projecten/jaarfacturen/overzicht/" + result.value;
                }
            });
        }

        function weFactSync() {
            Swal.fire({
                icon: "question",
                title: "WeFact sync.",
                text: "Wil je klanten & projecten uit WeFact importeren?",
                confirmButtonText: "Doorgaan",
                confirmButtonColor: "#FA4D09",
                showCancelButton: true,
                cancelButtonText: "Sluiten",
                footer: "Dit duurt ongeveer 1 minuut"
            }).then((result) => {
                if (result.isConfirmed) {
                    showPreloader();
                    window.location.href = "{{ route('wefact.import-customers-and-projects') }}";
                }
            });
        }
    </script>
@endsection
