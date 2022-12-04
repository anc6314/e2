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
                    Move history for Game ID {{ $game_id }}
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
        @if ($moves)
            <div class="row">
                <div class="col">
                </div>
                <div class="col-8">
                    <h2>History</h2>
                    <table class="table table-hover">
                        <tr class="table-primary">
                            <th scope="col">Round #</th>
                            <th scope="col">Player's card</th>
                            <th scope="col">Computer's card</th>
                            <th scope="col">Winner</th>
                            <th scope="col">Choice</th>
                            <th scope="col">Player cards left</th>
                            <th scope="col">Computer cards left</th>
                        </tr>
                        @foreach ($moves as $move)
                            <tr class="table-{{ $move['winner_class'] }} black">
                                <td> {{ $move['number'] }}</td>
                                <td>
                                    <span class={{ $move['player_card_class'] }}>
                                        {{ $move['player_card'] }}
                                    </span>
                                </td>
                                <td>
                                    <span class={{ $move['computer_card_class'] }}>
                                        {{ $move['computer_card'] }}
                                    </span>
                                </td>
                                <td>
                                    {{ $move['winner'] }}
                                </td>
                                <td> {{ $move['choice'] }} </td>
                                <td> {{ $move['player_card_count'] }} </td>
                                <td> {{ $move['computer_card_count'] }} </td>
                            </tr>
                        @endforeach
                    </table>
                </div>
                <div class="col">
                </div>
        @endif

    </div>

    </div>
@endsection
