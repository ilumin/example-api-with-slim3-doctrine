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
        try {
            $product = new Product($productData);
            $product = $this->setCategory($productData['category_id'], $product);
            $product = $this->setTags($productData['tags'], $product);

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

            $product = $this->setCategory($productData['category_id'], $product);
            $product = $this->setTags($productData['tags'], $product);

            $this->doctrine->persist($product);
            $this->doctrine->flush();
            return $product;
        }
        catch (\Exception $e) {
            throw new \Exception('Update product fail with (' . $e->getMessage() . ')');
        }
    }

    public function remove($slug)
    {
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
            return true;
        }
        catch (\Exception $e) {
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
