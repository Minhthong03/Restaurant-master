<?php
include_once("Model/modelOrderDetail.php");

class controlOrderDetail {
    public function getOrderDetailsByOrderId($orderId) {
        $p = new modelOrderDetail();
        return $p->selectDetailsByOrderId($orderId);
    }
}
?>
