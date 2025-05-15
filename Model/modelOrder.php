<?php
include_once("ketNoi.php");

class modelOrder {
    public function selectAllOrders() {
        $p = new clsKetNoi();
        $con = $p->MoKetNoi();

        $sql = "SELECT o.*, c.full_name, c.email, c.phone, c.address
                FROM orders o
                JOIN customers c ON o.customer_id = c.id
                ORDER BY o.order_date DESC";

        $kq = mysqli_query($con, $sql);
        $p->DongKetNoi($con);
        return $kq;
    }
}
?>
