<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="cart_items")
 */
class CartItem
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
     * @ORM\Column(type="float")
     *
     * @var float
     */
    public $price;

    /**
     * @ORM\Column(type="float")
     *
     * @var float
     */
    public $totalPrice;

    /**
     * @ORM\Column(type="integer")
     *
     * @var int
     */
    public $quantity;

    /**
     * @ORM\ManyToOne(targetEntity="Variant")
     *
     * @var Variant
     */
    protected $variant;

    /**
     * @ORM\ManyToOne(targetEntity="Cart", inversedBy="items")
     *
     * @var Cart
     */
    protected $cart;

    /**
     * @ORM\Column(type="datetime", name="created_at")
     *
     * @var \DateTime
     */
    protected $createdAt;

    /**
     * @ORM\Column(type="datetime", name="updated_at")
     *
     * @var \DateTime
     */
    protected $updatedAt;

    /**
     * @ORM\Column(type="datetime", name="deleted_at", nullable=true)
     *
     * @var \DateTime
     */
    protected $deletedAt;

    public function __construct(Variant $variant, $quantity)
    {
        $this->quantity = $quantity;
        $this->variant = $variant;
        $this->name = $variant->name;
        $this->price = $variant->price;
        $this->createdAt = new \DateTime();

        $this->updateCartItemData($this->createdAt);
    }

    public function update(Variant $variant, $quantity, $addition = true)
    {
        $this->price = $variant->price;
        $this->quantity = $addition
            ? $this->quantity + $quantity
            : $quantity;

        $this->updateCartItemData(new \DateTime());
    }

    public function updateCartItemData($datetime)
    {
        $this->totalPrice = $this->price * $this->quantity;
        $this->updatedAt = $datetime;
    }

    public function setCart(Cart $cart)
    {
        $this->cart = $cart;
    }

    public function getVariant()
    {
        return $this->variant;
    }

    public function getCart()
    {
        return $this->cart;
    }
}
