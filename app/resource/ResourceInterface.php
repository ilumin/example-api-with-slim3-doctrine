<?php
namespace App\Resource;

interface ResourceInterface
{
    public function get($slug = null);

    public function create($data);

    public function update($slug, $data);

    public function remove($slug);
}
