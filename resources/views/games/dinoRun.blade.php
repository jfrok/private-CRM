@extends('layouts.app')
@section('content')

{{--    <link rel="stylesheet" href="{{ asset('packages/micra-run/style.css') }}">--}}
<div class="row">
    <div class="col s12">
        <div class="card">
            <div class="card-content">
                <div class="row">
                    <div class="col s12">
                        <div class="title left">
                            Dino run
                        </div>
                    </div>
                    <div class="col s12 center">
                        <canvas id="game" height="200" width="1000"></canvas>
                        <p class="controls">press space bar to start</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

    <script src="{{ asset('packages/micra-run/js/helpers.js') }}"></script>
    <script src="{{ asset('packages/micra-run/js/objects/game-object.js') }}"></script>
    <script src="{{ asset('packages/micra-run/js/objects/cactus.js') }}"></script>
    <script src="{{ asset('packages/micra-run/js/objects/dinosaur.js') }}"></script>
    <script src="{{ asset('packages/micra-run/js/objects/background.js') }}"></script>
    <script src="{{ asset('packages/micra-run/js/objects/score.js') }}"></script>
    <script src="{{ asset('packages/micra-run/js/game.js') }}"></script>
    <script>
        new Game({
            el: document.getElementById("game")
        });
    </script>
    </body>
    </html>
@endsection
