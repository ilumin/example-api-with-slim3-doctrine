<?php
namespace App\Entity;

/**
 * @Entity
 * @Table(name="products")
 */
class Product
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
     * @Column(type="float")
     * @var float
     */
    public $price;

    /**
     * @Column(type="datetime", name="created_at")
     * @var \DateTime
     */
    public $createdAt;

    /**
     * @ManyToOne(targetEntity="Category", inversedBy="products")
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

    /**
     * @return array
     */
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
