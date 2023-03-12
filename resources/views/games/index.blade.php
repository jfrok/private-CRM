@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col s12">
            <div class="card">
                <div class="card-content">
                    <div class="row">
                        <div class="col s12">
                            <div class="title left">
                                Games
                            </div>
                        </div>
                        <div class="col s12 m3">
                            <a href="{{ url('/games/spelen/dino-run') }}">
                                <div class="card hoverable">
                                    <div class="center-align grey darken-3 gamesTop" style="background-image: url('{{ asset('img/dinorun.png') }}'); background-position: center; background-size: cover">
                                    </div>
                                    <div class="card-content blue">
                                        <i class="material-icons biggerIcon right orange-text">play_circle_outline</i>
                                        <br><br>
                                    </div>
                                </div>
                            </a>
                        </div>

                        <div class="col s12 m3">
                            <a href="{{ url('/games/spelen/monkey-island') }}">
                                <div class="card hoverable">
                                    <div class="center-align grey darken-3 gamesTop" style="background-image: url('{{ asset('img/monkeyisland500300.jpg') }}'); background-position: center; background-size: cover">
                                    </div>
                                    <div class="card-content blue">
                                        <i class="material-icons biggerIcon right orange-text">play_circle_outline</i>
                                        <br><br>
                                    </div>
                                </div>
                            </a>
                        </div>

                        <div class="col s12 m3">
                            <a href="{{ url('/games/spelen/spider-solitaire') }}">
                                <div class="card hoverable">
                                    <div class="center-align grey darken-3 gamesTop" style="background-image: url('{{ asset('img/spidersolitaire300200.jpg') }}'); background-position: center; background-size: cover">
                                    </div>
                                    <div class="card-content blue">
                                        <i class="material-icons biggerIcon right orange-text">play_circle_outline</i>
                                        <br><br>
                                    </div>
                                </div>
                            </a>
                        </div>

                        <div class="col s12 m3">
                            <a href="{{ url('/games/spelen/gold-miner') }}">
                                <div class="card hoverable">
                                    <div class="center-align grey darken-3 gamesTop" style="background-image: url('{{ asset('img/goldminer300.jpg') }}'); background-position: center; background-size: cover">
                                    </div>
                                    <div class="card-content blue">
                                        <i class="material-icons biggerIcon right orange-text">play_circle_outline</i>
                                        <br><br>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
