<?php

namespace App\Controllers;

class Controller
{
    protected $app;

    public function __construct($app)
    {
        $this->app = $app;
    }
}

// https://stackoverflow.com/questions/35634530/laravel-routes-not-found-after-nginx-install