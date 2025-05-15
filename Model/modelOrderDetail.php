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
}
?>
