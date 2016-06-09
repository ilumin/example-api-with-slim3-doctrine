<?php
namespace App\Resource;

use App\AbstractResource;
use App\Entity\Category;
use Doctrine\ORM\EntityRepository;

class CategoryResource extends AbstractResource
{
    public function get($slug = null)
    {
        /** @var EntityRepository $categoryEntity */
        $categoryEntity = $this->doctrine->getRepository('App\Entity\Category');
        if (empty($slug)) {
            $categories = $categoryEntity->findAll();
            $categories = array_map(function($category) {
                return $category->getData();
            }, $categories);
            return $categories;
        }
        else{
            /** @var Category $category */
            $category = $categoryEntity->findOneBy(array(
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
            /** @var EntityRepository $categoryEntity */
            $categoryEntity = $this->doctrine->getRepository('App\Entity\Category');

            /** @var Category $category */
            $category = $categoryEntity->findOneBy([
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
            /** @var EntityRepository $categoryEntity */
            $categoryEntity = $this->doctrine->getRepository('App\Entity\Category');

            /** @var Category $category */
            $category = $categoryEntity->findOneBy([
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
