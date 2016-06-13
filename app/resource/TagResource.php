<?php
namespace App\Resource;


use App\AbstractResource;
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
        try {
            $tag = new Tag($data);
            $this->doctrine->persist($tag);
            $this->doctrine->flush();
            return $tag;
        }
        catch (\Exception $e) {
            throw new \Exception('Insert tag fail with (' . $e->getMessage() . ')');
        }
    }

    public function update($slug, $data)
    {
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
            return $tag;
        }
        catch (\Exception $e) {
            throw new \Exception('Update tag fail with (' . $e->getMessage() . ')');
        }
    }

    public function remove($slug)
    {
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
            return true;
        }
        catch (\Exception $e) {
            throw new \Exception('Remove tag fail with (' . $e->getMessage() . ')');
        }
    }
}
