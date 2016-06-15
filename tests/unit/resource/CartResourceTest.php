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
     * @var MockInterface|EntityManagerInterface
     */
    public $doctrine;

    protected function mockTransaction($option = [])
    {
        $connection = Mockery::mock('Doctrine\DBAL\Connection');
        $connection->shouldReceive('beginTransaction');

        if (in_array('commit', $option)) {
            $connection->shouldReceive('commit');
        }

        if (in_array('rollback', $option)) {
            $connection->shouldReceive('rollBack');
        }

        $this->doctrine->shouldReceive('getConnection')->andReturn($connection);
    }

    protected function mockGetVariant($variant_id, $returnValue = null)
    {
        /** @var MockInterface|EntityRepository $variantRepository */
        $variantRepository = Mockery::mock('Doctrine\ORM\EntityRepository');
        $variantRepository->shouldReceive('findOneBy')
            ->with([
                'id' => $variant_id,
            ])
            ->andReturn($returnValue);

        $this->doctrine->shouldReceive('getRepository')
            ->with('App\Entity\Variant')
            ->andReturn($variantRepository);
    }

    protected function mockGetCart($returnValue = null)
    {
        /** @var MockInterface|EntityRepository $cartRepository */
        $cartRepository = Mockery::mock('Doctrine\ORM\EntityRepository');
        $cartRepository->shouldReceive('findOneBy')
            ->with([
                'deletedAt' => null,
                'status' => Cart::STATUS_DRAFT,
            ])
            ->andReturn($returnValue);

        $this->doctrine->shouldReceive('getRepository')
            ->with('App\Entity\Cart')
            ->andReturn($cartRepository);
    }

    protected function mockSaveData()
    {
        $this->doctrine->shouldReceive('persist');
        $this->doctrine->shouldReceive('flush');
    }

    public function setUp()
    {
        parent::setUp();

        $this->doctrine = Mockery::mock('Doctrine\ORM\EntityManager');
    }

    public function tearDown()
    {
        parent::tearDown();

        unset($this->doctrine);
    }

    /**
     * Get empty cart should return an empty cart
     */
    public function testGetOnEmptyCart()
    {
        $cartEntity = new Cart();
        $expected = $cartEntity->getData();
        $mockCart = null;

        $this->mockGetCart($mockCart);

        $cart = new CartResource($this->doctrine);
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

        $this->mockGetCart($cartEntity);

        $cart = new CartResource($this->doctrine);
        $result = $cart->get();

        $this->assertSame($expected, $result);
    }

    /**
     * @expectedException \Exception
     * @expectedExceptionMessage Cannot add item to cart (Add item required item's id and quantity.).
     */
    public function testCreateThrowOnEmptyData()
    {
        $this->mockTransaction([
            'rollback',
        ]);

        $data = [];
        $cart = new CartResource($this->doctrine);
        $cart->create($data);
    }

    public function testAddItemToEmptyCart()
    {
        $this->mockTransaction([
            'commit',
        ]);

        $variant_id = 1;
        $mockVariant = new Variant([
            'name'  => 'dummy',
            'sku'   => '100',
            'price' => 100,
        ]);

        $mockCart = new Cart();

        $expected = [
            'total_price' => 100,
            'item_count' => 1,
            'item_total_price' => 100,
            'item_quantity' => 1,
        ];

        $this->mockGetVariant($variant_id, $mockVariant);
        $this->mockGetCart($mockCart);
        $this->mockSaveData();

        $data = [
            'id' => 1,
            'quantity' => 1,
        ];
        $cart = new CartResource($this->doctrine);
        $result = $cart->create($data);

        $cartItem = array_pop($result['items']);
        $this->assertSame( $expected['total_price'], $result['total_price'] );
        $this->assertSame( $expected['item_count'], $result['item_count'] );
        $this->assertSame( $expected['item_total_price'], $cartItem->totalPrice );
        $this->assertSame( $expected['item_quantity'], $cartItem->quantity );
    }
}
