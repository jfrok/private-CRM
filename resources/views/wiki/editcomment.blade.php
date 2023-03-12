@extends('layouts.app')

@section('content')
    <script src="https://cdn.ckeditor.com/ckeditor5/31.0.0/classic/ckeditor.js"></script>

    <section class="row s12">
        <div class="collection col s3">
            <a href="{{ route('wiki') }}" class="blue-text collection-item">
                <b>+ Maak wiki post</b>
            </a>

            @foreach($wiki as $posts)
                <a href="{{ route('wiki.show', $posts->id) }}" class="blue-text collection-item">
                    &bull; {{ $posts->titel }} - {{ $posts->user_name }}
                </a>
            @endforeach
        </div>

        <div class="row">
            <div class="col s9">

                <div class="card">
                    <div class="card-content">

                        <form action="{{ route('edit.comment') }}" method="post">
                            @csrf
                            <h5>Bewerk comment</h5>

                            <input type="hidden" name="id" value="{{ $post->id }}"/>

                            <input type="hidden" name="post_id" value="{{ $post->post_id }}"/>

                            <div>
                                <label>Body</label>
                                <textarea type="text" id="editor" name="body" required>{{ $post->body }}</textarea>
                            </div>

                            <br/>

                            <button id="submit" class="blue waves-effect waves-light btn">
                                <b>Bewerk post</b>
                            </button>
                        </form>

                    </div>
                </div>

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
                </script>

            </div>
        </div>
    </section>
@endsection
