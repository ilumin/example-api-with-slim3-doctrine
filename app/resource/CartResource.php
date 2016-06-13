<?php
namespace App\Resource;

use App\Core\AbstractResource;
use App\Core\ResourceInterface;

class CartResource extends AbstractResource implements ResourceInterface
{
    public function get($slug = null)
    {
        $cartRepository = $this->getRepository('App\Entity\Cart');
        return $cartRepository->findAll();
    }

    public function create($data)
    {
        // TODO: Implement create() method.
    }

    public function update($slug, $data)
    {
        // TODO: Implement update() method.
    }

    public function remove($slug)
    {
        // TODO: Implement remove() method.
    }
}
