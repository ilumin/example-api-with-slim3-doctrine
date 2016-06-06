<?php
namespace App\Entity;

/**
 * @Entity
 * @Table(name="products")
 */
class Product
{
    /**
     * @Id @Column(type="integer")
     * @GenerateValue
     * @var int
     */
    protected $id;

    /**
     * @Column(type="string")
     * @var string
     */
    protected $name;

    /**
     * @Column(type="float")
     * @var float
     */
    protected $price;

    /**
     * @Column(type="datetime")
     * @var \DateTime
     */
    protected $created;

    /**
     * @return array
     */
    public function getData()
    {
        return get_object_vars($this);
    }
}
