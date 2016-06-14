<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="carts")
 */
class Cart
{
    const STATUS_DRAFT      = 'draft';
    const STATUS_PUBLISHED  = 'published';
    const STATUS_DELETED    = 'deleted';

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @ORM\Column(type="integer")
     *
     * @var int
     */
    public $id;

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
    public $itemCount;

    /**
     * @ORM\Column(type="string", columnDefinition="ENUM('draft', 'deleted', 'published')")
     * @var string
     */
    public $status = 'draft';

    /**
     * @ORM\OneToMany(targetEntity="CartItem", mappedBy="cart", cascade="persist")
     * @var Collection|CartItem[]
     */
    public $items;

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

    public function __construct()
    {
        $this->totalPrice = 0;
        $this->itemCount = 0;
        $this->items = new ArrayCollection();

        $this->createdAt = new \DateTime();
        $this->updateCartData($this->createdAt);
    }

    public function addItem(Variant $variant, $quantity = 0)
    {
        /** @var ArrayCollection $cartItems */
        $cartItems = $this->items->filter(function($cartItem) use ($variant) {
            return $cartItem->getVariant()->id == $variant->id;
        });
        if ($cartItems->count() > 0) {
            /** @var CartItem $cartItem */
            $cartItem = $cartItems->first();
            $cartItem->update($variant, $quantity);
        }
        else {
            $cartItem = new CartItem($variant, $quantity);
            $cartItem->setCart($this);

            $this->items->add($cartItem);
        }

        $this->updateCartData(new \DateTime());

        return $this;
    }

    public function updateItem($variant, $quantity)
    {
        /** @var ArrayCollection $cartItems */
        $cartItems = $this->items->filter(function($cartItem) use ($variant) {
            return $cartItem->getVariant()->id == $variant->id;
        });
        if ($cartItems->count()<=0) {
            throw new \Exception('Item not exists in cart.');
        }

        $cartItem = $cartItems->first();
        $cartItem->update($variant, $quantity, false);

        $this->updateCartData(new \DateTime());

        return $this;
    }

    private function updateCartData($datetime)
    {
        $totalPrice = 0;
        $itemCount = 0;

        foreach ($this->items as $cartItem) {
            $totalPrice += $cartItem->totalPrice;
            $itemCount += $cartItem->quantity;
        }

        $this->totalPrice = $totalPrice;
        $this->itemCount = $itemCount;
        $this->updatedAt = $datetime;
    }

    public function getData()
    {
        return [
            'total_price' => $this->totalPrice,
            'item_count' => $this->itemCount,
            'items' => $this->items->toArray(),
        ];
    }
}
