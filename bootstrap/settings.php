<?php

// @TODO: Display error on development environment
error_reporting(E_ALL);
ini_set('display_errors', 'on');

return [
    'settings' => [
        'displayErrorDetails' => true, // set to false in production

        // Monolog settings
        'logger' => [
            'name' => 'slim-app',
            'path' => __DIR__ . '/../logs/app.log',
        ],
    ],
];
