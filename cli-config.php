<?php

/**
 * Doctrine command helper
 */

use App\Core\Doctrine;
use Doctrine\ORM\Tools\Console\ConsoleRunner;

$databaseConfig = require 'config/database.php';
$entityManager = Doctrine::bootstrap($databaseConfig);

return ConsoleRunner::createHelperSet($entityManager);
