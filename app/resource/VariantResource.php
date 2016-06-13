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
        try {
            $variant = new Variant($data);
            $variant = $this->setProduct($data['product_id'], $variant);

            $this->doctrine->persist($variant);
            $this->doctrine->flush();
            return $variant;
        }
        catch (\Exception $e) {
            throw new \Exception('Insert variant fail with (' . $e->getMessage() . ')');
        }
    }

    public function update($slug, $data)
    {
        try {
            $variantRepository = $this->getRepository('App\Entity\Variant');

            /** @var Variant $product */
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
            return $product;
        }
        catch (\Exception $e) {
            throw new \Exception('Update variant fail with (' . $e->getMessage() . ')');
        }
    }

    public function remove($slug)
    {
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
            return true;
        }
        catch (\Exception $e) {
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
