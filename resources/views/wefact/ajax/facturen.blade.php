@if(count(Illuminate\Support\Facades\DB::table('wefact')->get()) > 0)
    <br>
    @foreach(Illuminate\Support\Facades\DB::table('wefact')->orderBy('id', 'desc')->get() as $factuur)
        <div>
            <div class="row valign-wrapper">
                <p class="valign-wrapper col s6">
                    <i class="bi bi-file-earmark-ruled-fill"></i> &nbsp;&nbsp;<b>{{ $factuur->file }}</b>
                </p>

                <form id="csvDelete">
                    @csrf
                    <input type="hidden" name="id" value="{{ $factuur->id }}">
                </form>

                <form id="csvExport">
                    @csrf
                    <input type="hidden" name="id" value="{{ $factuur->id }}">
                </form>

                <div class="col s6">
                    <a onclick="csvDelete()" class="btn waves-effect waves-dark bg-light black-text right">
                        <i class="bi bi-trash"></i>
                    </a>
                    <a onclick="csvExport()" class="btn waves-effect waves-dark bg-light black-text right">
                        <i class="bi bi-upload"></i>
                    </a>
                </div>
            </div>
        </div>
    @endforeach
@else
    <div>
        Nog geen facturen geüpload.
    </div>
@endif
