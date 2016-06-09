<?php
namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="categories")
 */
class Category
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
     * @ORM\Column(type="string", unique=true)
     *
     * @var string
     */
    public $slug;

    /**
     * @ORM\Column(type="datetime", name="created_at")
     *
     * @var \DateTime
     */
    public $createdAt;

    /**
     * @ORM\OneToMany(targetEntity="Product", mappedBy="category", fetch="EXTRA_LAZY")
     *
     * @var Product[]
     */
    protected $products;

    public function __construct($categoryData)
    {
        $this->name = $categoryData['name'];
        $this->slug = $categoryData['slug'];
        $this->createdAt = isset($categoryData['createdAt']) ? $categoryData['createdAt'] : new \DateTime();
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
