<?php
namespace App\Entity;

use Gedmo\Mapping\Annotation as GEDMO;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="products")
 */
class Product
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
     * @ORM\Column(type="datetime", name="deleted_at")
     *
     * @var \DateTime
     */
    public $deletedAt;

    /**
     * @ORM\ManyToOne(targetEntity="Category", inversedBy="products")
     *
     * @var Category
     */
    protected $category;

    public function __construct($productData)
    {
        $this->name = $productData['name'];
        $this->slug = $productData['slug'];
        $this->price = $productData['price'];
        $this->createdAt = new \DateTime();
    }

    public function getCategoryData()
    {
        return $this->category->getData();
    }

    public function getData()
    {
        $productData = get_object_vars($this);
        $productData['category'] = $this->getCategoryData();
        return $productData;
    }

    public function setCategory(Category $category)
    {
        $this->category = $category;
    }
}
