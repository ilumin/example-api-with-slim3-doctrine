<?php
namespace App\Core;

use Doctrine\ORM\EntityManager;

abstract class AbstractResource
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    protected $doctrine = null;

    public function __construct(EntityManager $doctrine)
    {
        $this->doctrine = $doctrine;
    }

    /**
     * @param $entityNameSpace
     *
     * @return \Doctrine\ORM\EntityRepository
     */
    public function getRepository($entityNameSpace)
    {
        return $this->doctrine->getRepository($entityNameSpace);
    }
}
