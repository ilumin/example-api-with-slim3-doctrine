<?php
namespace App\Resource;

use App\Core\AbstractResource;
use App\Core\ResourceInterface;
use App\Entity\Product;
use App\Entity\Variant;

class VariantResource extends AbstractResource implements ResourceInterface
{

    public function get($slug = null)
    {
        $variantRepository = $this->getRepository('App\Entity\Variant');
        $variant = $variantRepository->findOneBy([
            'slug' => $slug,
        ]);

        return $variant;
    }

    public function create($data)
    {
        $this->doctrine->getConnection()->beginTransaction();

        try {
            $variant = new Variant($data);
            $variant = $this->setProduct($data['product_id'], $variant);

            $this->doctrine->persist($variant);
            $this->doctrine->flush();
            $this->doctrine->getConnection()->commit();
            return $variant;
        }
        catch (\Exception $e) {
            $this->doctrine->getConnection()->rollBack();
            throw new \Exception('Insert variant fail with (' . $e->getMessage() . ')');
        }
    }

    public function update($slug, $data)
    {
        $this->doctrine->getConnection()->beginTransaction();

        try {
            $variantRepository = $this->getRepository('App\Entity\Variant');

            /** @var Variant $variant */
            $variant = $variantRepository->findOneBy([
                'slug' => $slug,
            ]);
            if (!$variant) {
                throw new \Exception('Variant not exist.');
            }

            $variant->name = $data['name'];
            $variant->price = $data['price'];
            $variant = $this->setProduct($data['product_id'], $variant);

            $this->doctrine->persist($variant);
            $this->doctrine->flush();
            $this->doctrine->getConnection()->commit();
            return $product;
        }
        catch (\Exception $e) {
            $this->doctrine->getConnection()->rollBack();
            throw new \Exception('Update variant fail with (' . $e->getMessage() . ')');
        }
    }

    public function remove($slug)
    {
        $this->doctrine->getConnection()->beginTransaction();

        try {
            $variantRepository = $this->getRepository('App\Entity\Variant');

            /** @var Variant $product */
            $variant = $variantRepository->findOneBy([
                'slug' => $slug,
            ]);
            if (!$variant) {
                throw new \Exception('Variant not exist.');
            }

            $this->doctrine->remove($variant);
            $this->doctrine->flush();
            $this->doctrine->getConnection()->commit();
            return true;
        }
        catch (\Exception $e) {
            $this->doctrine->getConnection()->rollBack();
            throw new \Exception('Remove product fail with (' . $e->getMessage() . ')');
        }
    }

    private function setProduct($product_id, Variant $variant)
    {
        if (empty($product_id)) {
            return $variant;
        }

        $productRepository = $this->getRepository('App\Entity\Product');
        /** @var Product $product */
        $product = $productRepository->findOneBy([
            'id' => $product_id,
        ]);
        if (!$product) {
            return $variant;
        }

        $variant->setProduct($product);
        return $variant;
    }
}
