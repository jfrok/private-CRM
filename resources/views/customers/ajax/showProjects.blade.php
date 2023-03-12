@php($projects = $customer->projects)
<div class="row">
    <div class="col s12">
        <div class="card">
            <div class="card-content">
                <div class="row">
                    <div class="col s12">
                        <div class="title left">
                            Projecten van {{ $customer->company_name }}
                        </div>
                    </div>
                    <div class="col s12">
                        <br>
                        @include('projects.ajax.projectsTable')
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
