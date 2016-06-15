<?php
namespace App\Resource;

use App\Core\AbstractResource;
use App\Core\ResourceInterface;
use App\Entity\Tag;

class TagResource extends AbstractResource implements ResourceInterface
{
    public function get($slug = null)
    {
        $tagRepository = $this->getRepository('App\Entity\Tag');
        if (empty($slug)) {
            $tags = $tagRepository->findAll();
            return $tags;
        }
        else {
            $tag = $tagRepository->findOneBy([
                'slug' => $slug,
            ]);

            if ($tag) {
                return $tag;
            }
        }

        return false;
    }

    public function create($data)
    {
        $this->doctrine->getConnection()->beginTransaction();

        try {
            $tag = new Tag($data);
            $this->doctrine->persist($tag);
            $this->doctrine->flush();
            $this->doctrine->getConnection()->commit();
            return $tag;
        }
        catch (\Exception $e) {
            $this->doctrine->getConnection()->rollBack();
            throw new \Exception('Insert tag fail with (' . $e->getMessage() . ')');
        }
    }

    public function update($slug, $data)
    {
        $this->doctrine->getConnection()->beginTransaction();

        try {
            $tagRepository = $this->getRepository('App\Entity\Tag');

            /** @var Tag $tag */
            $tag = $tagRepository->findOneBy([
                'slug' => $slug,
            ]);
            if (!$tag) {
                throw new \Exception('Tag not exist.');
            }

            $tag->name = $tagRepository['name'];

            $this->doctrine->persist($tag);
            $this->doctrine->flush();
            $this->doctrine->getConnection()->commit();
            return $tag;
        }
        catch (\Exception $e) {
            $this->doctrine->getConnection()->rollBack();
            throw new \Exception('Update tag fail with (' . $e->getMessage() . ')');
        }
    }

    public function remove($slug)
    {
        $this->doctrine->getConnection()->beginTransaction();

        try {
            $tagRepository = $this->getRepository('App\Entity\Tag');

            /** @var Tag $tag */
            $tag = $tagRepository->findOneBy([
                'slug' => $slug,
            ]);
            if (!$tag) {
                throw new \Exception('Tag not exist.');
            }

            $this->doctrine->remove($tag);
            $this->doctrine->flush();
            $this->doctrine->getConnection()->commit();
            return true;
        }
        catch (\Exception $e) {
            $this->doctrine->getConnection()->rollBack();
            throw new \Exception('Remove tag fail with (' . $e->getMessage() . ')');
        }
    }
}
