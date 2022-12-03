<?php

# Define the routes of your application

return [
    # Ex: The path `/` will trigger the `index` method within the `AppController`
    '/'             => ['AppController', 'index'],
    '/game'         => ['AppController', 'game'],
    '/play'         => ['AppController', 'play'],
    '/play/process' => ['AppController', 'process'],
    '/register'     => ['AppController', 'register'],
];