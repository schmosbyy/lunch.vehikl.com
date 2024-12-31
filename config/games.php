<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Available Games
    |--------------------------------------------------------------------------
    |
    | This array defines the games that users can challenge each other to play.
    | Each game has an ID, name, icon URL, and game URL.
    |
    */
    'available_games' => [
        [
            'id' => 'chess',
            'name' => 'Chess',
            'icon' => 'https://www.chess.com/favicon.ico',
            'url' => 'https://lichess.org/challenge-page'
        ],
        [
            'id' => 'tictactoe',
            'name' => 'Tic Tac Toe',
            'icon' => '/icons/tictactoe.svg',
            'url' => 'https://playtictactoe.org'
        ],
        [
            'id' => 'connect4',
            'name' => 'Connect Four',
            'icon' => '/icons/connect4.svg',
            'url' => 'https://connect-four.org'
        ]
    ],

    /*
    |--------------------------------------------------------------------------
    | Challenge Settings
    |--------------------------------------------------------------------------
    |
    | Various settings for game challenges.
    |
    */
    'challenge_settings' => [
        'allowed_statuses' => ['pending', 'accepted', 'declined', 'completed'],
        'default_status' => 'pending',
        'max_active_challenges' => 5, // Maximum number of active challenges per user
    ],
]; 