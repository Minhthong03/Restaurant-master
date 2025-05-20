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

}
?>
