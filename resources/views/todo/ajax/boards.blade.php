@foreach($boards as $board)

    @php
        $gesloten = 0;
        $alles = 0;
        $lists = Illuminate\Support\Facades\DB::table('todo_list')->where('board_id', '=', $board->id)->get();

        if ($lists != null) {
            foreach($lists as $list) {
                foreach(Illuminate\Support\Facades\DB::table('todo')->where('status', '=', 'Afgerond')->where('list_id', '=', $list->id)->get() as $afg) {
                    $gesloten++;
                }

                foreach(Illuminate\Support\Facades\DB::table('todo')->where('list_id', '=', $list->id)->get() as $all) {
                    $alles++;
                }
            }
        }

        if ($alles == 0) {
            $procent = 0;
        } else {
            $rest = $gesloten / $alles;
            $procent =  $rest * 100;
        }

    @endphp

    <div class="col s12 m3">
        <div class="card">
            <div class="card-content">
                <div>
                    <h5>
                        <b>
                            {{ $board->title }}
                        </b>

                        <a onclick="fillEditBoard({{ $board->id }}, '{{ $board->title }}', '{{ $board->status }}', {{ $board->project_id }})"
                           href="#editBoardModal"
                           class="modal-trigger btn waves-effect waves-dark bg-light black-text right">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <b>
                            <a onclick="viewBoard({{ $board->id }}, {{ $board->project_id }})"
                               class="btn waves-effect waves-dark bg-light black-text right">Bekijk board</a>
                        </b>
                    </h5>
                </div>

                <br>

                <div>
                    @if($board->status == 'Open')
                        <label>Vooruitgang <b>{{ round($procent) }}%</b></label>
                        <div class="progress">
                            <div class="determinate" style="width: {{ round($procent) }}%"></div>
                        </div>
                    @else
                        <label>Vooruitgang <b>100% <i class="bi bi-check2"></i></b></label>
                        <div class="progress">
                            <div class="determinate" style="width: 100%"></div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endforeach
