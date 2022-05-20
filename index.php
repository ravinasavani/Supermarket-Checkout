<?php
include_once('Dbconnect.php');
class Checkout extends Dbconnect
{
    function getItems()
    {
        $qry = "select DISTINCT(name) from tbl_item";
        $result = $this->connection->query($qry);
        return $result;
    }

    function executeQuery($qry, $assoc = False)
    {
        $result = $this->connection->query($qry);
        if ($assoc) {
            return $result->fetch_assoc();
        }
        return $result;
    }

    function calculatePrice($item, $qty, $allItem = array())
    {
        $checkDependentQry = "select dependent_on from tbl_item where name ='" . $item . "'";
        $isDependent = $this->executeQuery($checkDependentQry, False);
        $total = 0;
        // check for dependecy cases, e.g, price of D is 5 is perchased with A
        if ($isDependent->num_rows > 0) {
            // for each associated purchases
            while ($row = $isDependent->fetch_assoc()) {
                $depItem = $row['dependent_on'];
                if ($depItem != NULL) {
                    // reduce the current item's quanty by the amount of related purchase
                    foreach ($allItem as $key => $value) {
                        if ($key == $depItem) {
                            $itemPrice = "select price from tbl_item where name='" . $item . "' and dependent_on='" . $key . "'";
                            $price = $this->executeQuery($itemPrice, True);
                            // get right quantity
                            if ($qty < $value) {
                                $newQty = $qty;
                            } else {
                                $newQty = $value;
                            }
                            $total += $price['price'] * $newQty;
                            $qty = $qty - $newQty;
                            break;
                        }
                    }
                }
            }
        }
        // check the special prices based on quantities of item
        $qtyArr = "select qty from tbl_item where name='" . $item . "' order by qty desc";
        $qtyArrResult = $this->executeQuery($qtyArr, False);
        $qtysArr = array();
        if ($qtyArrResult->num_rows > 0) {
            while ($row = $qtyArrResult->fetch_assoc()) {
                $qtysArr[] = (int)$row['qty'];
            }
        }
        $i = 0;
        // calculate the total price of the item based on quantities
        while ($qty > 0) {
            if ($qty < $qtysArr[$i]) {
                $i++;
                continue;
            }
            $itemPrice = "select price from tbl_item where name='" . $item . "' and qty=$qtysArr[$i]";
            $price = $this->executeQuery($itemPrice, True);
            $total += $price['price'];
            $qty = $qty - $qtysArr[$i];
        }
        return $total;
    }
}
// when page is submitted for checkout
$item = new Checkout();
if (isset($_POST['CheckoutButton'])) {
    $numOfItem = count($_POST['item']);
    $checkoutTotal = 0;
    $allItem = array();
    // associative array of item and quantities
    for ($i = 0; $i < $numOfItem; $i++) {
        $allItem[$_POST['item'][$i]] = $_POST['qty'][$i];
    }
    // calculate the price of each item
    for ($i = 0; $i < $numOfItem; $i++) {
        $checkoutTotal += $item->calculatePrice($_POST['item'][$i], $_POST['qty'][$i], $allItem);
    }
}
if (isset($_SERVER['REQUEST_URI']) && strstr($_SERVER['REQUEST_URI'], "index.php")) {
    include('home.php');
}
