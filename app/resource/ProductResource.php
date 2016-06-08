<?php
namespace App\Resource;

use App\AbstractResource;
use App\Entity\Product;
use Doctrine\ORM\EntityRepository;

class ProductResource extends AbstractResource
{
    public function get($slug = null)
    {
        /** @var EntityRepository $productEntity */
        $productEntity = $this->doctrine->getRepository('App\Entity\Product');
        if (empty($slug)) {
            $products = $productEntity->findAll();
            $products = array_map(
                function ($product) {
                    return $product->getData();
                },
                $products
            );

            return $products;
        }
        else {
            /** @var Product $product */
            $product = $productEntity->findOneBy(array(
                'slug' => $slug,
            ));

            if ($product) {
                return $product->getData();
            }
        }

        return false;
    }

    public function create($productData)
    {
        try {
            $product = new Product($productData);

            if (isset($productData['category_id'])) {
                /** @var EntityRepository $productEntity */
                $categoryEntity = $this->doctrine->getRepository('App\Entity\Category');
                $category = $categoryEntity->find($productData['category_id']);
                $product->setCategory($category);
            }

            $this->doctrine->persist($product);
            $this->doctrine->flush();
            return $product;
        }
        catch (\Exception $e) {
            throw new \Exception('Insert product fail with (' . $e->getMessage() . ')');
        }
    }
}
