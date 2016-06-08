<?php
namespace App\Resource;

use App\AbstractResource;
use App\Entity\Category;
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
            $this->setCategory($productData['category_id'], $product);

            $this->doctrine->persist($product);
            $this->doctrine->flush();
            return $product;
        }
        catch (\Exception $e) {
            throw new \Exception('Insert product fail with (' . $e->getMessage() . ')');
        }
    }

    public function update($slug, $productData)
    {
        try {
            /** @var EntityRepository $productEntity */
            $productEntity = $this->doctrine->getRepository('App\Entity\Product');

            /** @var Product $product */
            $product = $productEntity->findOneBy([
                'slug' => $slug,
            ]);
            if (!$product) {
                throw new \Exception('Product not exist.');
            }

            $product->name = $productData['name'];
            $product->price = $productData['price'];

            $this->setCategory($productData['category_id'], $product);

            $this->doctrine->persist($product);
            $this->doctrine->flush();
            return $product;
        }
        catch (\Exception $e) {
            throw new \Exception('Update product fail with (' . $e->getMessage() . ')');
        }
    }

    /**
     * @param $categoryId
     * @param $product
     */
    public function setCategory($categoryId, $product)
    {
        if (empty($categoryId)) {
            return $product;
        }

        /** @var EntityRepository $productEntity */
        $categoryEntity = $this->doctrine->getRepository('App\Entity\Category');

        /** @var Category $category */
        $category = $categoryEntity->find($categoryId);
        $product->setCategory($category);

        return $product;
    }
}
