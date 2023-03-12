<form id="editCustomerForm" method="post" enctype="multipart/form-data">
    @csrf
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
                            <div class="row">
                                <div class="col s2">
                                    <a onclick="deleteCustomer()" class="btn red white-text "><i class="material-icons">delete</i></a>
                                </div>
                                <div class="col s10">
                                    <a onclick="submitCustomerForm()" class="btn fullWidth green white-text">Wijzigingen
                                        opslaan</a>
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
                            <div class="title left">
                                Gegevens aanpassen
                            </div>
                        </div>
                        <div class="col s12 m9">
                            <label for="name">{{ __('Bedrijfsnaam / naam') }}</label>
                            <input id="name" type="text" name="name" value="{{ $customer->company_name }}" required>
                        </div>
                        <div class="col s12 m3 center">
                            <div class="switch customerSwitch">
                                <label>
                                    Particulier
                                    <input type="checkbox" @if($customer->is_company == true) checked
                                           @endif name="is_company">
                                    <span class="lever"></span>
                                    Bedrijf
                                </label>
                            </div>
                        </div>
                        <div class="col s12">
                            <label for="description">Extra informatie</label>
                            <textarea id="descriptionHolder" class="ckEditor" cols="30"
                                      rows="10">{!! $customer->description !!}</textarea>
                            <textarea name="description" id="description" cols="30" rows="10" class="hidden"></textarea>
                        </div>
                    </div>
                    <div>
                        <div class="switch">
                            <p><b>Onderhouds contract</b></p><br />
                            <label>
                                Nee
                                <input type="checkbox" @if($customer->has_onderhoud == true) checked @endif name="has_onderhoud">
                                <span class="lever"></span>
                                Ja
                            </label>
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
                            <div class="title left">
                                Addressen
                            </div>
                        </div>
                        <div class="col s12">
                            <br>
                            <table class="striped">
                                <thead class="bg-dark white-text">
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
                                        <input type="hidden" name="address_id[]" value="0">
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
                                           class="btn btn-floating orange addressDeleteButton"><i
                                                class="material-icons">delete</i></a>
                                        <a onclick="cloneAddress()" class="btn btn-floating blue"><i
                                                class="material-icons">add</i></a>
                                    </td>
                                </tr>
                                @if($customer->addresses->count() < 1)
                                    <tr id="cloneAddress">
                                        <td>
                                            <input type="hidden" name="address_id[]" value="0">
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
                                               class="btn btn-floating orange addressDeleteButton"><i
                                                    class="material-icons">delete</i></a>
                                            <a onclick="cloneAddress()" class="btn btn-floating blue"><i
                                                    class="material-icons">add</i></a>
                                        </td>
                                    </tr>
                                @else
                                    @foreach($customer->addresses as $address)
                                        <tr>
                                            <td>
                                                <input type="hidden" name="address_id[]" value="{{ $address->id }}">
                                                <select name="address_status[]" id="addressStatus">
                                                    <option @if($address->status == 'Hoofdlocatie') selected
                                                            @endif value="Hoofdlocatie">Hoofdlocatie
                                                    </option>
                                                    <option @if($address->status == 'Afleveradres') selected
                                                            @endif value="Afleveradres">Afleveradres
                                                    </option>
                                                    <option @if($address->status == 'Factuuradres') selected
                                                            @endif value="Factuuradres">Factuuradres
                                                    </option>
                                                </select>
                                            </td>
                                            <td><input type="text" name="address_address[]"
                                                       value="{{ $address->address }}"></td>
                                            <td><input type="text" name="address_zip_code[]"
                                                       value="{{ $address->zip_code }}"></td>
                                            <td><input type="text" name="address_place[]" value="{{ $address->place }}">
                                            </td>
                                            <td>
                                                <a onclick="deleteAddress('{{ $address->id }}', $(this));"
                                                   class="btn btn-floating orange addressDeleteButton"><i
                                                        class="material-icons">delete</i></a>
                                                <a onclick="cloneAddress()" class="btn btn-floating blue"><i
                                                        class="material-icons">add</i></a>
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
        <div class="col s12 m9 right">
            <div class="card">
                <div class="card-content">
                    <div class="row">
                        <div class="col s12">
                            <div class="title left">
                                Contactpersonen
                            </div>
                        </div>
                        <div class="col s12">
                            <br>
                            <table class="striped">
                                <thead class=" bg-dark white-text">
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
                                        <input type="hidden" name="contact_id[]" value="0">
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
                                           class="btn btn-floating orange contactDeleteButton"><i
                                                class="material-icons">delete</i></a>
                                        <a onclick="cloneContact()" class="btn btn-floating blue"><i
                                                class="material-icons">add</i></a>
                                    </td>
                                </tr>
                                @if($customer->contacts->count() == 0)
                                    <tr id="cloneContact">
                                        <td>
                                            <input type="hidden" name="contact_id[]" value="0">
                                            <select name="contact_function[]" id="contactFunction"
                                                    class="browser-default">
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
                                               class="btn btn-floating contactDeleteButton orange"><i
                                                    class="material-icons">delete</i></a>
                                            <a onclick="cloneContact()" class="btn btn-floating blue"><i
                                                    class="material-icons">add</i></a>
                                        </td>
                                    </tr>
                                @else
                                    @foreach($customer->contacts as $contact)
                                        <tr id="cloneContact">
                                            <td>
                                                <input type="hidden" name="contact_id[]" value="{{ $contact->id }}">
                                                <select name="contact_function[]" id="contactFunction">
                                                    <option @if($contact->function == 'Contactpersoon') selected
                                                            @endif value="Contactpersoon">Contactpersoon
                                                    </option>
                                                    <option @if($contact->function == 'Medewerker') selected
                                                            @endif value="Medewerker">Medewerker
                                                    </option>
                                                    <option @if($contact->function == 'Eigenaar') selected
                                                            @endif value="Eigenaar">Eigenaar
                                                    </option>
                                                    <option @if($contact->function == 'Facturatie') selected
                                                            @endif value="Facturatie">Facturatie
                                                    </option>
                                                </select>
                                            </td>
                                            <td><input type="text" name="contact_first_name[]"
                                                       value="{{ $contact->first_name }}"></td>
                                            <td><input type="text" name="contact_last_name[]"
                                                       value="{{ $contact->last_name }}"></td>
                                            <td><input type="text" name="contact_email[]" value="{{ $contact->email }}">
                                            </td>
                                            <td><input type="text" name="contact_phone[]" value="{{ $contact->phone }}">
                                            </td>
                                            <td>
                                                <a onclick="deleteContact('{{ $contact->id }}', $(this))"
                                                   class="btn btn-floating orange contactDeleteButton"><i
                                                        class="material-icons">delete</i></a>
                                                <a onclick="cloneContact()" class="btn btn-floating blue"><i
                                                        class="material-icons">add</i></a>
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
    </div>
</form>
