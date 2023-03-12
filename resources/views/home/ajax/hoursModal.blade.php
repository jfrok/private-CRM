<div id="replaceHoursModal">


    @csrf

    <div class="modal-content">

        <div class="row">

            <div class="col s12">

                <h4>Uren invullen</h4>

            </div>

            <div class="col s12">

{{--                <table>--}}

{{--                    <thead class="bg-dark white-text">--}}

{{--                    <tr>--}}

{{--                        <td>Project</td>--}}

{{--                        <td>Klant</td>--}}

{{--                        <td>To-do's</td>--}}

{{--                        <td>Van</td>--}}

{{--                        <td>Tot</td>--}}

{{--                        <td></td>--}}

{{--                    </tr>--}}

{{--                    </thead>--}}

{{--                    <tbody>--}}

{{--                    @if($chosenUser->finishedTodoProjects()->count() < 1)--}}

{{--                        <tr>--}}

{{--                            <td colspan="99" class="center">Geen projecten gevonden voor vandaag...</td>--}}

{{--                        </tr>--}}

{{--                    @else--}}

{{--                        @foreach($chosenUser->finishedTodoProjects() as $proj)--}}

{{--                            <tr>--}}

{{--                                <td>{{ $proj->title }}</td>--}}

{{--                                <td>{{ $proj->customer->company_name }}</td>--}}

{{--                                <td onclick="$(this).parent().parent().find('#hiddenTodoHolder{{ $proj->id }}').toggle();">--}}
{{--                                        <span class="badge new orange"--}}
{{--                                              data-badge-caption="to-do's">{{ $chosenUser->countFinishedTodosBasedOnProject($proj->id)  }}</span>--}}
{{--                                </td>--}}

{{--                                <td class="fromTimeHolder"><input type="time" class="fromTime"></td>--}}

{{--                                <td class="toTimeHolder"><input type="time" class="toTime"></td>--}}

{{--                                <td class="center"><a--}}
{{--                                        onclick="saveHours('{{ $proj->id }}', $(this).parent().parent())"--}}
{{--                                        class="btn-floating orange"><i class="material-icons">save</i></a></td>--}}

{{--                            </tr>--}}

{{--                            <tr class="hiddenTodoHolder grey" id="hiddenTodoHolder{{$proj->id}}">--}}

{{--                                <td colspan="99">--}}

{{--                                    @php($count = 1)--}}

{{--                                    @foreach($chosenUser->finishedTodosBasedOnProject($proj->id) as $todo)--}}

{{--                                        {!! $todo->description !!}--}}

{{--                                    @endforeach--}}

{{--                                </td>--}}

{{--                            </tr>--}}

{{--                        @endforeach--}}

{{--                    @endif--}}

{{--                    </tbody>--}}

{{--                </table>--}}

                <table>

                    <thead class="bg-dark white-text">

                    <tr>

                        <td colspan="99">Losse uren invullen</td>

                    </tr>

                    </thead>

                    <tbody>

                    <tr class="customHolder">

                        <td class="projectHolder">

                            <select name="custom_project_id" id="customProjectId" class="browser-default">

                                @foreach(\App\Models\Project::all()->orderByDesc('id') as $project)

                                    <option
                                        value="{{ $project->id }}">{{ ($project->customer ? $project->customer->company_name . " | ": "") . $project->title }}</option>

                                @endforeach

                            </select>

                        </td>

                        <td class="fromTimeHolder"><input type="time" class="fromTime"></td>

                        <td class="toTimeHolder"><input type="time" class="toTime"></td>

                        <td class="dateHolder"><input type="date" class="date"
                                                      value="{{ \Carbon\Carbon::today('Europe/Amsterdam')->format('Y-m-d') }}">
                        </td>

                        <td class="statusHolder">

                            <select name="status" id="status" class="browser-default">

                                <option value="Declarabel">Declarabel</option>

                                <option value="Niet Declarabel">Niet declarabel</option>

                                <option value="Jaarfactuur">Jaarfactuur</option>

                                {{--                                        <option value="Niet meetellen">Niet meetellen</option>--}}

                            </select>

                        </td>

                        <td class="center"><a onclick="saveCustomHours($(this).parent().parent().parent())"
                                              class="btn-floating orange"><i class="material-icons">save</i></a>
                        </td>

                    </tr>

                    <tr class="descHolder">

                        <td colspan="99" class="descriptionHolder">

                                <textarea name="customDescription" id="customDescription" cols="30"
                                          rows="10"></textarea>

                        </td>

                    </tr>

                    </tbody>

                    <table>

                        <thead class="bg-dark white-text">

                        <tr>

                            <td colspan="99">Eenmalige bedragen.</td>

                        </tr>

                        </thead>

                        <tbody>

                        <tr class="customHolder">
                            <form action="{{ route('home.eenmalig') }}" method="post">
                                @csrf
                                <td class="projectHolder">

                                    <label>Bedrijf / Klant</label>
                                    <select name="bedrijf" id="customProjectIdEenmalig" class="browser-default">

                                        @foreach(\App\Models\Project::all() as $project)

                                            <option
                                                value="{{ ($project->customer ? $project->customer->company_name . " | ": "") . $project->title }}">{{ ($project->customer ? $project->customer->company_name . " | ": "") . $project->title }}
                                            </option>

                                        @endforeach

                                    </select>

                                </td>

                                <td class="dateHolder">
                                    <label>Datum</label>
                                    <input type="date" class="bate" name="datum"
                                           value="{{ \Carbon\Carbon::today('Europe/Amsterdam')->format('Y-m-d') }}"
                                           required>
                                </td>

                                <td class="prijsHolder">
                                    <label>Bedrag</label>
                                    <input type="number" name="prijs" required>
                                </td>

                                <td class="center">
                                    <button class="btn-floating orange">
                                        <i class="material-icons">save</i>
                                    </button>
                                </td>

                                <input type="hidden" name="user_id" value="{{ $chosenUser->id }}">

                            </form>

                        </tr>


                        </tbody>

                    </table>

            </div>

        </div>

    </div>

    <div class="bg-dark modal-footer">

        <a href="#!" class="modal-close waves-effect waves-green btn-flat">Sluiten</a>

    </div>

</div>
