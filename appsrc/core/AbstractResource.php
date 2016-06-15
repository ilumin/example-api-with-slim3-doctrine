<?php
namespace App\Core;

use Doctrine\ORM\EntityManagerInterface;

abstract class AbstractResource
{
    /**
     * @var \Doctrine\ORM\EntityManagerInterface
     */
    protected $doctrine = null;

    public function __construct(EntityManagerInterface $doctrine)
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
