<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="variants")
 */
class Variant
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @ORM\Column(type="integer")
     *
     * @var int
     */
    public $id;

    /**
     * @ORM\Column(type="string")
     *
     * @var string
     */
    public $name;

    /**
     * @ORM\Column(type="string")
     *
     * @var string
     */
    public $sku;

    /**
     * @ORM\Column(type="float")
     *
     * @var float
     */
    public $price;

    /**
     * @ORM\Column(type="datetime", name="created_at")
     *
     * @var \DateTime
     */
    public $createdAt;

    /**
     * @ORM\Column(type="datetime", name="deleted_at", nullable=true)
     *
     * @var \DateTime
     */
    protected $deletedAt;

    /**
     * @ORM\ManyToOne(targetEntity="Product", inversedBy="variants")
     *
     * @var Product
     */
    protected $product;

    public function __construct($variantData)
    {
        $this->name = $variantData['name'];
        $this->sku = $variantData['sku'];
        $this->price = $variantData['price'];

        $this->setProduct($variantData['product']);
    }

    public function setProduct(Product $product)
    {
        $this->product = $product;
    }
}
