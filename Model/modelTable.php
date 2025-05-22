<?php
include_once('KetNoi.php'); // file kết nối database, bạn điều chỉnh nếu khác

class modelTable {
    // Lấy tất cả bản ghi trong bảng tables
    public function selectAllTables() {
        $p = new clsKetNoi();
        $con = $p->MoKetNoi();
        $sql = "SELECT * FROM tables ORDER BY table_number ASC";
        $result = mysqli_query($con, $sql);
        $p->DongKetNoi($con);
        return $result;
    }
    public function selectTableById($id) {
    $p = new clsKetNoi();
    $con = $p->MoKetNoi();

    $id = intval($id);
    $sql = "SELECT table_number FROM tables WHERE id = $id";
    $result = mysqli_query($con, $sql);

    $p->DongKetNoi($con);
    return $result;
}
    // Thêm bàn mới
    public function insertTable($table_number, $status) {
        $p = new clsKetNoi();
        $con = $p->MoKetNoi();

        // Không lấy trực tiếp $_POST ở đây, nên lấy qua tham số $table_number truyền vào
        // Và escape chuỗi để tránh lỗi SQL injection
        $table_number = mysqli_real_escape_string($con, $table_number);
        $status = mysqli_real_escape_string($con, $status);

        $sql = "INSERT INTO tables (table_number, status) VALUES ('$table_number', '$status')";

        $result = mysqli_query($con, $sql);

        $p->DongKetNoi($con);
        return $result;
    }

    // Cập nhật trạng thái bàn
    public function updateTableStatus($id, $status) {
        $p = new clsKetNoi();
        $con = $p->MoKetNoi();

        $id = intval($id);
        $status = mysqli_real_escape_string($con, $status);

        $sql = "UPDATE tables SET status = '$status' WHERE id = $id";
        $result = mysqli_query($con, $sql);

        $p->DongKetNoi($con);
        return $result;
    }
}
?>
