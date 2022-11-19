@extends('templates/master')

@section('title')
    Instructions
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col"></div>
            <div class="col">

                <h2> Instructions </h2>
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
            <div class="col"></div>
        </div>
        <div class="row">
            <div class="col"></div>
            <div class="col">
                <a href="/play" class="btn btn-success">Play</a>
            </div>
            <div class="col"></div>
        </div>
    </div>
@endsection
