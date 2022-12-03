@extends('templates/master')

@section('title')
    Instructions
@endsection

@section('content')
    <div class="container-fluid background">
        <div class="row">
            {{-- Stats / left col --}}
            <div class="col">

                <div class="card">
                    <div class="card-header" id="headingOne">
                        <h2>
                            Player list
                        </h2>
                    </div>
                    <div class="card-body">
                        @foreach ($users as $user)
                            <div class="row">
                                {{ $user['name'] }}
                            </div>
                        @endforeach

                    </div>
                </div>
            </div>
            {{-- Register or Welcome back col --}}
            <div class="col">

                @if (!$name)
                    <div class="card">
                        <div class="card-header" id="headingOne">
                            <h2>
                                Would you like to play a game?
                            </h2>
                        </div>
                        <div class="card-body">
                            <div class="row mb-4">
                                New players may register and starting playing by entering their name below.
                                If your name already exists, this will log you in so you can resume your playing with
                                your
                                same stats / game history.
                            </div>
                            <div class="row">
                                <form method='POST' action='/register'>
                                    <div class="col mb-4">
                                        <label for='name'>Player Name:</label>
                                        <input type='text' id='name' name='name'>
                                    </div>
                                    <div class="float-right">
                                        <button class="btn btn-success" type='submit'>Register and Play</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="card">
                        <div class="card-header" id="headingOne">
                            <h2>
                                Welcome back {{ $name }}!
                            </h2>
                        </div>
                        <div class="card-body">

                            @if ($stats)
                                @foreach ($stats as $stat)
                                    <div class="row">
                                        You have {{ $stat['status'] }} {{ $stat['count(*)'] }} game(s)!
                                    </div>
                                @endforeach
                            @endif
                            <div class="row mt-4">
                                <a href="/play" class="btn btn-success">Resume Playing</a>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
            {{-- instructions right col --}}
            <div class="col black">
                <div id="accordion">
                    <div class="card">
                        <div class="card-header" id="headingInstructions">
                            <button class="btn btn-link" data-toggle="collapse" data-target="#collapseInstructions"
                                aria-expanded="false" aria-controls="collapseInstructions">
                                <h2>
                                    Instructions:
                                </h2>
                            </button>
                            <div id="collapseInstructions" class="collapse show" aria-labelledby="headingInstructions"
                                data-parent="#accordion">
                                <div class="card-body">

                                    <ul>
                                        <li>Each player starts with half the deck (26 cards), shuffled in a random
                                            order.
                                        </li>
                                        <li>For each round, a card is picked from the “top” of each player’s cards.
                                        </li>
                                        <li>The Human player has the <b>choice of playing that top card or
                                                re-shuffling</b>
                                            the
                                            deck and playing
                                            that
                                            card instead</li>
                                        <li>Whoevers card is highest wins that round and keeps both cards.</li>
                                        <li>If the two cards are of the same value, it’s a tie and those cards are
                                            discarded.
                                        </li>
                                        <li>The player who ends up with 0 cards is the loser.</li>
                                    </ul>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
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
                                    <h2> Player History</h2>
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
@endsection
