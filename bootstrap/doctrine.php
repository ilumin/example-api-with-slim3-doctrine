<?php

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Setup;
use Gedmo\SoftDeleteable\Filter\SoftDeleteableFilter;

$databaseConfig = require __DIR__ . '/../config/database.php';
$doctrineConfig = Setup::createAnnotationMetadataConfiguration(
    $databaseConfig['meta']['entityPath'],
    $databaseConfig['meta']['auto_generate_proxies'],
    $databaseConfig['meta']['proxy_dir'],
    $databaseConfig['meta']['cache'],
    true
);

// register soft delete filter
$doctrineConfig->addFilter('soft-deleteable', SoftDeleteableFilter::class);

$entityManager = EntityManager::create($databaseConfig['connection'], $doctrineConfig);

return $entityManager;
