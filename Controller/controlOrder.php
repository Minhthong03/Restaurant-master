<?php
include_once("Model/modelOrder.php");

class controlOrder {
    public function getAllOrders() {
        $p = new modelOrder();
        $kq = $p->selectAllOrders();
        return (mysqli_num_rows($kq) > 0) ? $kq : false;
    }
}
?>
