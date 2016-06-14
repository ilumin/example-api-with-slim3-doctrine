<?php

/**
 * Doctrine command helper
 */

use Doctrine\ORM\Tools\Console\ConsoleRunner;

try {
    $entityManager = require __DIR__ . '/bootstrap/doctrine.php';

    /** @var $entityManager \Doctrine\ORM\EntityManager */
    $platform = $entityManager->getConnection()->getDatabasePlatform();
    $platform->registerDoctrineTypeMapping('enum', 'string');

    return ConsoleRunner::createHelperSet($entityManager);
}
catch (\Exception $e) {
    echo "ERROR: " . $e->getMessage();
}
