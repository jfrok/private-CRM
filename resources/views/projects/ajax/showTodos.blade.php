<div class="row">
    <div class="col s12 m8">
        <div class="card">
            <div class="card-content">
                <div class="row">
                    <div class="col s12">
                        <div class="title left">
                            To-do lijst
                        </div>
                        <div class="title right">
                            @if($project->getProgress() == 100)
                                <i class="material-icons fireworks">celebration</i>
                            @endif
                            {{ number_format($project->getProgress(), 0) }}%
                        </div>
                    </div>

                    <div class="col s12">
                        <div class="progress">
                            <div class="determinate" style="width:{{ $project->getProgress() }}%"></div>
                        </div>
                    </div>
                    @foreach($project->todoCategories() as $cat)
                        <div class="col s12">
                            <br>
                            <table class="striped">
                                <thead class="grey darken-3 white-text">
                                    <tr>
                                        <td style="min-width: 150px; width: 150px;">{{ $cat['category_name'] }}</td>
                                        <td style="min-width: 300px; width: 300px;"></td>
                                        <td></td>
                                        <td></td>
                                        <td style="min-width: 50px; width: 50px;"></td>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($project->getTodosByCatName($cat['category_name']) as $todo)
                                        <tr @if($todo->status == 'Afgerond') class="green accent-1 hoverable clickable" @else class="hoverable clickable" @endif>
                                            <td>
                                                <p>
                                                    <label>
                                                        <input type="checkbox" class="filled-in" @if($todo->status == 'Afgerond') checked="checked" @endif onchange="finishTodo('{{ $todo->id }}', $(this))" />
                                                        <span>Afgerond</span>
                                                    </label>
                                                </p>
                                            </td>
                                            <td onclick="showTodoModal('{{ $todo->id }}')"><b>{{ $todo->title }}</b></td>
                                            <td onclick="showTodoModal('{{ $todo->id }}')">{!! $todo->description !!}</td>
                                            @if($todo->status == 'Afgerond')
                                                <td onclick="showTodoModal('{{ $todo->id }}')">{{ $todo->getNiceFinishedDate()  }}</td>
                                            @else
                                                <td onclick="showTodoModal('{{ $todo->id }}')"></td>
                                            @endif
                                            <td onclick="showTodoModal('{{ $todo->id }}')" class="tooltipped" data-tooltip="{{ $todo->user->name }}" data-position="right"><img class="circle tinyUser " src="{{ $todo->getUserImage() }}"></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    <div class="col s12 m4 right">
        <div class="card">
            <div class="card-content">
                <form id="createTodoForm" method="post">
                    @csrf
                    <div class="row">
                        <div class="col s12">
                            <div class="title left">
                                Nieuwe to-do
                            </div>
                        </div>
                        <div class="col s12">
                            <br>
                            <label for="category_name">To-do toevoegen aan</label>
                            <select name="category_name" id="category_name" class="browser-default" required>
                                <option disabled selected>Toevoegen aan</option>
                                @foreach($project->todoCategories() as $cat)
                                    <option value="{{ $cat['category_name'] }}">{{ $cat['category_name'] }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col s12">
                            <label for="name">Naam van de to-do</label>
                            <input type="text" id="name" name="name" required>
                        </div>
                        <div class="col s12">
                            <label for="description">Omschrijving</label>
                            <textarea name="descriptionHolder" id="descriptionHolder" cols="30" rows="10"></textarea>
                            <textarea name="description" id="description" cols="30" rows="10" required hidden></textarea>
                        </div>
                        <div class="col s12">
                            <br>
                            <a onclick="submitTodoForm()" class="orange white-text btn btn-flat right">To-do aanmaken</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
