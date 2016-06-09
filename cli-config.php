<?php

/**
 * Doctrine command helper
 */

use Doctrine\ORM\Tools\Console\ConsoleRunner;

try {
    $entityManager = require __DIR__ . '/bootstrap/doctrine.php';
    return ConsoleRunner::createHelperSet($entityManager);
}
catch (\Exception $e) {
    echo "ERROR: " . $e->getMessage();
}
