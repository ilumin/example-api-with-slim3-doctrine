<?php

$config = array(
    'entityPath' => array(__DIR__ . '/../../src'),
    'driver'     => 'pdo_mysql',
    'host'       => 'app-database-service',
    'dbname'     => 'app',
    'user'       => 'app',
    'password'   => 'app',
);

return $config;
