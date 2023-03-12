<form id="editUserForm" method="post" enctype="multipart/form-data">
    @csrf
    <div class="row">
        <div class="col s12 m3">
            <div class="card">
                <div class="card-content">
                    <div class="row">
                        <div class="col s12">
                            <div class="title center">
                                {{ $user->name }}
                            </div>
                        </div>
                        <div class="col s12">
                            <br>
                            <table class="striped">
                                <thead>
                                <tr>
                                    <td colspan="99" class="center">
                                        <input type="file" name="profile_image" id="profileImage" hidden/>
                                        <img src="{{ $user->getProfileImage() }}" id="imageClick" class="circle userImage bigger hoverable clickable" onclick="$('#profileImage').trigger('click');">
                                    </td>
                                </tr>
                                </thead>
                                <thead class="bg-dark white-text">
                                    <tr>
                                        <td colspan="99">Gebruikergegevens</td>
                                    </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td><b>Naam:</b></td>
                                    <td>{{ $user->name }}</td>
                                </tr>
                                <tr>
                                    <td><b>Aantal projecten:</b></td>
                                    <td>{{ $user->countProjects() }}</td>
                                </tr>
                                <tr>
                                    <td><b>Laatst online:</b></td>
                                    <td>
                                        @if($user->getNiceDate($user->last_login) == '01 januari 1970')
                                            Nog niet ingelogd...
                                        @else
                                            {{ $user->getNiceDate($user->last_login) }}
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td><b>Toegevoegd op:</b></td>
                                    <td>{{ $user->getNiceDate($user->created_at) }}</td>
                                </tr>
                                </tbody>
                            </table>
                            <br>
                            <div class="row">
                                <div class="col s2">
                                    <a onclick="deleteUser()" class="btn red white-text "><i class="material-icons">delete</i></a>
                                </div>
                                <div class="col s10">
                                    <a onclick="submitUserForm()" class="btn fullWidth green white-text">Wijzigingen opslaan</a>
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
                        <div class="col s12">
                            <label for="name">{{ __('Naam') }}</label>
                            <input id="name" type="text" name="name" value="{{ $user->name }}" required>
                        </div>
                        <div class="col s12">
                            <label for="email">E-mailadres</label>
                            <input type="email" id="email" name="email" value="{{ $user->email }}" required>
                        </div>
                        <div class="col s12 m6">
                            <label for="password">Wachtwoord</label>
                            <input type="password" id="password" name="password" required>
                        </div>
                        <div class="col s12 m6">
                            <label for="password_veri">Wachtwoord (verificatie)</label>
                            <input type="password" id="password_veri" name="password_veri" required>
                        </div>
                        <div class="col s12 m2">
                            <label for="min_income">Target in €</label>
                            <input type="number" id="min_income" name="min_income" value="{{ $user->min_income }}" step="any" required>
                        </div>
                        <div class="col s12 m2">
                            <label for="hourly_costs">Kost prijs in €</label>
                            <input type="number" id="hourly_costs" name="hourly_costs" value="{{ $user->hourly_costs }}" step="any" required>
                        </div>
                        <div class="col s12 m2">
                            <label for="hourly_costs">Project prijs per uur in €</label>
                            <input type="number" id="hourly_costs" name="project_cost" value="{{ $user->project_cost }}" step="any" required>
                        </div>
                        <div class="col s12 m2">
                            <label for="hourly_costs">Werkuren per dag</label>
                            <input type="number" id="hourly_costs" name="hours_a_dag" value="{{ $user->hours_a_dag }}" step="any" required>
                        </div>
                        <div class="col s12 m2">
                            <label for="hourly_costs">Aantal dagen</label>
                            <input type="number" id="hourly_costs" name="aantal_dagen" value="{{ $user->aantal_dagen }}" step="any" required>
                        </div>
                        <div class="col s12 m2">
                            <label for="hourly_costs">Werkuren per week</label>
                            <input type="number" id="hourly_costs" name="hours_a_week" value="{{ $user->hours_a_week }}" step="any" required>
                        </div>
                        <div class="col s12 m12">
                            <label for="user_color">Kleur</label>
                            <input type="color" id="user_color" name="user_color" value="{{ $user->color }}" required style="width:100%; margin-top:10px;">
                        </div>
                        <div class="col s12">
                            <label for="description">Extra informatie</label>
                            <textarea id="descriptionHolder" class="ckEditor" cols="30" rows="10">{!! $user->description !!}</textarea>
                            <textarea name="description" id="description" cols="30" rows="10" class="hidden"></textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
