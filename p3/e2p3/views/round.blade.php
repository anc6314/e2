@extends('templates/master')

@section('title')
    Details for Game {{ $result['game_id'] }} / Round {{ $result['number'] }}
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
                    Details for Game {{ $result['game_id'] }} / Round {{ $result['number'] }}
                </h1>
            </div>
            <div class="col"></div>
            <div class="col">
                <a href="/" class="btn btn-warning float-right">Home</a>
            </div>

        </div>

        {{-- Game row  --}}
        <div class="row">
            {{-- Left col --}}
            <div class="col">
            </div>

            {{-- Card/game play - middle col --}}
            <div class="col">
                <table>
                    <tr>
                        <th>Your Card</th>
                        <th>Computer's Card</th>
                    </tr>
                    <tr>
                        <td>
                            <span>
                                <img src="{{ $player1_card_path }}" />
                            </span>
                        </td>
                        <td>
                            <span>
                                <img src="{{ $computer_card_path }}" />
                            </span>
                        </td>
                    </tr>
                </table>
            </div>

            {{--  right col --}}
            <div class="col black">
            </div>
        </div>

        {{-- History row  --}}
        <div class="row">
            <div class="col">
            </div>
            <div class="col-8">
                <h2>Results</h2>
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
                    <tr class="table-{{ $result['winner_class'] }} black">
                        <td> {{ $result['number'] }}</td>
                        <td>
                            <span class={{ $result['player_card_class'] }}>
                                {{ $result['player_card'] }}
                            </span>
                        </td>
                        <td>
                            <span class={{ $result['computer_card_class'] }}>
                                {{ $result['computer_card'] }}
                            </span>
                        </td>
                        <td>
                            {{ $result['winner'] }}
                        </td>
                        <td> {{ $result['choice'] }} </td>
                        <td> {{ $result['player_card_count'] }} </td>
                        <td> {{ $result['computer_card_count'] }} </td>
                    </tr>
                </table>
            </div>
            <div class="col">
            </div>

        </div>

    </div>
@endsection
