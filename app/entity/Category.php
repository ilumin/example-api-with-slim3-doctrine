<?php
namespace App\Entity;

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
     * @Column(type="datetime", name="created_at")
     * @var \DateTime
     */
    public $createdAt;

    /**
     * @OneToMany(targetEntity="Product", mappedBy="category_id", indexBy="id", fetch="EXTRA_LAZY")
     * @var Product[]
     */
    public $products;

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
            'createdAt' => $this->createdAt,
        ];
    }
}
