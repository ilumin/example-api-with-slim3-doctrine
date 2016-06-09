<?php
namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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
     * @ORM\Column(type="datetime", name="deleted_at", nullable=true)
     *
     * @var \DateTime
     */
    protected $deletedAt;

    /**
     * @ORM\ManyToOne(targetEntity="Category", inversedBy="products")
     *
     * @var Category
     */
    protected $category;

    /**
     * @ORM\ManyToMany(targetEntity="Tag", inversedBy="products")
     * @ORM\JoinTable(
     *     name="product_tags",
     *     joinColumns={ @ORM\JoinColumn(name="product_id", referencedColumnName="id") },
     *     inverseJoinColumns={ @ORM\JoinColumn(name="tag_id", referencedColumnName="id") }
     * )
     *
     * @var Collection|Tag[]
     */
    protected $productTags;

    public function __construct($productData)
    {
        $this->name = $productData['name'];
        $this->slug = $productData['slug'];
        $this->price = $productData['price'];
        $this->createdAt = isset($categoryData['createdAt']) ? $categoryData['createdAt'] : new \DateTime();

        $this->productTags = new ArrayCollection();
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

    public function addTag(Tag $tag)
    {
        if ($this->productTags->contains($tag)) {
            return;
        }

        $this->productTags->add($tag);
        $tag->addProduct($this);
    }

    public function removeTag(Tag $tag)
    {
        if (!$this->productTags->contains($tag)) {
            return;
        }

        $this->productTags->removeElement($tag);
        $tag->removeProduct($this);
    }
}
