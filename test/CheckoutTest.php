<?php
require 'index.php';
use PHPUnit\Framework\TestCase;
class CheckoutTest extends TestCase
{
    private $item;
    public $total;

    function setUp():void
    {
        $this->item = new Checkout();
    }

    function tearDown():void
    {
        $this->item = NULL;
    }

    public function resetTotalVal()
    {
        return $this->total = 0;
    }
    public function testCalculatePrice()
    {
        // Testing for individual items with different quantities
        $this->assertEquals(130, $this->item->calculatePrice('A', 3));

        $this->assertEquals(440, $this->item->calculatePrice('A', 10));

        $this->assertNotEquals(80, $this->item->calculatePrice('B', 3));

        $this->assertEquals(174, $this->item->calculatePrice('C', 9));

        $this->assertEquals(15, $this->item->calculatePrice('D', 1));
    }

    public function testCalculatePriceMultipleItem()
    {
        // Testing for item A, B and C with different quantity
        $data = array(
            'A' => 3,
            'B' => 5,
            'C' => 15,
        );
        $total = $this->resetTotalVal();
        foreach ($data as $key => $value) {
            $total += $this->item->calculatePrice($key, $value, $data);
        }
        $this->assertEquals(540, $total);
    }

    public function testCalculatePriceMultipleItemWithDependency()
    {
        //For item A,B and C with different quantity and also check item dependency
        $data = array(
            'A' => 2,
            'C' => 3,
            'D' => 5
        );
        $total = $this->resetTotalVal();
        foreach ($data as $key => $value) {
            $total += $this->item->calculatePrice($key, $value, $data);
        }
        $this->assertEquals(213, $total);

        $data = array(
            'A' => 5,
            'D' => 1
        );
        $total = $this->resetTotalVal();
        foreach ($data as $key => $value) {
            $total += $this->item->calculatePrice($key, $value, $data);
        }
        $this->assertEquals(235, $total);
    }
}