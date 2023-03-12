<div id="replaceTodosList">
    <div class="row">
        <div class="col s12 mt-20">
            <table class="striped">
                @foreach($todos as $t)
                    <tr class="hoverable clickable">
                        <td onclick="showTodoModal('{{ $t->id }}')"><b>{{ ($t->project ? $t->project->title . ($t->project->customer ? " | " . $t->project->customer->company_name : "") : "") }}</b></td>
                        <td onclick="showTodoModal('{{ $t->id }}')"><b>{{ $t->title }}</b></td>
                        <td onclick="showTodoModal('{{ $t->id }}')" class="tooltipped" data-tooltip="{{ $t->user->name }}" data-position="right"><img class="circle userImage right" src="{{ $t->getUserImage() }}"></td>
                    </tr>
                @endforeach
                @if($todos->count() == 0)
                    <tr>
                        <td colspan="3"><i>Geen todo's</i></td>
                    </tr>
                @endif
            </table>
        </div>
    </div>
</div>
