<form method="post" action="{{ route('keysoftware.edit.makelaar', $customer->id) }}">
    @csrf
    <div class="row">
        <div class="col s12 m3">
            <div class="card">
                <div class="card-content">
                    <div class="row">
                        <div class="col s12">
                            <div class="title left">
                                {{ ucfirst($customer->name) }}
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
                                    <td>{{ ucfirst($customer->company_name) }}</td>
                                </tr>
                                <tr>
                                    <td><b>Adres</b></td>
                                    <td>{{ $customer->fullAddress() }}</td>
                                </tr>
                                <tr>
                                    <td><b>Email</b></td>
                                    <td>{{ $customer->company_email }}</td>
                                </tr>
                                <tr>
                                    <td><b>Api Calls Deze Maand</b></td>
                                    <td>{{ $customer->apiCallsThisMonth() ? $customer->apiCallsThisMonth() : 0 }}</td>
                                </tr>
                                <tr>
                                    <td><b>Toegevoegd op:</b></td>
                                    <td>{{ date('d-m-Y', strtotime($customer->created_at)) }}</td>
                                </tr>
                                </tbody>
                            </table>
                            <br>
                            <div class="row">
                                <div class="col s2">
                                    <a onclick="deleteCustomer('{{ $customer->id }}')" class="btn red white-text "><i class="material-icons">delete</i></a>
                                </div>
                                <div class="col s10">
                                    <button type="submit" class="btn fullWidth green white-text">Wijzigingen opslaan</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col s12 m9 right">
            <div class="card">
                <div class="card-content">
                    <div class="row">
                        <div class="col s12">
                            <div class="col s12 mt-20">
                                <span class="subtitle">Makelaar gegevens</span>
                            </div>
                            <div class="col s8">
                                <label for="company_name">{{ __('Bedrijfsnaam / naam') }}</label>
                                <input id="company_name" type="text" name="company_name" onchange="generateWebsiteUrl()" value="{{ $customer->company_name }}" required>
                            </div>
                            <div class="col s4">
                                <label for="start_date">{{ __('Start Datum') }}</label>
                                <input id="start_date" type="date" name="start_date" value="{{ $customer->start_date }}" required>
                            </div>
                            <div class="col s6">
                                <label for="company_phone">{{ __('Telefoon / mobiele nummer') }}</label>
                                <input id="company_phone" type="text" name="company_phone" value="{{ $customer->company_phone }}" required>
                            </div>
                            <div class="col s6">
                                <label for="company_email">{{ __('E-mailadres') }}</label>
                                <input id="company_email" type="text" name="company_email" value="{{ $customer->company_email }}" required>
                            </div>
                        </div>
                        <div class="col s12 mt-20">
                            <div class="col s12">
                                <span class="subtitle">Adres gegevens</span>
                            </div>
                            <div class="col s6">
                                <label for="company_street_name">{{ __('Straatnaam') }}</label>
                                <input id="company_street_name" type="text" name="company_street_name" value="{{ $customer->company_street }}" required>
                            </div>
                            <div class="col s6">
                                <label for="company_zipcode">{{ __('Postcode') }}</label>
                                <input id="company_zipcode" type="text" name="company_zipcode" value="{{ $customer->company_zipcode }}" required>
                            </div>
                            <div class="col s4">
                                <label for="company_number">{{ __('Huisnummer') }}</label>
                                <input id="company_number" type="text" name="company_number" value="{{ $customer->company_number }}" required>
                            </div>
                            <div class="col s4">
                                <label for="company_place">{{ __('Plaats') }}</label>
                                <input id="company_place" type="text" name="company_place" value="{{ $customer->company_place }}" required>
                            </div>
                            <div class="col s4">
                                <label for="company_province">{{ __('Provincie') }}</label>
                                <input id="company_province" type="text" name="company_province" value="{{ $customer->company_province }}" required>
                            </div>
                        </div>
                        <div class="col s12 mt-20">
                            <div class="col s12">
                                <span class="subtitle">Website gegevens</span>
                            </div>
                            <div class="col s12">
                                <label for="company_website">{{ __('Website Url') }}</label>
                                <input id="company_website" type="text" name="company_website" value="{{ $customer->company_website }}" required>
                            </div>
                            <div class="col s10">
                                <label for="">{{ __('API Token') }}</label>
                                <input id="api_token" type="text" name="api_token" value="{{ $customer->api_token }}" readonly required>
                            </div>
                            <div class="col s2">
                                <a class="btn mt-25" onclick="generateApiToken()"><i class="material-icons">autorenew</i></a>
                                <a class="btn mt-25" onclick="copyKeysoftwareApiToken()"><i class="material-icons">content_copy</i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
