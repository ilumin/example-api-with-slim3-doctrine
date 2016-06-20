<?php

$config = array(
    'meta' => array(
        'entityPath' => array(
            __DIR__ . '/../appsrc/entity',
        ),
        'auto_generate_proxies' => true,
        'proxy_dir'             => __DIR__ . '/../cache/proxies',
        'cache'                 => null,
    ),
    'connection' => array(
        'driver'     => 'pdo_mysql',
        'host'       => 'app-database-service',
        'dbname'     => 'app',
        'user'       => 'app',
        'password'   => 'app',
    ),
);

return $config;
