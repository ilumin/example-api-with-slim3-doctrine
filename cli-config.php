<?php

/**
 * Doctrine command helper
 */

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Console\ConsoleRunner;
use Doctrine\ORM\Tools\Setup;

try {
    $databaseConfig = require 'config/database.php';
    $doctrineConfig = Setup::createAnnotationMetadataConfiguration($databaseConfig['entityPath'], true);
    $entityManager = EntityManager::create($databaseConfig, $doctrineConfig);;
    return ConsoleRunner::createHelperSet($entityManager);
}
catch (\Exception $e) {
    echo "ERROR: " . $e->getMessage();
}
