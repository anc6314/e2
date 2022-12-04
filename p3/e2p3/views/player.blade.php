@extends('templates/master')

@section('title')
    Play
@endsection

@section('bodyclass')
    body
@endsection

@section('content')
    <div class="container-fluid background">
        {{-- Title row  --}}
        <div class="row">
            <div class="col">
                <h1>
                    History for {{ $name }}
                </h1>
            </div>

            <div class="col"></div>
            <div class="col">
                <a href="/" class="btn btn-warning float-right">Home</a>
                @if ($name)
                    <a href="/play" class="btn btn-success float-right">Resume Playing</a>
                @endif
            </div>

        </div>

        {{-- History row  --}}
        @if ($games)
            <div class="row">
                <div class="col">
                </div>
                <div class="col-8 black">

                    <div id="accordion">
                        <div class="card">
                            <div class="card-header" id="headingHistory">
                                <button class="btn btn-link" data-toggle="collapse" data-target="#collapseHistory"
                                    aria-expanded="false" aria-controls="collapseHistory">
                                    <h2>Games Played</h2>
                                </button>
                                <div id="collapseHistory" class="collapse show" aria-labelledby="headingHistory"
                                    data-parent="#accordion">
                                    <div class="card-body">
                                        <table class="table table-hover">
                                            <tr class="table-primary">
                                                <th scope="col">Game ID</th>
                                                <th scope="col">Status</th>
                                                <th scope="col">Score</th>
                                            </tr>
                                            @foreach ($games as $game)
                                                <tr>
                                                    <td> <a href="/game?id={{ $game['id'] }}"> {{ $game['id'] }} </td>
                                                    <td> {{ $game['status'] }} </td>
                                                    <td> {{ $game['score'] }} </td>
                                                </tr>
                                            @endforeach
                                        </table>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col">
                </div>
        @endif

    </div>

    </div>
@endsection
