<?php
include_once('Model/modelReservations.php');

class controlReservations {
    // Lấy tất cả bàn đặt cùng tên khách và số bàn
    public function getAllReservationsWithDetails() {
        $p = new modelReservations();
        $kq = $p->selectAllReservationsWithDetails();

        if (mysqli_num_rows($kq) > 0) {
            return $kq;
        } else {
            return false;
        }
    }

    // Thêm bàn đặt mới
    public function addReservation($customer_id, $table_id, $reservation_time, $number_of_people) {
        $p = new modelReservations();
        return $p->insertReservation($customer_id, $table_id, $reservation_time, $number_of_people);
    }

    // Tìm bàn đặt theo email khách hàng
    public function getReservationsByEmail($email) {
        $p = new modelReservations();
        $kq = $p->selectReservationsByEmail($email);

        if (mysqli_num_rows($kq) > 0) {
            return $kq;
        } else {
            return false;
        }
    }

    // Cập nhật bàn đặt
    public function updateReservation($id, $customer_id, $table_id, $reservation_time, $number_of_people) {
        $p = new modelReservations();
        return $p->updateReservation($id, $customer_id, $table_id, $reservation_time, $number_of_people);
    }

    // Xóa bàn đặt
    public function deleteReservation($id) {
        $p = new modelReservations();
        return $p->deleteReservation($id);
    }
}
?>
