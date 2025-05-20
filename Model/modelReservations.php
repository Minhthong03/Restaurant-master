<?php
include_once("ketNoi.php");

class modelReservations {
    // Lấy tất cả bàn đặt kèm tên khách và số bàn
    public function selectAllReservationsWithDetails() {
        $p = new clsKetnoi();
        $con = $p->MoKetNoi();

        $truyvan = "SELECT r.*, u.username, t.table_number 
                    FROM reservations r
                    JOIN users u ON r.customer_id = u.id
                    JOIN tables t ON r.table_id = t.id
                    ORDER BY r.reservation_time DESC";

        $kq = mysqli_query($con, $truyvan);

        $p->DongKetNoi($con);
        return $kq;
    }

    // Thêm bàn đặt mới
    public function insertReservation($customer_id, $table_id, $reservation_time, $number_of_people) {
        $p = new clsKetnoi();
        $con = $p->MoKetNoi();

        $truyvan = "INSERT INTO reservations (customer_id, table_id, reservation_time, number_of_people)
                    VALUES ($customer_id, $table_id, '$reservation_time', $number_of_people)";
        $kq = mysqli_query($con, $truyvan);

        $p->DongKetNoi($con);
        return $kq;
    }

    // Tìm bàn đặt theo email khách hàng (JOIN với users)
    public function selectReservationsByEmail($email) {
        $p = new clsKetnoi();
        $con = $p->MoKetNoi();

        $truyvan = "SELECT r.*, u.username, t.table_number
                    FROM reservations r
                    JOIN users u ON r.customer_id = u.id
                    JOIN tables t ON r.table_id = t.id
                    WHERE u.email = '$email'";

        $kq = mysqli_query($con, $truyvan);
        $p->DongKetNoi($con);
        return $kq;
    }

    // Cập nhật bàn đặt
    public function updateReservation($id, $customer_id, $table_id, $reservation_time, $number_of_people) {
        $p = new clsKetnoi();
        $con = $p->MoKetNoi();

        $truyvan = "UPDATE reservations
                    SET customer_id = $customer_id,
                        table_id = $table_id,
                        reservation_time = '$reservation_time',
                        number_of_people = $number_of_people
                    WHERE id = $id";
        $kq = mysqli_query($con, $truyvan);

        $p->DongKetNoi($con);
        return $kq;
    }

    // Xóa bàn đặt theo id
    public function deleteReservation($id) {
        $p = new clsKetnoi();
        $con = $p->MoKetNoi();

        $truyvan = "DELETE FROM reservations WHERE id = $id";
        $kq = mysqli_query($con, $truyvan);

        $p->DongKetNoi($con);
        return $kq;
    }
}
?>
