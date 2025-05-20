<?php
include_once("ketNoi.php");

class modelOrderDetail {
    public function selectDetailsByOrderId($orderId) {
        $p = new clsKetNoi();
        $con = $p->MoKetNoi();

        $sql = "SELECT od.*, d.dish_name, d.image
                FROM orderdetails od
                JOIN dishes d ON od.dish_id = d.id
                WHERE od.order_id = $orderId";

        $kq = mysqli_query($con, $sql);
        $p->DongKetNoi($con);
        return $kq;
    }
    // Hàm thêm chi tiết đơn hàng
    public function insertOrderDetail($order_id, $dish_id, $quantity, $unit_price, $note) {
        $p = new clsKetNoi();
        $con = $p->MoKetNoi();

        $sql = "INSERT INTO orderdetails (order_id, dish_id, quantity, unit_price, note) 
                VALUES (?, ?, ?, ?, ?)";

        $stmt = $con->prepare($sql);
        $stmt->bind_param("iiids", $order_id, $dish_id, $quantity, $unit_price, $note);
        $result = $stmt->execute();

        $stmt->close();
        $p->DongKetNoi($con);
        return $result;
    }
}
?>
