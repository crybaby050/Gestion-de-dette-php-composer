<?php
/**
 * Table de routage.
 * Format : 'METHODE /chemin' => [Controller::class, 'action']
 */
return [
    'GET /login'          => ['App\Controllers\AuthController', 'showLogin'],
    'POST /login'         => ['App\Controllers\AuthController', 'login'],
    'GET /logout'         => ['App\Controllers\AuthController', 'logout'],

    'GET /clients'        => ['App\Controllers\ClientController', 'index'],
    'GET /clients/show'   => ['App\Controllers\ClientController', 'show'],
    'GET /clients/create' => ['App\Controllers\ClientController', 'create'],
    'POST /clients/store' => ['App\Controllers\ClientController', 'store'],
];