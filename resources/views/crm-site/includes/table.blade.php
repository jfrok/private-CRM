<table>
    <thead>
    <tr>
        <td>klant</td>
        <td>Project</td>
        <td>Titel</td>
        <td>Datum</td>
        <td>Type</td>
        <td>Thumbnail</td>
        <td>Actie</td>
    </tr>
    </thead>
    <tbody>
    @forelse($siteProjects as $sp)

        <tr>
            <td>{{($sp->customer ? $sp->customer->company_name : '')}}</td>
            <td>{{($sp->project ? $sp->project->title : '')}}</td>
            <td>{{$sp->title}}</td>
            <td>{{$sp->date}}</td>
            <td>{{$sp->type}}</td>
            <td>{{$sp->thumbnail}}</td>
            <td>
                <a href="javascript:void(0);" onclick="editCase('{{ $sp->id }}')"
                   class="btn waves-effect modal-trigger">edit</a>
                <a href="javascript:void(0);" onclick="deleteCase('{{ $sp->id }}')"
                   class="btn waves-effect modal-trigger red white-text">delete</a>
                <a href="{{ route('siteProjects.content', ['siteId' => $sp->id]) }}" class="btn waves-effect modal-trigger blue white-text">content</a>
            </td>
        </tr>
    @empty
        <tr>
            <td colspan="99"><i>Geen klantcases gevonden...</i></td>
        </tr>
    @endforelse
    </tbody>
</table>
