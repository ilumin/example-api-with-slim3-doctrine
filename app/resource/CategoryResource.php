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
}
