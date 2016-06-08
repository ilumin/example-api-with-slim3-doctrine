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
    public $category;

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
}
