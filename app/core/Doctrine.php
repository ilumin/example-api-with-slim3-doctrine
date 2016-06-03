<?php
namespace App\Core;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Setup;

class Doctrine
{
    /**
     * @param array $databaseConfig
     *
     * @return EntityManager
     * @throws \Doctrine\ORM\ORMException
     */
    public static function bootstrap(array $databaseConfig)
    {
        $doctrineConfig = Setup::createAnnotationMetadataConfiguration($databaseConfig['entityPath'], true);
        return EntityManager::create($databaseConfig, $doctrineConfig);
    }
}
