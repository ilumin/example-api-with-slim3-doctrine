<?php

/**
 * Doctrine command helper
 */

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Console\ConsoleRunner;
use Doctrine\ORM\Tools\Setup;

try {
    $databaseConfig = require 'config/database.php';
    $doctrineConfig = Setup::createAnnotationMetadataConfiguration(
        $databaseConfig['meta']['entityPath'],
        $databaseConfig['meta']['auto_generate_proxies'],
        $databaseConfig['meta']['proxy_dir'],
        $databaseConfig['meta']['cache'],
        true
    );
    $entityManager = EntityManager::create($databaseConfig['connection'], $doctrineConfig);;
    return ConsoleRunner::createHelperSet($entityManager);
}
catch (\Exception $e) {
    echo "ERROR: " . $e->getMessage();
}
