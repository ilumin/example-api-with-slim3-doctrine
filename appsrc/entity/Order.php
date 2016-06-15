<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="orders")
 */
class Order
{
    const STATUS_DRAFT      = 'draft';
    const STATUS_SUCCESS    = 'success';
    const STATUS_FAILED     = 'failed';
    const STATUS_EXPIRED    = 'expired';
    const STATUS_CANCEL     = 'cancel';

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
     * @ORM\Column(type="string", columnDefinition="ENUM('draft', 'success', 'failed', 'expired', 'cancel')")
     * @var string
     */
    public $status = 'draft';

    /**
     * @ORM\OneToMany(targetEntity="OrderItem", mappedBy="order", cascade="persist")
     * @var Collection|OrderItem[]
     */
    public $items;

    /**
     * @ORM\OneToOne(targetEntity="Cart")
     * @ORM\JoinColumn(name="cart_id", referencedColumnName="id")
     * @var Cart
     */
    public $cart;

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

    public function __construct(Cart $cart)
    {
        $cartItems = $cart->fetchItems();
        if (empty($cartItems)) {
            throw new \Exception('Cart cannot empty on create order.');
        }

        $currentDatetime = new \DateTime();
        $this->items = new ArrayCollection();
        $this->cart = $cart;

        foreach ($cartItems as $cartItem) {
            $variant = $cartItem->getVariant();
            $this->addItem($variant, $cartItem->quantity);
        }

        $this->createdAt = $currentDatetime;

        $this->updateData($currentDatetime);
    }

    public function getData()
    {
        return [
            'id' => $this->id,
            'total_price' => $this->totalPrice,
            'item_count' => $this->itemCount,
            'cart_id' => $this->cart->id,
            'items' => $this->items->toArray(),
        ];
    }

    private function updateData($datetime)
    {
        $totalPrice = 0;
        $itemCount = 0;

        foreach ($this->items as $orderItem) {
            $totalPrice += $orderItem->totalPrice;
            $itemCount += $orderItem->quantity;
        }

        $this->totalPrice = $totalPrice;
        $this->itemCount = $itemCount;
        $this->updatedAt = $datetime;
    }

    private function addItem($variant, $quantity)
    {
        $orderItem = new OrderItem($variant, $quantity);
        $this->items->add($orderItem);
    }
}
