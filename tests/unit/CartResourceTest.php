<?php

use App\Entity\Cart;
use App\Entity\Variant;
use App\Resource\CartResource;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Mockery\MockInterface;

class CartResourceTest extends \Codeception\Test\Unit
{
    /**
     * @var MockInterface|EntityManagerInterface
     */
    protected $doctrine;
    /**
     * @var \UnitTester
     */
    protected $tester;

    protected function _before()
    {
        $this->doctrine = Mockery::mock('Doctrine\ORM\EntityManager');
    }

    protected function _after()
    {
    }

    protected function mockTransaction($option)
    {
        $connection = Mockery::mock('Doctrine\DBAL\Connection');
        $connection->shouldReceive('beginTransaction');

        if ($option == 'commit') {
            $connection->shouldReceive('commit');
        }

        if ($option == 'rollback') {
            $connection->shouldReceive('rollBack');
        }

        $this->doctrine->shouldReceive('getConnection')->andReturn($connection);
    }

    protected function mockGetVariant($returnValue = null)
    {
        /** @var MockInterface|EntityRepository $variantRepository */
        $variantRepository = Mockery::mock('Doctrine\ORM\EntityRepository');
        $variantRepository->shouldReceive('findOneBy')
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
        $this->mockTransaction('rollback');

        $data = [];
        $cart = new CartResource($this->doctrine);
        $cart->create($data);
    }

    public function testAddItemToEmptyCart()
    {
        $variantEntity = new Variant([
            'name'  => 'dummy',
            'sku'   => '100',
            'price' => 100,
        ]);
        $cartEntity = null;

        $expected = [
            'total_price' => 100,
            'item_count' => 1,
            'item_total_price' => 100,
            'item_quantity' => 1,
        ];

        $this->mockTransaction('commit');
        $this->mockGetVariant($variantEntity);
        $this->mockGetCart($cartEntity);
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

    public function testAddSameItemToExistsCart()
    {
        $variantEntity = new Variant([
            'name'  => 'dummy',
            'sku'   => '100',
            'price' => 100,
        ]);

        $cartEntity = new Cart();
        $cartEntity->addItem($variantEntity, 1);

        $expected = [
            'total_price' => 200,
            'item_count' => 2,
            'item_total_price' => 200,
            'item_quantity' => 2,
        ];

        $this->mockTransaction('commit');
        $this->mockGetVariant($variantEntity);
        $this->mockGetCart($cartEntity);
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

    public function testAddDifferenceItemToExistsCart()
    {
        $variantEntityA = new Variant([
            'name'  => 'dummy',
            'sku'   => '100',
            'price' => 200,
        ]);
        $variantEntityA->id = 1;

        $variantEntityB = new Variant([
            'name'  => 'dummy',
            'sku'   => '100',
            'price' => 300,
        ]);
        $variantEntityB->id = 2;

        $cartEntity = new Cart();
        $cartEntity->addItem($variantEntityA, 1);

        $expected = [
            'total_price' => 800,
            'item_count' => 3,
            'itemA_total_price' => 200,
            'itemA_quantity' => 1,
            'itemB_total_price' => 600,
            'itemB_quantity' => 2,
        ];

        $this->mockTransaction('commit');
        $this->mockGetVariant($variantEntityB);
        $this->mockGetCart($cartEntity);
        $this->mockSaveData();

        $data = [
            'id' => 2,
            'quantity' => 2,
        ];
        $cart = new CartResource($this->doctrine);
        $result = $cart->create($data);

        $this->assertSame( $expected['total_price'], $result['total_price'] );
        $this->assertSame( $expected['item_count'], $result['item_count'] );

        $cartItem = array_pop($result['items']);
        $this->assertSame( $expected['itemB_total_price'], $cartItem->totalPrice );
        $this->assertSame( $expected['itemB_quantity'], $cartItem->quantity );

        $cartItem = array_pop($result['items']);
        $this->assertSame( $expected['itemA_total_price'], $cartItem->totalPrice );
        $this->assertSame( $expected['itemA_quantity'], $cartItem->quantity );
    }

    /**
     * @expectedException \Exception
     * @expectedExceptionMessage Cannot update item on cart (Update item required item's id and quantity.).
     */
    public function testUpdateNoData()
    {
        $this->mockTransaction('rollback');

        $variant_id = 1;
        $data = [];
        $cart = new CartResource($this->doctrine);
        $cart->update($variant_id, $data);
    }

    /**
     * @expectedException \Exception
     * @expectedExceptionMessage Cannot update item on cart (Required variant.).
     */
    public function testUpdateNotExistItem()
    {
        $this->mockTransaction('rollback');
        $this->mockGetVariant(null);

        $variant_id = 1;
        $data = [
            'quantity' => 1,
        ];
        $cart = new CartResource($this->doctrine);
        $cart->update($variant_id, $data);
    }

    public function testUpdateItem()
    {
        $variantEntity = new Variant([
            'name'  => 'dummy',
            'sku'   => '100',
            'price' => 100,
        ]);
        $variantEntity->id = 1;

        $cartEntity = new Cart();
        $cartEntity->addItem($variantEntity, 1);

        $expected = [
            'total_price' => 500,
            'item_count' => 5,
            'item_total_price' => 500,
            'item_quantity' => 5,
        ];

        $this->mockTransaction('commit');
        $this->mockGetVariant($variantEntity);
        $this->mockGetCart($cartEntity);
        $this->mockSaveData();

        $variant_id = 1;
        $data = [
            'quantity' => 5,
        ];
        $cart = new CartResource($this->doctrine);
        $result = $cart->update($variant_id, $data);

        $cartItem = array_pop($result['items']);
        $this->assertSame( $expected['total_price'], $result['total_price'] );
        $this->assertSame( $expected['item_count'], $result['item_count'] );
        $this->assertSame( $expected['item_total_price'], $cartItem->totalPrice );
        $this->assertSame( $expected['item_quantity'], $cartItem->quantity );
    }

    /**
     * @expectedException \Exception
     * @expectedExceptionMessage Cannot remove item from cart (Required variant.).
     */
    public function testRemoveNotExistsItem()
    {
        $this->mockTransaction('rollback');
        $this->mockGetVariant(null);

        $variant_id = 1;
        $cart = new CartResource($this->doctrine);
        $cart->remove($variant_id);
    }

    public function testRemoveItem()
    {
        $variantEntity = new Variant([
            'name'  => 'dummy',
            'sku'   => '100',
            'price' => 100,
        ]);
        $variantEntity->id = 1;

        $cartEntity = new Cart();
        $cartEntity->addItem($variantEntity, 1);

        $expected = [
            'total_price' => 0,
            'item_count' => 0,
        ];

        $this->mockTransaction('commit');
        $this->mockGetVariant($variantEntity);
        $this->mockGetCart($cartEntity);
        $this->mockSaveData();

        $variant_id = 1;
        $cart = new CartResource($this->doctrine);
        $result = $cart->remove($variant_id);

        $this->assertSame( $expected['total_price'], $result['total_price'] );
        $this->assertSame( $expected['item_count'], $result['item_count'] );
    }
}
