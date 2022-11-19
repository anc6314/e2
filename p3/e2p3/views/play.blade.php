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
                    Interactive War Card Game
                </h1>
            </div>
        </div>

        <div class="row">
            {{-- Stats / left col --}}
            <div class="col">
                <h2>Stats</h2>
                <div>
                    Total Rounds: {{ $round }}
                    <br />

                    @if ($round > 1)
                        Wins: {{ $wins }}
                        <div class="progress">
                            <div class="progress-bar bg-success" role="progressbar" style="width:  {{ $winpercent }}%;"
                                aria-valuenow="{{ $winpercent }}" aria-valuemin="0" aria-valuemax="100">
                                {{ $winpercent }}%
                            </div>
                        </div>

                        Losses: {{ $losses }}
                        <div class="progress">
                            <div class="progress-bar bg-danger" role="progressbar" style="width:  {{ $losspercent }}%;"
                                aria-valuenow="{{ $losspercent }}" aria-valuemin="0" aria-valuemax="100">
                                {{ $losspercent }}
                                %</div>
                        </div>

                        Ties: {{ $ties }}
                        <div class="progress">
                            <div class="progress-bar bg-warning" role="progressbar" style="width: {{ $tiepercent }}%;"
                                aria-valuenow="{{ $tiepercent }}" aria-valuemin="0" aria-valuemax="100">
                                {{ $tiepercent }}%
                            </div>
                        </div>
                        <br />
                        <div class="alert alert-{{ $winnerClass }}">
                            @if ($winner != 'Tie')
                                {{ $winner }} won the last round!
                            @else
                                The last round was a tie!
                            @endif
                        </div>
                    @endif

                </div>

            </div>

            {{-- Card/game play - middle col --}}
            <div class="col">
                <table>
                    @if ($winnerGame == '')
                        <tr>
                            <th>Your Card</th>
                            <th>Computer's Card</th>
                        </tr>
                        <tr>
                            <td>
                                <span>
                                    <img src="{{ $player1CardPath }}" />
                                </span>
                            </td>
                            <td>
                                <span>
                                    <img src="{{ $computerCardPath }}" />
                                </span>
                            </td>
                        </tr>
                        <form method='POST' action='/play/process'>
                            <tr>
                                <td>
                                    <input type='radio' id='keep' name='choice' value='keep' checked>
                                    <label for='keep' class="black">Keep</label>
                                </td>
                                <td>
                                    <input type='radio' id='shuffle' name='choice' value='random'>
                                    <label for='shuffle' class="black">Get random card</label>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <button class="btn btn-success" type='submit'>Play</button>
                                </td>
                        </form>
                    @else
                        <div class="alert alert-primary" role="alert">

                            @if ($winnerGame != 'Tie')
                                Game over, {{ $winnerGame }} won the game!
                            @else
                                The game ended in a tie!
                            @endif

                        </div>
                    @endif

                    <form method='POST' action='/play/process'>
                        <td>
                            <input type='hidden' id='reset' name='choice' value='reset'>
                            <button class="btn btn-danger" type='submit'>
                                @if ($winnerGame = '')
                                    New Game
                                @else
                                    Reset
                                @endif
                            </button>
                        </td>
                    </form>

                    </tr>
                </table>
            </div>

            {{-- instructions right col --}}
            <div class="col black">
                <div id="accordion">
                    <div class="card">
                        <div class="card-header" id="headingOne">
                            <h5 class="mb-0">
                                <button class="btn btn-link" data-toggle="collapse" data-target="#collapseInstructions"
                                    aria-expanded="false" aria-controls="collapseInstructions">
                                    <h2>
                                        Instructions:
                                    </h2>
                                </button>

                                <div id="collapseInstructions" class="collapse show" aria-labelledby="headingOne"
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

                        </h5>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
