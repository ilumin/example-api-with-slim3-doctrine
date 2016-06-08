<?php
namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * @Entity
 * @Table(name="categories")
 */
class Category
{
    /**
     * @Id
     * @GeneratedValue(strategy="IDENTITY")
     * @Column(type="integer")
     * @var int
     */
    public $id;

    /**
     * @Column(type="string")
     * @var string
     */
    public $name;

    /**
     * @Column(type="string", unique=true)
     * @var string
     */
    public $slug;

    /**
     * @Column(type="datetime", name="created_at")
     * @var \DateTime
     */
    public $createdAt;

    /**
     * @OneToMany(targetEntity="Product", mappedBy="category", fetch="EXTRA_LAZY")
     * @var Product[]
     */
    protected $products;

    public function __construct()
    {
        $this->products = new ArrayCollection();
    }

    public function getProducts()
    {
        return $this->products->toArray();
    }

    public function getProductData($id)
    {
        if (!isset($this->products[$id])) {
            throw new \InvalidArgumentException('Product not found.');
        }

        return $this->products[$id]->getData();
    }

    public function getData()
    {
        return [
            'id'        => $this->id,
            'name'      => $this->name,
            'slug'      => $this->slug,
            'createdAt' => $this->createdAt,
        ];
    }

    public function getFullData()
    {
        $categoryData = $this->getData();
        $categoryData['products'] = $this->getProducts();
        return $categoryData;
    }
}
