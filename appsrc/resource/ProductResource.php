<?php
namespace App\Resource;

use App\Core\AbstractResource;
use App\Core\ResourceInterface;
use App\Entity\Category;
use App\Entity\Product;

class ProductResource extends AbstractResource implements ResourceInterface
{
    public function get($slug = null)
    {
        $productRepository = $this->getRepository('App\Entity\Product');
        if (empty($slug)) {
            $products = $productRepository->findAll();
            $products = array_map(function ($product) {
                return $product->getData();
            }, $products);

            return $products;
        }
        else {
            /** @var Product $product */
            $product = $productRepository->findOneBy(array(
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
        $this->doctrine->getConnection()->beginTransaction();

        try {
            $product = new Product($productData);
            $product = $this->setCategory(array_get($productData, 'category_id'), $product);
            $product = $this->setTags(array_get($productData, 'tags'), $product);

            $this->doctrine->persist($product);
            $this->doctrine->flush();
            $this->doctrine->getConnection()->commit();
            return $product;
        }
        catch (\Exception $e) {
            $this->doctrine->getConnection()->rollBack();
            throw new \Exception('Insert product fail with (' . $e->getMessage() . ')');
        }
    }

    public function update($slug, $productData)
    {
        $this->doctrine->getConnection()->beginTransaction();

        try {
            $productRepository = $this->getRepository('App\Entity\Product');

            /** @var Product $product */
            $product = $productRepository->findOneBy([
                'slug' => $slug,
            ]);
            if (!$product) {
                throw new \Exception('Product not exist.');
            }

            $product->name = $productData['name'];
            $product->price = $productData['price'];

            $product = $this->setCategory(array_get($productData, 'category_id'), $product);
            $product = $this->setTags(array_get($productData, 'tags'), $product);

            $this->doctrine->persist($product);
            $this->doctrine->flush();
            $this->doctrine->getConnection()->commit();
            return $product;
        }
        catch (\Exception $e) {
            $this->doctrine->getConnection()->rollBack();
            throw new \Exception('Update product fail with (' . $e->getMessage() . ')');
        }
    }

    public function remove($slug)
    {
        $this->doctrine->getConnection()->beginTransaction();

        try {
            $productRepository = $this->getRepository('App\Entity\Product');

            /** @var Product $product */
            $product = $productRepository->findOneBy([
                'slug' => $slug,
            ]);
            if (!$product) {
                throw new \Exception('Product not exist.');
            }

            $this->doctrine->remove($product);
            $this->doctrine->flush();
            $this->doctrine->getConnection()->commit();
            return true;
        }
        catch (\Exception $e) {
            $this->doctrine->getConnection()->rollBack();
            throw new \Exception('Remove product fail with (' . $e->getMessage() . ')');
        }
    }

    /**
     * @param         $categoryId
     * @param Product $product
     *
     * @return Product
     */
    public function setCategory($categoryId, Product $product)
    {
        if (empty($categoryId)) {
            return $product;
        }

        $categoryRepository = $this->getRepository('App\Entity\Category');

        /** @var Category $category */
        $category = $categoryRepository->find($categoryId);
        $product->setCategory($category);

        return $product;
    }

    private function setTags($tags, Product $product)
    {
        if (empty($tags)){
            return $product;
        }

        $tagRepository = $this->getRepository('App\Entity\Tag');
        $tags = $tagRepository->findBy([
            'id' => $tags,
        ]);
        if (!$tags) {
            return $product;
        }

        $product->removeAllTag();
        foreach ($tags as $tag) {
            $product->addTag($tag);
        }

        return $product;
    }
}
