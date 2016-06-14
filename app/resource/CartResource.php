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
            if (empty($data)) {
                throw new \Exception('Add item required item\'s id and quantity.');
            }

            $product = $this->getVariant($data['id']);

            $cart = $this->getCurrentCart();
            $cart->addItem($product, $data['quantity']);

            $this->doctrine->persist($cart);
            $this->doctrine->flush();

            return $cart->getData();
        }
        catch (\Exception $e) {
            throw new \Exception('Cannot add item to cart (' . $e->getMessage() . ').');
        }
    }

    public function update($variant_id = null, $data)
    {
        try {
            if (empty($data)) {
                throw new \Exception('Update item required item\'s id and quantity.');
            }

            $variant = $this->getVariant($variant_id);
            $cart = $this->getCurrentCart();
            $cart->updateItem($variant, $data['quantity']);

            $this->doctrine->persist($cart);
            $this->doctrine->flush();

            return $cart->getData();
        }
        catch (\Exception $e) {
            throw new \Exception('Cannot update item on cart (' . $e->getMessage() . ').');
        }
    }

    public function remove($variant_id = null)
    {
        try {
            $variant = $this->getVariant($variant_id);
            $cart = $this->getCurrentCart();
            $cart->removeItem($variant);

            $this->doctrine->persist($cart);
            $this->doctrine->flush();

            return $cart->getData();
        }
        catch (\Exception $e) {
            throw new \Exception('Cannot remove item from cart (' . $e->getMessage() . ').');
        }
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
