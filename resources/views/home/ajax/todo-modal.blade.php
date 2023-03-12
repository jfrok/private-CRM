<div id="replaceTodoModal">
    @if(isset($selectedTodo))
        <form action="{{ route('home.todos.edit', ['todoId' => $selectedTodo->id]) }}" method="post">
            @csrf
            <div class="modal-content">
                <div class="row">
                    <div class="col s12">
                        <div class="title left">
                            To-do bekijken
                        </div>
                    </div>
                    <div id="editTodoProjectWrapper" class="col s12 {{ ($selectedTodo->project_id != NULL ? "m6" : "") }}">
                        <br>
                        <label for="edit_todo_project_name">To-do toevoegen project</label>
                        <select name="edit_todo_project_name" id="edit_todo_project_name" class="browser-default" onchange="getProjectCategories($(this).val(), 'edit')" required>
                            <option disabled>Toevoegen aan</option>
                            <option value="other" {{ ($selectedTodo->project_id == NULL ? "selected" : "") }}>Losse todo</option>
                            @foreach($searchProjects as $sp)
                                <option value="{{ $sp->id }}"{{ ($selectedTodo->project_id == $sp->id ? "selected" : "") }}>{{ ($sp->customer ? $sp->customer->company_name . " | " : "") . $sp->title }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div id="editTodoCategoryWrapper" class="col s12 m6 {{ ($selectedTodo->project_id == NULL ? "displayNone" : "") }}">
                        <br>
                        <label for="edit_todo_category_name">Project categorie</label>
                        <select name="edit_todo_category_name" id="edit_todo_category_name" class="browser-default">
                            <option disabled selected>Toevoegen aan</option>
                            @foreach($projectCats as $pc)
                                <option value="{{ $pc['category_name'] }}" {{ ($pc['category_name'] == $selectedTodo->category_name ? "selected" : "") }}>{{ $pc['category_name'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col s12 m6 mt-10">
                        <label for="edit_todo_name">Naam van de to-do</label>
                        <input type="text" id="edit_todo_name" name="edit_todo_name" value="{{ $selectedTodo->title }}" required>
                    </div>
                    <div class="col s12 m6 mt-10">
                        <label for="todo_user">Voor wie is de todo?</label>
                        <select name="edit_todo_user" id="edit_todo_user" class="browser-default">
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ ($user->id == $selectedTodo->user_id ? "selected" : "") }} data-icon="{{ asset($user->getProfileImage()) }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col s12">
                        <label for="description">Omschrijving</label>
                        <textarea name="edit_todo_description" id="edit_todo_description" cols="30" rows="10">{!! $selectedTodo->description !!}</textarea>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-flat waves-effect waves-light left green white-text" onclick="finishTodo('{{ $selectedTodo->id }}')"><i class="material-icons left">check</i> Afronden</button>
                <button type="button" class="btn-flat waves-effect waves-light modal-close white-text">Sluiten</button>
                <button type="submit" class="btn-flat waves-effect waves-light modal-close white-text">To-do wijzigen</button>
            </div>
        </form>
    @endif
</div>
