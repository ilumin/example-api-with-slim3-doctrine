<?php
namespace App\Resource;

use App\Core\AbstractResource;
use App\Core\ResourceInterface;
use App\Entity\Cart;
use App\Entity\Variant;

class CartResource extends AbstractResource implements ResourceInterface
{
    public function get($slug = null)
    {
        $cart = $this->getCurrentCart();
        return $cart->getData();
    }

    public function create($data)
    {
        $this->doctrine->getConnection()->beginTransaction();

        try {
            if (empty($data)) {
                throw new \Exception('Add item required item\'s id and quantity.');
            }

            /** @var Variant $variant */
            $variant = $this->getVariant(array_get($data, 'id'));

            $cart = $this->getCurrentCart();
            $cart->addItem($variant, array_get($data, 'quantity'));

            $this->doctrine->persist($cart);
            $this->doctrine->flush();
            $this->doctrine->getConnection()->commit();

            return $cart->getData();
        }
        catch (\Exception $e) {
            $this->doctrine->getConnection()->rollBack();
            throw new \Exception('Cannot add item to cart (' . $e->getMessage() . ').');
        }
    }

    public function update($variant_id = null, $data)
    {
        $this->doctrine->getConnection()->beginTransaction();

        try {
            if (empty($data)) {
                throw new \Exception('Update item required item\'s id and quantity.');
            }

            $variant = $this->getVariant($variant_id);
            $cart = $this->getCurrentCart();
            $cart->updateItem($variant, array_get($data, 'quantity'));

            $this->doctrine->persist($cart);
            $this->doctrine->flush();
            $this->doctrine->getConnection()->commit();

            return $cart->getData();
        }
        catch (\Exception $e) {
            $this->doctrine->getConnection()->rollBack();
            throw new \Exception('Cannot update item on cart (' . $e->getMessage() . ').');
        }
    }

    public function remove($variant_id = null)
    {
        $this->doctrine->getConnection()->beginTransaction();

        try {
            $variant = $this->getVariant($variant_id);
            $cart = $this->getCurrentCart();
            $cart->removeItem($variant);

            $this->doctrine->persist($cart);
            $this->doctrine->flush();
            $this->doctrine->getConnection()->commit();

            return $cart->getData();
        }
        catch (\Exception $e) {
            $this->doctrine->getConnection()->rollBack();
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
