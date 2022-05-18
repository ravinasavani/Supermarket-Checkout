<?php
require '../index.php';

class CheckoutTest
{
    private $item;
    public $total;

    function __construct()
    {
        $this->item = new Checkout();
    }

    function __destruct()
    {
        $this->item = NULL;
    }

    protected function successMsg($arg1, $arg2)
    {
        return "<span style='color:green; margin-left: 20px;'>Test Suceesful!</span><br/>";
    }

    protected function errorMsg($arg1, $arg2)
    {
        return "<span style='color:red; margin-left: 20px;'>Test Error: Result value should be " . $arg2 . " but found " . $arg1 . "</span> <br/>";
    }

    protected function assertEquals($arg1, $arg2)
    {
        if ($arg1 == $arg2) {
            print $this->successMsg($arg1, $arg2);
        } else {
            print $this->errorMsg($arg1, $arg2);
        }
    }

    protected function assertNotEquals($arg1, $arg2)
    {
        if ($arg1 != $arg2) {
            print $this->successMsg($arg1, $arg2);
        } else {
            print $this->errorMsg($arg1, $arg2);
        }
    }

    public function resetTotalVal()
    {
        return $this->total = 0;
    }
    public function testCalculatePrice()
    {
        // Testing for individual items with different quantities

        echo 'Testing for (A, 3)';
        $this->assertEquals(130, $this->item->calculatePrice('A', 3));

        echo 'Testing for (A, 10)';
        $this->assertEquals(440, $this->item->calculatePrice('A', 10));

        echo 'Testing for (B, 3)';
        $this->assertNotEquals(80, $this->item->calculatePrice('B', 3));

        echo 'Testing for (C, 9)';
        $this->assertEquals(174, $this->item->calculatePrice('C', 9));

        echo 'Testing for (D, 1)';
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
        echo 'Testing for (A, 3), (B, 5) and (C, 15)';
        $total = $this->resetTotalVal();
        foreach ($data as $key => $value) {
            $total += $this->item->calculatePrice($key, $value, $data);
        }
        $this->assertEquals(540, $total);
    }

    public function testCalculatePriceMultipleItemWithDependency()
    {
        //For item A,B and C with different quantity and also check item dependency
        echo 'Testing for (A, 2), (C, 3) and (D, 5)';
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
    }
}

$test = new CheckoutTest();
$test->testCalculatePrice();
$test->testCalculatePriceMultipleItem();
$test->testCalculatePriceMultipleItemWithDependency();
