@extends('layouts.app')

@section('content')
    <script src="https://cdn.ckeditor.com/ckeditor5/31.0.0/classic/ckeditor.js"></script>

    <section class="row s12">

        <div class="row">
            <div class="col s12">

                <div class="card wiki white">
                    <div class="card-content black-text">
                        <h5>
                            <i class="bi bi-basket"></i> &nbsp;&nbsp;Boodschappenlijst.
                        </h5>

                        <br>

                        <span class="black-text">
                            {!! $lijst->body !!}
                        </span>
                        <br/>

                        <form id="post" action="{{ route('delete.lijst') }}" method="post">
                            @csrf
                            <input type="hidden" name="id" value="{{ $lijst->id }}">
                        </form>

                        <a href="{{ route('lijst.edit', $lijst->id) }}" class="blue waves-effect waves-light btn">
                            <b>Bewerk lijstje</b>
                        </a>
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

                    function deletePostPopUp() {
                        Swal.fire({
                            title: 'Wil je deze lijstje afronden?',
                            icon: 'question',
                            showCancelButton: true,
                            confirmButtonColor: '#4caf50',
                            cancelButtonColor: '#260089',
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
