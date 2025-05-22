<?php
include_once("ketNoi.php");

class modelReservations {
    // Lấy tất cả bàn đặt kèm tên khách (nếu có) và số bàn, mô tả
    public function selectAllReservationsWithDetails() {
        $p = new clsKetnoi();
        $con = $p->MoKetNoi();

        $sql = "SELECT r.*, u.username, u.email, t.table_number
        FROM reservations r
        LEFT JOIN users u ON r.customer_id = u.id
        LEFT JOIN tables t ON r.table_id = t.id
        ORDER BY r.reservation_time DESC";

        $result = mysqli_query($con, $sql);
        if (!$result) {
            die("Lỗi truy vấn SQL ở selectAllReservationsWithDetails: " . mysqli_error($con));
        }

        $p->DongKetNoi($con);
        return $result;
    }
    public function deleteExpiredReservations() {
    $p = new clsKetnoi();
    $con = $p->MoKetNoi();

    // Xóa các đặt bàn có thời gian đặt trước thời điểm hiện tại (giờ hiện tại)
    $sql = "DELETE FROM reservations WHERE reservation_time < NOW()";

    $result = mysqli_query($con, $sql);
    $p->DongKetNoi($con);
    return $result;
}

    // Lấy customer_id theo email, trả về id hoặc NULL nếu không có
    private function getCustomerIdByEmail($email, $con) {
        if (empty($email)) return null;
        $email_safe = mysqli_real_escape_string($con, $email);
        $sql = "SELECT id FROM users WHERE email = '$email_safe' LIMIT 1";
        $result = mysqli_query($con, $sql);
        if ($result && mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            return intval($row['id']);
        }
        return null;
    }

    // Thêm bàn đặt mới (có thể không có customer_id)
    public function insertReservationByEmail($customer_email, $table_id, $reservation_time, $number_of_people, $description) {
        $p = new clsKetnoi();
        $con = $p->MoKetNoi();

        $customer_id = $this->getCustomerIdByEmail($customer_email, $con);

        $table_id = intval($table_id);
        $reservation_time = mysqli_real_escape_string($con, $reservation_time);
        $number_of_people = intval($number_of_people);
        $description = mysqli_real_escape_string($con, $description);

        $customer_id_sql = is_null($customer_id) ? "NULL" : $customer_id;

        $sql = "INSERT INTO reservations (customer_id, table_id, reservation_time, number_of_people, description)
                VALUES ($customer_id_sql, $table_id, '$reservation_time', $number_of_people, '$description')";

        $result = mysqli_query($con, $sql);
        $p->DongKetNoi($con);
        return $result;
    }

    // Cập nhật bàn đặt (có thể không có customer_id)
    public function updateReservationByEmail($id, $customer_email, $table_id, $reservation_time, $number_of_people, $description) {
        $p = new clsKetnoi();
        $con = $p->MoKetNoi();

        $customer_id = $this->getCustomerIdByEmail($customer_email, $con);

        $id = intval($id);
        $table_id = intval($table_id);
        $reservation_time = mysqli_real_escape_string($con, $reservation_time);
        $number_of_people = intval($number_of_people);
        $description = mysqli_real_escape_string($con, $description);

        $customer_id_sql = is_null($customer_id) ? "NULL" : $customer_id;

        $sql = "UPDATE reservations SET
                    customer_id = $customer_id_sql,
                    table_id = $table_id,
                    reservation_time = '$reservation_time',
                    number_of_people = $number_of_people,
                    description = '$description'
                WHERE id = $id";

        $result = mysqli_query($con, $sql);
        $p->DongKetNoi($con);
        return $result;
    }

    // Tìm bàn đặt theo email khách hàng
    public function selectReservationsByEmail($email) {
        $p = new clsKetnoi();
        $con = $p->MoKetNoi();

        $email_safe = mysqli_real_escape_string($con, $email);

        $sql = "SELECT r.*, u.username, t.table_number
                FROM reservations r
                LEFT JOIN users u ON r.customer_id = u.id
                LEFT JOIN tables t ON r.table_id = t.id
                WHERE u.email = '$email_safe'
                ORDER BY r.reservation_time DESC";

        $result = mysqli_query($con, $sql);
        $p->DongKetNoi($con);
        return $result;
    }

    // Xóa bàn đặt theo id
    public function deleteReservation($id) {
        $p = new clsKetnoi();
        $con = $p->MoKetNoi();

        $id = intval($id);
        $sql = "DELETE FROM reservations WHERE id = $id";

        $result = mysqli_query($con, $sql);
        $p->DongKetNoi($con);
        return $result;
    }
}
?>
