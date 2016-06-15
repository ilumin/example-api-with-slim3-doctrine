<?php

error_reporting(-1);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
date_default_timezone_set('Asia/Bangkok');

require __DIR__ . '/../vendor/autoload.php';

class LocalWebTestCase extends PHPUnit_Framework_TestCase
{
    public function getSlimInstance() {
        $app = new \Slim\App([
            'version' => '0.0.0',
            'debug' => false,
            'mode' => 'testing',
        ]);

        return $app;
    }
}
