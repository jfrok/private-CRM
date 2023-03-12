<div id="replaceViewHoursModal">
    @if(isset($chosenWorkorder))
        <div class="modal-content">
            <div class="row">
                <div class="col s12">
                    <h4>Uren bekijken</h4>
                </div>
                <div class="col s12">
                    <table class="striped">
                        <thead class="grey darken-3 white-text">
                            <tr>
                                <td colspan="99">Ingevulde gegevens</td>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td style="min-width: 300px;">
                                    <select name="custom_project_id" id="editProjectId" class="editProjectId browser-default mt-0">
                                        @foreach(\App\Models\Project::all() as $project)
                                            <option @if($project->id == $chosenWorkorder->project_id) selected @endif value="{{ $project->id }}">{{ ($project->customer ? $project->customer->company_name . " | ": "") . $project->title }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td><input type="time" class="editFromTime" value="{{ $chosenWorkorder->time_from }}"></td>
                                <td><input type="time" class="editToTime" value="{{ $chosenWorkorder->time_to }}"></td>
                                <td><input type="date" class="editDate" value="{{ $chosenWorkorder->date }}"></td>
                                <td>
                                    <select name="status" class="editStatus">
                                        <option @if($chosenWorkorder->status == 'Declarabel') selected @endif value="Declarabel">Declarabel</option>
                                        <option @if($chosenWorkorder->status == 'Niet Declarabel') selected @endif value="Niet Declarabel">Niet declarabel</option>
                                        <option @if($chosenWorkorder->status == 'Jaarfactuur') selected @endif value="Jaarfactuur">Jaarfactuur</option>
{{--                                        <option @if($chosenWorkorder->status == 'Niet meetellen') selected @endif value="Niet meetellen">Niet meetellen</option>--}}
                                    </select>
                                </td>
                                <td class="center"><a onclick="editCustomHours('{{ $chosenWorkorder->id }}')" class="btn-floating orange"><i class="material-icons">save</i></a></td>
                            </tr>
                            <tr class="descHolder">
                                <td colspan="99">
                                    @if($chosenWorkorder->description != null)
                                        <textarea name="editDescription" id="editDescription" cols="30" rows="10">{!! $chosenWorkorder->description !!}</textarea>
                                    @else
                                        <textarea name="editDescription" id="editDescription" cols="30" rows="10">@foreach($chosenWorkorder->finishedTodos() as $todo){!! $todo->description !!}@endforeach</textarea>
                                    @endif
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <a onclick="deleteWorkOrder('{{ $chosenWorkorder->id }}')" class="modal-close waves-effect waves-green btn-flat left">Ingevulde uren verwijderen</a>
            <a href="{{ url('/projecten/bekijken/'.$chosenWorkorder->project_id) }}" class="modal-close waves-effect waves-green btn-flat">Project bezoeken</a>
            <a href="#!" class="modal-close waves-effect waves-green btn-flat">Sluiten</a>
        </div>
    @endif
</div>
