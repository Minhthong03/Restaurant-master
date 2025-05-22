<?php
include_once('Model/modelReservations.php');

class controlReservations {
    // Thêm đặt bàn dựa trên email khách hàng (có thể null)
    public function addReservationByEmail($customer_email, $table_id, $reservation_time, $number_of_people, $description) {
        $model = new modelReservations();
        return $model->insertReservationByEmail($customer_email, $table_id, $reservation_time, $number_of_people, $description);
    }

    // Cập nhật đặt bàn theo id dựa trên email khách hàng (có thể null)
    public function updateReservationByEmail($id, $customer_email, $table_id, $reservation_time, $number_of_people, $description) {
        $model = new modelReservations();
        return $model->updateReservationByEmail($id, $customer_email, $table_id, $reservation_time, $number_of_people, $description);
    }

    // Lấy tất cả đặt bàn kèm thông tin chi tiết
    public function getAllReservationsWithDetails() {
        $model = new modelReservations();
        return $model->selectAllReservationsWithDetails();
    }

    // Tìm đặt bàn theo email khách hàng
    public function getReservationsByEmail($email) {
        $model = new modelReservations();
        return $model->selectReservationsByEmail($email);
    }

    // Xóa đặt bàn theo id
    public function deleteReservation($id) {
        $model = new modelReservations();
        return $model->deleteReservation($id);
    }
    public function deleteExpiredReservations() {
    $model = new modelReservations();
    return $model->deleteExpiredReservations();
}

}
?>
