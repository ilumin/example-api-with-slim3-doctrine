<?php

namespace App\Resource;

use App\Core\AbstractResource;
use App\Core\ResourceInterface;
use App\Entity\Cart;
use App\Entity\Order;

class OrderResource extends AbstractResource implements ResourceInterface
{
    public function get($slug = null)
    {
        // TODO: Implement get() method.
    }

    public function create($data)
    {
        try {
            $cart = $this->getCart();
            $order = new Order($cart);

            $cart->markPublished();

            $this->doctrine->persist($cart);
            $this->doctrine->persist($order);
            $this->doctrine->flush();

            return $order->getData();
        }
        catch (\Exception $e) {
            throw new \Exception('Cannot create new order (' . $e->getMessage() . ').');
        }
    }

    public function update($slug, $data)
    {
        // TODO: Implement update() method.
    }

    public function remove($slug)
    {
        // TODO: Implement remove() method.
    }

    protected function getCart()
    {
        $cartRepository = $this->getRepository('App\Entity\Cart');
        /** @var Cart $cart */
        $cart = $cartRepository->findOneBy([
            'deletedAt' => null,
            'status' => Cart::STATUS_DRAFT,
        ]);

        if (!$cart) {
            return new Cart();
        }

        return $cart;
    }
}
