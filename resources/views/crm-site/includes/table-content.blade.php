<table class="striped">
    <thead class="blue white-text">
    <tr>
        <th>Type</th>
        <th>Titel</th>
        <th>Content</th>
        <th>Actie</th>
    </tr>
    </thead>
    <tbody id="sortable">
    @forelse($contents as $content)
        <tr>
            <td>
                {{$content->type}}
                <input type="hidden" name="contentIds[]" value="{{ $content->id }}">
            </td>
            <td>{{$content->title}}</td>
            <td>{{ $content->type == 'text'? $content->description : $content->image_path}}</td>
            <td><a href="#EditTextModal" onclick="editCaseContent('{{$content->id}}')" class="btn waves-effect modal-trigger">edit</a>
                <a href="javascript:void(0);" onclick="deleteCaseContent('{{ $content->id }}')"
                   class="btn waves-effect modal-trigger red white-text">delete</a></td>
        </tr>
    @empty
        <tr>
            <td colspan="99"><i>Geen content gevonden...</i></td>
        </tr>
    @endforelse
    </tbody>
</table>

