<?php
include_once("Model/modelOrder.php");

class controlOrder {
    public function getAllOrders() {
        $p = new modelOrder();
        $kq = $p->selectAllOrders();
        return (mysqli_num_rows($kq) > 0) ? $kq : false;
    }
     public function getOrderById($orderId) {
        $p = new modelOrder();
        $kq = $p->selectOrderById($orderId);
        if ($kq && mysqli_num_rows($kq) > 0) {
            return $kq;
        } else {
            return false;
        }
    }
    public function insertOrder($customer_id, $table_id, $total_amount, $status, $description = null) {
    $p = new modelOrder();
    return $p->insertOrder($customer_id, $table_id, $total_amount, $status, $description);
}
public function getOrdersByCustomerAndStatus($customer_id, $status = 'Tất cả') {
    $p = new modelOrder();
    return $p->selectOrdersByCustomerAndStatus($customer_id, $status);
}
public function updateOrderStatus($orderId, $status) {
    $p = new modelOrder();  // mOrder là model xử lý bảng orders
    return $p->updateStatus($orderId, $status);
}




}
?>
