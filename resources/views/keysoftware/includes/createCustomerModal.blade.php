<div id="createCustomerModal" class="modal modal-fixed-footer roundedModal" style="height: 80%; min-height: 80%">
    <form method="post" action="{{ route('keysoftware.create.makelaar') }}">
        @csrf
        <div class="modal-content">
            <div class="row">
                <div class="col s12">
                    <div class="col s12 mt-20">
                        <span class="subtitle">Makelaar gegevens</span>
                    </div>
                    <div class="col s8">
                        <label for="company_name">{{ __('Bedrijfsnaam / naam') }}</label>
                        <input id="company_name" type="text" name="company_name" onchange="generateWebsiteUrl()" required>
                    </div>
                    <div class="col s4">
                        <label for="start_date">{{ __('Start Datum') }}</label>
                        <input id="start_date" type="date" name="start_date" required>
                    </div>
                    <div class="col s6">
                        <label for="company_phone">{{ __('Telefoon / mobiele nummer') }}</label>
                        <input id="company_phone" type="text" name="company_phone" required>
                    </div>
                    <div class="col s6">
                        <label for="company_email">{{ __('E-mailadres') }}</label>
                        <input id="company_email" type="text" name="company_email" required>
                    </div>
                </div>
                <div class="col s12 mt-20">
                    <div class="col s12">
                        <span class="subtitle">Adres gegevens</span>
                    </div>
                    <div class="col s6">
                        <label for="company_street_name">{{ __('Straatnaam') }}</label>
                        <input id="company_street_name" type="text" name="company_street_name" required>
                    </div>
                    <div class="col s6">
                        <label for="company_zipcode">{{ __('Postcode') }}</label>
                        <input id="company_zipcode" type="text" name="company_zipcode" required>
                    </div>
                    <div class="col s4">
                        <label for="company_number">{{ __('Huisnummer') }}</label>
                        <input id="company_number" type="text" name="company_number" required>
                    </div>
                    <div class="col s4">
                        <label for="company_place">{{ __('Plaats') }}</label>
                        <input id="company_place" type="text" name="company_place" required>
                    </div>
                    <div class="col s4">
                        <label for="company_province">{{ __('Provincie') }}</label>
                        <input id="company_province" type="text" name="company_province" required>
                    </div>
                </div>
                <div class="col s12 mt-20">
                    <div class="col s12">
                        <span class="subtitle">Website gegevens</span>
                    </div>
                    <div class="col s12">
                        <label for="company_">{{ __('Website Url') }}</label>
                        <input id="company_website" type="text" name="company_website" required>
                    </div>
                    <div class="col s10">
                        <label for="">{{ __('API Token') }}</label>
                        <input id="api_token" type="text" name="api_token" readonly required>
                    </div>
                    <div class="col s2">
                        <a class="btn mt-25" onclick="generateApiToken()"><i class="material-icons">autorenew</i></a>
                        <a class="btn mt-25" onclick="copyApiToken()"><i class="material-icons">content_copy</i></a>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <a class="modal-close waves-effect waves-green btn-flat">Sluiten</a>
            <button type="submit" class="waves-effect waves-green btn-flat white-text">Toevoegen</button>
        </div>
    </form>
</div>
