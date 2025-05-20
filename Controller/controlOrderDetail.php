<?php
include_once("Model/modelOrderDetail.php");

class controlOrderDetail {
    public function getOrderDetailsByOrderId($orderId) {
        $p = new modelOrderDetail();
        return $p->selectDetailsByOrderId($orderId);
    }
    public function addOrderDetail($order_id, $dish_id, $quantity, $unit_price, $note) {
        $p = new modelOrderDetail();
        return $p->insertOrderDetail($order_id, $dish_id, $quantity, $unit_price, $note);
    }
}
?>
