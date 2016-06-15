<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="tags")
 */
class Tag
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
    public $slug;

    /**
     * @ORM\Column(type="datetime", name="created_at")
     *
     * @var \DateTime
     */
    protected $createdAt;

    /**
     * @ORM\Column(type="datetime", name="deleted_at", nullable=true)
     *
     * @var \DateTime
     */
    protected $deletedAt;

    /**
     * @ORM\ManyToMany(targetEntity="Product", mappedBy="tags")
     *
     * @var Collection|Product[]
     */
    protected $products;

    public function __construct($data)
    {
        $this->name = array_get($data, 'name');
        $this->slug = array_get($data, 'slug');
        $this->createdAt = array_get($data, 'createdAt', new \DateTime());
        $this->products = new ArrayCollection();
    }

    public function addProduct(Product $product)
    {
        if ($this->products->contains($product)) {
            return;
        }

        $this->products->add($product);
        $product->addTag($this);
    }

    public function removeProduct(Product $product)
    {
        if (!$this->products->contains($product)) {
            return;
        }

        $this->products->removeElement($product);
        $product->removeTag($this);
    }
}
