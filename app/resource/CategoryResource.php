<?php
namespace App\Resource;

use App\Core\AbstractResource;
use App\Core\ResourceInterface;
use App\Entity\Category;

class CategoryResource extends AbstractResource implements ResourceInterface
{
    public function get($slug = null)
    {
        $categoryRepository = $this->getRepository('App\Entity\Category');
        if (empty($slug)) {
            $categories = $categoryRepository->findAll();
            $categories = array_map(function($category) {
                return $category->getData();
            }, $categories);
            return $categories;
        }
        else{
            /** @var Category $category */
            $category = $categoryRepository->findOneBy(array(
                'slug' => $slug,
            ));

            if ($category) {
                return $category->getFullData();
            }
        }

        return false;
    }

    public function create($categoryData)
    {
        try {
            $category = new Category($categoryData);

            $this->doctrine->persist($category);
            $this->doctrine->flush();
            return $category;
        }
        catch (\Exception $e) {
            throw new \Exception('Insert category fail with (' . $e->getMessage() . ')');
        }
    }

    public function update($slug, $categoryData)
    {
        try {
            $categoryRepository = $this->getRepository('App\Entity\Category');

            /** @var Category $category */
            $category = $categoryRepository->findOneBy([
                'slug' => $slug,
            ]);
            if (!$category) {
                throw new \Exception('Category not exist.');
            }

            $category->name = $categoryData['name'];

            $this->doctrine->persist($category);
            $this->doctrine->flush();
            return $category;
        }
        catch (\Exception $e) {
            throw new \Exception('Update category fail with (' . $e->getMessage() . ')');
        }
    }

    public function remove($slug)
    {
        try {
            $categoryRepository = $this->getRepository('App\Entity\Category');

            /** @var Category $category */
            $category = $categoryRepository->findOneBy([
                'slug' => $slug,
            ]);
            if (!$category) {
                throw new \Exception('Category not exist.');
            }

            $this->doctrine->remove($category);
            $this->doctrine->flush();
            return true;
        }
        catch (\Exception $e) {
            throw new \Exception('Remove category fail with (' . $e->getMessage() . ')');
        }
    }
}
