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
     * @ORM\ManyToOne(targetEntity="Category", inversedBy="products")
     *
     * @var Category
     */
    protected $category;
    /**
     * @ORM\OneToMany(targetEntity="Variant", mappedBy="product")
     *
     * @var Collection|Variant[]
     */
    protected $variants;
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
    protected $tags;
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

    public function __construct($productData)
    {
        $this->name = array_get($productData, 'name');
        $this->slug = array_get($productData, 'slug');
        $this->price = array_get($productData, 'price');
        $this->createdAt = array_get($productData, 'createdAt', new \DateTime());
        $this->tags = new ArrayCollection();
        $this->variants = new ArrayCollection();
    }

    public function getCategoryData()
    {
        return $this->category->getData();
    }

    public function getData()
    {
        return [
            'id'       => $this->id,
            'name'     => $this->name,
            'slug'     => $this->slug,
            'price'    => $this->price,
            'category' => $this->category->getData(),
            'tags'     => $this->tags->first(),
            'variants' => $this->variants->toArray(),
        ];
    }

    public function setCategory(Category $category)
    {
        $this->category = $category;
    }

    public function addTag(Tag $tag)
    {
        if ($this->tags->contains($tag)) {
            return;
        }

        $this->tags->add($tag);
        $tag->addProduct($this);
    }

    public function removeTag(Tag $tag)
    {
        if (!$this->tags->contains($tag)) {
            return;
        }

        $this->tags->removeElement($tag);
        $tag->removeProduct($this);
    }

    public function removeAllTag()
    {
        if ($this->tags->count() <= 0) {
            return;
        }

        $this->tags->clear();
    }
}
