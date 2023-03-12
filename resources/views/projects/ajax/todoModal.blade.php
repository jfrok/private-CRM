<div id="replaceTodoModal">
    @if(isset($chosenTodo))
        <form method="post" id="editTodoForm">
            @csrf
            <div class="modal-content">
                <div class="row">
                    <div class="col s12">
                        <div class="title left">
                            To-do aanpassen
                        </div>
                    </div>
                    <div class="col s12">
                        <br>
                        <label for="edit_category_name">Categorie van to-do</label>
                        <select name="edit_category_name" id="edit_category_name" class="browser-default" required>
                            <option disabled selected>Toevoegen aan</option>
                            @foreach($project->todoCategories() as $cat)
                                <option @if($chosenTodo->category_name == $cat['category_name']) selected @endif value="{{ $cat['category_name'] }}">{{ $cat['category_name'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col s12">
                        <label for="name">Naam van de to-do</label>
                        <input type="text" id="name" name="name" value="{{ $chosenTodo->title }}" required>
                    </div>
                    <div class="col s12">
                        <label for="description">Omschrijving</label>
                        <textarea name="descriptionHolder" id="editDescriptionHolder" cols="30" rows="10">{!! $chosenTodo->description !!}</textarea>
                        <textarea name="edit_description" id="editDescription" cols="30" rows="10" required hidden></textarea>
                    </div>
                    <div class="col s12">
                        <label for="status">Status van de to-do</label>
                        <select name="status" id="status">
                            <option @if($chosenTodo->status == 'Open') selected @endif value="Open">Open</option>
                            <option @if($chosenTodo->status == 'Afgerond') selected @endif value="Afgerond">Afgerond</option>
                            <option @if($chosenTodo->status == 'Verwijderd') selected @endif value="Verwijderd">Verwijderd</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <a href="#!" class="modal-close waves-effect waves-green btn-flat">Sluiten</a>
                <a onclick="editTodoForm('{{ $chosenTodo->id }}')" class="modal-close waves-effect waves-green btn-flat white-text">Aanpassen</a>
            </div>
        </form>
    @endif
</div>
