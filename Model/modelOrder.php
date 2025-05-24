<?php
include_once("ketNoi.php");

class modelOrder {
    public function selectAllOrders() {
    $p = new clsKetNoi();
    $con = $p->MoKetNoi();

    $sql = "SELECT o.*, c.username, c.email, c.phone, c.address, t.table_number, t.status AS table_status
        FROM orders o
        LEFT JOIN users c ON o.customer_id = c.id
        LEFT JOIN tables t ON o.table_id = t.id
        ORDER BY o.order_date DESC";

    $kq = mysqli_query($con, $sql);
    $p->DongKetNoi($con);
    return $kq;
}
// Hàm lấy đơn hàng theo ID
    public function selectOrderById($orderId) {
        $p = new clsKetNoi();
        $con = $p->MoKetNoi();

        $sql = "SELECT * FROM orders WHERE id = $orderId LIMIT 1";
        $result = mysqli_query($con, $sql);

        $p->DongKetNoi($con);
        return $result;
    }
    public function insertOrder($customer_id, $table_id, $total_amount, $status, $description = null) {
    $p = new clsKetNoi();
    $con = $p->MoKetNoi();

    if (is_null($customer_id)) {
        $sql = "INSERT INTO orders (customer_id, table_id, total_amount, status, description) VALUES (NULL, ?, ?, ?, ?)";
        $stmt = $con->prepare($sql);
        $stmt->bind_param("idss", $table_id, $total_amount, $status, $description);
    } else {
        $sql = "INSERT INTO orders (customer_id, table_id, total_amount, status, description) VALUES (?, ?, ?, ?, ?)";
        $stmt = $con->prepare($sql);
        $stmt->bind_param("iidss", $customer_id, $table_id, $total_amount, $status, $description);
    }

    $result = $stmt->execute();

    if ($result) {
        $order_id = $stmt->insert_id;
    } else {
        $order_id = false;
    }

    $stmt->close();
    $p->DongKetNoi($con);
    return $order_id;
}
public function selectOrdersByCustomerAndStatus($customer_id, $status = 'Tất cả') {
    $p = new clsKetNoi();
    $con = $p->MoKetNoi();

    if ($status === 'Tất cả') {
        // Lấy tất cả đơn hàng của khách, không lọc trạng thái
        $sql = "SELECT * FROM orders WHERE customer_id = ? ORDER BY order_date DESC";
        $stmt = $con->prepare($sql);
        $stmt->bind_param("i", $customer_id);
    } else {
        // Lọc theo trạng thái cụ thể
        $sql = "SELECT * FROM orders WHERE customer_id = ? AND status = ? ORDER BY order_date DESC";
        $stmt = $con->prepare($sql);
        $stmt->bind_param("is", $customer_id, $status);
    }

    $stmt->execute();
    $result = $stmt->get_result();

    $p->DongKetNoi($con);
    return $result;
}



}
?>
