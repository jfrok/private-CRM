@extends('layouts.app')

@section('content')
    <script src="https://cdn.ckeditor.com/ckeditor5/31.0.0/classic/ckeditor.js"></script>

    <section class="row s12">

        <div class="row">
            <div class="col s12">
                <div class="editor">
                    <div class="card">
                        <div class="card-content">

                            <form action="{{ route('lijst.edit.submit') }}" method="post">
                                @csrf
                                <h5>Bewerk lijst</h5>

                                <input type="hidden" name="id" value="{{ $lijst->id }}"/>

                                <div>
                                    <label>Body</label>
                                    <textarea type="text" id="editor" name="body" required>{{ $lijst->body }}</textarea>
                                </div>

                                <br/>

                                <button id="submit" class="blue waves-effect waves-light btn">
                                    <b>Bewerk post</b>
                                </button>
                            </form>

                        </div>
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
