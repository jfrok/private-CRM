<div class="row">
    <div class="col s12 m3">
        <div class="card">
            <div class="card-content">
                <div class="row">
                    <div class="col s12">
                        <div class="title left">
                            {{ $customer->company_name }}
                        </div>
                    </div>
                    <div class="col s12">
                        <br>
                        <table class="striped">
                            <thead class="bg-dark white-text">
                            <tr>
                                <td colspan="99">Klantgegevens</td>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td><b>Naam:</b></td>
                                <td>{{ $customer->company_name }}</td>
                            </tr>
                            <tr>
                                <td><b>Soort klant:</b></td>
                                @if($customer->is_company == true)
                                    <td>Bedrijf</td>
                                @else
                                    <td>Particulier</td>
                                @endif
                            </tr>
                            <tr>
                                <td><b>Aantal adressen:</b></td>
                                <td>{{ $customer->countAddresses() }}</td>
                            </tr>
                            <tr>
                                <td><b>Aantal contacten:</b></td>
                                <td>{{ $customer->countContacts() }}</td>
                            </tr>
                            <tr>
                                <td><b>Toegevoegd op:</b></td>
                                <td>{{ date('d-m-Y', strtotime($customer->created_at)) }}</td>
                            </tr>
                            </tbody>
                        </table>
                        <br>
                        @if($customer->hasMainLocation() == true)
                            <table class="striped">
                                <thead>
                                <tr class="bg-dark white-text">
                                    <td colspan="99">Hoofdlocatie</td>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td><b>Adres + nr:</b></td>
                                    <td>{{ $customer->getMainLocation()->address }}</td>
                                </tr>
                                <tr>
                                    <td><b>Postcode:</b></td>
                                    <td>{{ $customer->getMainLocation()->zip_code }}</td>
                                </tr>
                                <tr>
                                    <td><b>Plaats:</b></td>
                                    <td>{{ $customer->getMainLocation()->place }}</td>
                                </tr>
                                </tbody>
                            </table>
                        @endif
                        <br>
                        @if($customer->hasOwner() == true)
                            <table class="striped">
                                <thead>
                                <tr class="primaryBackground white-text">
                                    <td colspan="99">Eigenaar</td>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td><b>Adres + nr:</b></td>
                                    <td>{{ $customer->getOwner()->first_name . ' ' . $customer->getOwner()->last_name }}</td>
                                </tr>
                                <tr>
                                    <td><b>Postcode:</b></td>
                                    <td>{{ $customer->getMainLocation()->email }}</td>
                                </tr>
                                <tr>
                                    <td><b>Plaats:</b></td>
                                    <td>{{ $customer->getMainLocation()->phone }}</td>
                                </tr>
                                </tbody>
                            </table>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col s12 m9 right">
        <div class="card">
            <div class="card-content">
                <div class="card-title">
                    Belregister van {{ $customer->company_name }}
                </div>
                <div class="row">
                    <div class="col s12">
                        <table class="striped">
                            <thead class="bg-dark white-text">
                                <tr>
                                    <td>Gebeld</td>
                                    <td>Telefoon nr.</td>
                                    <td>Voor gebruiker</td>
                                    <td>Wanneer</td>
                                    <td>Afgerond</td>
                                </tr>
                            </thead>
                            <tbody>
                                @if($customer->calls->count() < 1)
                                    <tr>
                                        <td colspan="99" class="center">Geen belverzoeken geregistreerd...</td>
                                    </tr>
                                @else
                                    @foreach($customer->calls as $call)
                                        <tr onclick="$('#showCallDescription{{ $call->id }}').toggle()" class="hoverable clickable">
                                            <td>{{ $call->caller_name }}</td>
                                            <td><b>{{ $call->phone_number }}</b></td>
                                            <td>{{ $call->user->name }}</td>
                                            <td>{{ $call->getNiceDate() }} / {{ date('d-m-Y', strtotime($call->created_at)) }}</td>
                                            @if($call->deleted_at != null)
                                                <td><b class="green-text">Ja</b> op {{ date('d-m-Y', strtotime($call->deleted_at)) }}</td>
                                            @else
                                                <td><b class="red-text">Nee</b></td>
                                            @endif
                                        </tr>
                                        <tr>
                                            <td colspan="99" class="hidden grey" id="showCallDescription{{ $call->id }}">
                                                {!! $call->description !!}
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
{{--    <div class="col s12 m9 right">--}}
{{--        <div class="card">--}}
{{--            <div class="card-content">--}}
{{--                <div class="card-title">--}}
{{--                    Todo's voor klant {{ $customer->company_name }}--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}
</div>
