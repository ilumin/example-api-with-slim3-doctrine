<?php

namespace Test\Unit\Resource;

use App\Entity\Cart;
use App\Entity\Variant;
use App\Resource\CartResource;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Mockery;
use Mockery\MockInterface;
use PHPUnit_Framework_TestCase;

class CartResourceTest extends PHPUnit_Framework_TestCase
{
    /**
     * Get empty cart should return an empty cart
     */
    public function testGetOnEmptyCart()
    {
        $cartEntity = new Cart();
        $expected = $cartEntity->getData();

        /** @var MockInterface|EntityRepository $cartRepository */
        $cartRepository = Mockery::mock('Doctrine\ORM\EntityRepository');
        $cartRepository->shouldReceive('findOneBy')
            ->with([
                'deletedAt' => null,
                'status' => Cart::STATUS_DRAFT,
            ])
            ->andReturn(null);

        /** @var MockInterface|EntityManagerInterface $doctrine */
        $doctrine = Mockery::mock('Doctrine\ORM\EntityManager');
        $doctrine->shouldReceive('getRepository')
            ->with('App\Entity\Cart')
            ->andReturn($cartRepository);

        $cart = new CartResource($doctrine);
        $result = $cart->get();

        $this->assertSame($expected, $result);
    }

    public function testGetExistCart()
    {
        $variant = new Variant([
            'name'  => 'dummy-name',
            'sku'   => 'dummy-sku',
            'price' => 1000,
        ]);
        $cartEntity = new Cart();
        $cartEntity->addItem($variant, 1);
        $expected = $cartEntity->getData();

        /** @var MockInterface|EntityRepository $cartRepository */
        $cartRepository = Mockery::mock('Doctrine\ORM\EntityRepository');
        $cartRepository->shouldReceive('findOneBy')
            ->with([
                'deletedAt' => null,
                'status' => Cart::STATUS_DRAFT,
            ])
            ->andReturn($cartEntity);

        /** @var MockInterface|EntityManagerInterface $doctrine */
        $doctrine = Mockery::mock('Doctrine\ORM\EntityManager');
        $doctrine->shouldReceive('getRepository')
            ->with('App\Entity\Cart')
            ->andReturn($cartRepository);

        $cart = new CartResource($doctrine);
        $result = $cart->get();

        $this->assertSame($expected, $result);
    }
}
