<?php
namespace App;

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
}
