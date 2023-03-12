@extends('layouts.app')

@section('content')
    <script src="https://cdn.ckeditor.com/ckeditor5/31.0.0/classic/ckeditor.js"></script>

    <section class="row s12">
        <div class="collection col s3">
            <a href="{{ route('wiki') }}" class="blue-text collection-item">
                <b>+ Maak wiki post</b>
            </a>

            @foreach($wiki as $posts)
                @if($posts->id == $post->id)
                    <a href="{{ route('wiki.show', $posts->id) }}" class="blue collection-item active">
                        @else
                            <a href="{{ route('wiki.show', $posts->id) }}" class="blue-text collection-item">
                                @endif
                                &bull; {{ $posts->titel }} - {{ $posts->user_name }}
                            </a>
                    @endforeach
        </div>

        <div class="row">
            <div class="col s9">

                <div class="card white">
                    <div class="card-content black-text wiki">
                        <span class="card-title">
                            {{ $post->titel }}
                        </span>
                        <p>Post gemaakt door {{ $post->user_name }}.</p>
                        <hr/>
                        <span class="black-text">
                            {!! $post->body !!}
                        </span>
                        @if($post->user_id == Auth::user()->id)
                            <br/>

                            <form id="post" action="{{ route('delete.post') }}" method="post">
                                @csrf
                                <input type="hidden" name="id" value="{{ $post->id }}">
                            </form>

                            <a href="{{ route('wiki.edit', $post->id) }}" class="blue waves-effect waves-light btn">
                                <b>Bewerk post</b>
                            </a>

                            &nbsp;&nbsp;

                            <button class="red waves-effect waves-light btn" onclick="deletePostPopUp()">
                                <b>Verwijder post</b>
                            </button>
                        @endif
                    </div>
                </div>

                <div>
                    <div class="card">
                        <div class="card-content">
                            <form action="{{ route('wiki.comment') }}" method="post">
                                @csrf
                                <h6>Reageer onder deze post</h6>

                                <div class="input-field">
                                    <label>Body</label>
                                    <input type="text" id="editor" name="body" required/>
                                </div>

                                <input type="hidden" name="hidden_id" value="{{ $post->id }}"/>

                                <button id="submit" class="blue waves-effect waves-light btn">
                                    <b>Reageer</b>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                @if(isset($comments))
                    @foreach($comments as $comment)
                        <div class="card white">
                            <div class="card-content black-text">
                                <p>{{ $comment->user_name }} &bull;</p>
                                <span class="black-text">
                                {!! $comment->body !!}
                                </span>
                                @if($post->user_id == Auth::user()->id || $comment->user_id == Auth::user()->id)
                                    <br/>
                                    <form id="comment-{{ $comment->id }}" action="{{ route('delete.comment') }}"
                                          method="post">
                                        @csrf
                                        <input type="hidden" name="id" value="{{ $comment->id }}">
                                        <input type="hidden" name="parameter" value="{{ $post->id }}">
                                    </form>

                                    <a href="{{ route('wiki.edit-comment', $comment->id) }}"
                                       class="blue waves-effect waves-light btn">
                                        <b>Bewerk reactie</b>
                                    </a>

                                    &nbsp;&nbsp;

                                    <button class="red waves-effect waves-light btn" onclick="deleteCommentPopUp()">
                                        <b>Verwijder reactie</b>
                                    </button>

                                    <script>
                                        function deleteCommentPopUp() {
                                            Swal.fire({
                                                title: 'Wil je deze comment verwijderen?',
                                                icon: 'warning',
                                                showCancelButton: true,
                                                confirmButtonColor: '#260089',
                                                cancelButtonColor: '#f44336',
                                                confirmButtonText: 'Ja, ik weet het zeker'
                                            }).then((result) => {
                                                if (result.isConfirmed) {
                                                    document.getElementById("comment-{{ $comment->id }}").submit()
                                                }
                                            })
                                        }
                                    </script>
                                @endif
                            </div>
                        </div>
                    @endforeach
                @else
                    <h6>Geen reacties beschikbaar.</h6>
                @endif

                <script>
                    let editor;

                    ClassicEditor
                        .create(document.querySelector('#editor'))
                        .then(newEditor => {
                            editor = newEditor;
                        })
                        .catch(error => {
                            console.error(error);
                        });

                    document.querySelector('#submit').addEventListener('click', () => {
                        const editorData = editor.getData();

                        document.getElementById("editor").value = editorData;
                    });

                    function deletePostPopUp() {
                        Swal.fire({
                            title: 'Wil je deze post verwijderen?',
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#260089',
                            cancelButtonColor: '#f44336',
                            confirmButtonText: 'Ja, ik weet het zeker'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                document.getElementById("post").submit()
                            }
                        })
                    }
                </script>

            </div>
        </div>
    </section>
@endsection
