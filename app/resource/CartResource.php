<?php
namespace App\Resource;

use App\Core\AbstractResource;
use App\Core\ResourceInterface;
use App\Entity\Cart;

class CartResource extends AbstractResource implements ResourceInterface
{
    public function get($slug = null)
    {
        $cart = $this->getCurrentCart();
        return $cart->getData();
    }

    public function create($data)
    {
        try {
            $cart = $this->addItem($data);
            $this->doctrine->persist($cart);
            $this->doctrine->flush();

            return $cart->getData();
        }
        catch (\Exception $e) {
            throw new \Exception('Cannot add item to cart (' . $e->getMessage() . ').');
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

    protected function addItem($data)
    {
        if (empty($data)) {
            throw new \Exception('Required item\'s id and quantity.');
        }

        $cart = $this->getCurrentCart();

        $product = $this->getVariant($data['id']);
        $cart->addItem($product, $data['quantity']);

        return $cart;
    }

    protected function getCurrentCart()
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

    protected function getVariant($variant_id)
    {
        $variantRepository = $this->getRepository('App\Entity\Variant');
        $variant = $variantRepository->findOneBy([ 'id' => $variant_id ]);
        if (empty($variant)) {
            throw new \Exception('Required variant.');
        }

        return $variant;
    }
}
