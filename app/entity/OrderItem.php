<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="order_items")
 */
class OrderItem
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
     * @ORM\ManyToOne(targetEntity="Order", inversedBy="items")
     *
     * @var Cart
     */
    protected $order;

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
}
