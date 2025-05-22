<?php
include_once("ketnoi.php");

class modelSuppliers {
    // Lấy tất cả nhà cung cấp
    public function selectAllSuppliers() {
        $p = new clsKetnoi();
        $con = $p->MoKetNoi();
        $query = "SELECT * FROM suppliers";
        $result = mysqli_query($con, $query);
        $p->DongKetNoi($con);
        return $result;
    }

    // Thêm nhà cung cấp mới
    public function insertSupplier($name, $contact) {
        $p = new clsKetnoi();
        $con = $p->MoKetNoi();

        $name = mysqli_real_escape_string($con, $name);
        $contact = mysqli_real_escape_string($con, $contact);

        $query = "INSERT INTO suppliers (name, contact) VALUES ('$name', '$contact')";
        $result = mysqli_query($con, $query);

        $p->DongKetNoi($con);
        return $result;
    }

    // Cập nhật nhà cung cấp
    public function updateSupplier($id, $name, $contact) {
        $p = new clsKetnoi();
        $con = $p->MoKetNoi();

        $name = mysqli_real_escape_string($con, $name);
        $contact = mysqli_real_escape_string($con, $contact);

        $query = "UPDATE suppliers SET name = '$name', contact = '$contact' WHERE id = $id";
        $result = mysqli_query($con, $query);

        $p->DongKetNoi($con);
        return $result;
    }

    // Lấy nhà cung cấp theo ID
    public function selectSupplierById($id) {
        $p = new clsKetnoi();
        $con = $p->MoKetNoi();
        $id = intval($id);
        $query = "SELECT * FROM suppliers WHERE id = $id";
        $result = mysqli_query($con, $query);
        $p->DongKetNoi($con);
        return $result;
    }
}
?>
