<div id="replaceCallList">
    <div class="row">
        <div class="col s12">
            @if(isset($chosenUser))
                @if($chosenUser->calls->count() < 1)
                    <div class="center">
                        <b>Geen belnotities voor deze gebruiker...</b>
                    </div>
                @else
                    <br>
                    <table class="striped styledTable">
                        <tbody>
                        @foreach($chosenUser->calls->sortBy('created_at') as $call)
                            <tr onclick="showDetailCall('{{ $call->id }}')" id="call{{$call->id}}"
                                class="hoverable clickable">
                                <td>{{ ($call->customer ? $call->customer->company_name : "-") }}
                                    ({{ $call->caller_name }})
                                </td>
                                <td><b>{{ $call->phone_number }}</b></td>
                                <td>{{ $call->getNiceDate() }}</td>
                            </tr>
                            <tr class="hiddenClass detailCalls" id="detailCall{{ $call->id }}">
                                <td colspan="99">
                                    <table class="striped styledTable">
                                        <tr class="grey">
                                            <td>
                                                <a onclick="deleteCall('{{ $call->id }}')" class="right"><i
                                                        class="material-icons orange-text clickable">done</i></a>
                                                <b>Omschrijving</b>:
                                                {!! $call->description !!}
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                @endif
            @else
                <p>Er is iets mis gegaan...</p>
            @endif
        </div>
    </div>

    <!-- Create call modal -->
    <div id="createCallModal" class="modal modal-fixed-footer">
        <form method="post" id="createCallForm">
            @csrf
            <div class="modal-content">
                <div class="row">
                    <div class="col s12">
                        <h4>Belnotitie aanmaken</h4>
                    </div>
                    <div class="col s12 m5">
                        <div class="row">
                            <div class="col s12 mb-5">
                                <small>Telefoon nr.</small>
                                <input type="text" name="phone_number" id="phoneNumber" required>
                            </div>
                            <div class="col s12 mb-5">
                                <small>Bedrijfsnaam</small>
                                <select name="customer_id_or_name" id="customerSelectCalls" class="browser-default">
                                    <option disabled selected>Zoek of maak een klant...</option>
                                    @foreach($customers as $customer)
                                        <option value="{{ $customer->id }}">{{ $customer->company_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col s12 mb-5">
                                <small>Naam van persoon</small>
                                <input type="text" name="customer_name">
                            </div>
                            <div class="col s12 mb-5">
                                <small>Voor gebruiker</small>
                                <select name="user_id" id="userSelectCalls" class="browser-default">
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}" @if($user->id == 2) selected @endif>{{ $user->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col s12 m7">
                        <div class="row">
                            <div class="col s12">
                                <small>Omschrijving</small>
                                <textarea name="descriptionHolder" class="height200" cols="30" rows="10">Gemaakt door ({{ Illuminate\Support\Facades\Auth::user()->name }})</textarea>
                                <textarea name="description" id="description" class="hiddenClass" cols="30"
                                          rows="10"></textarea>
                            </div>
                            <div class="col s12 mt-20">
                                <p>
                                    <label>
                                        <input type="checkbox" class="filled-in" name="notification"
                                               onchange="checkCallNote($(this))"/>
                                        <span>Notificatie ontvangen?</span>
                                    </label>
                                </p>
                            </div>
                            <div class="col s12 m6 hiddenCol notificationTimes">
                                <small>Wanneer?</small>
                                <input type="date" name="notification_date"
                                       value="{{ Carbon\Carbon::today('Europe/Amsterdam')->format('Y-m-d') }}">
                            </div>
                            <div class="col s12 m6 hiddenCol notificationTimes">
                                <small>Hoe laat?</small>
                                <input type="time" name="notifcation_time"
                                       value="{{ \Carbon\Carbon::now('Europe/Amsterdam')->addHours(2)->format('H:i') }}">
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <a href="#!" class="modal-close waves-effect waves-green btn-flat">Sluiten</a>
                <a onclick="createCallForm()"
                   class="modal-close waves-effect waves-green btn-flat white-text">Aanmaken</a>
            </div>
        </form>
    </div>
</div>
