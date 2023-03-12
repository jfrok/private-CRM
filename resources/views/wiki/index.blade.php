@extends('layouts.app')

@section('content')
    <script src="https://cdn.ckeditor.com/ckeditor5/31.0.0/classic/ckeditor.js"></script>

    <section class="row s12">
        <div class="collection col s3">
            <a href="{{ route('wiki') }}" class="blue collection-item active">
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


                        <div>

                            <form action="{{ route('wiki.submit') }}" method="post">
                                @csrf
                                <h5>Maak een wiki post</h5>

                                <div class="input-field">
                                    <label>Titel</label>
                                    <input type="text" name="titel" required/>
                                </div>

                                <div>
                                    <label>Body</label>
                                    <input type="text" id="editor" name="body" required/>
                                </div>

                                <br/>

                                <button id="submit" class="blue waves-effect waves-light btn">
                                    <b>Submit post</b>
                                </button>
                            </form>

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
            </div>
        </div>
    </section>
@endsection
