<?php
include_once("ketnoi.php");

class modelSuppliers {
    // Lấy tất cả nhà cung cấp
    public function selectAllSuppliers() {
        $p = new clsKetNoi();
        $con = $p->MoKetNoi();

        $query = "SELECT * FROM suppliers";
        $result = mysqli_query($con, $query);

        $p->DongKetNoi($con);
        return $result;
    }

    // Thêm nhà cung cấp mới, trả về ID insert hoặc false
    public function insertSupplier($name, $contact) {
        $p = new clsKetNoi();
        $con = $p->MoKetNoi();

        $name = mysqli_real_escape_string($con, $name);
        $contact = mysqli_real_escape_string($con, $contact);

        $query = "INSERT INTO suppliers (name, contact) VALUES ('$name', '$contact')";
        $result = mysqli_query($con, $query);

        if ($result) {
            $newId = mysqli_insert_id($con);
            $p->DongKetNoi($con);
            return $newId;
        } else {
            $p->DongKetNoi($con);
            return false;
        }
    }

    // Lấy nhà cung cấp theo tên (kiểm tra trùng)
    public function getSupplierByName($supplierName) {
        $p = new clsKetNoi();
        $con = $p->MoKetNoi();

        $supplierName = mysqli_real_escape_string($con, $supplierName);
        $query = "SELECT * FROM suppliers WHERE name = '$supplierName'";

        $result = mysqli_query($con, $query);

        $p->DongKetNoi($con);
        return $result;
    }

    // Cập nhật nhà cung cấp
    public function updateSupplier($id, $name, $contact) {
        $p = new clsKetNoi();
        $con = $p->MoKetNoi();

        $name = mysqli_real_escape_string($con, $name);
        $contact = mysqli_real_escape_string($con, $contact);
        $id = intval($id);

        $query = "UPDATE suppliers SET name = '$name', contact = '$contact' WHERE id = $id";
        $result = mysqli_query($con, $query);

        $p->DongKetNoi($con);
        return $result;
    }

    // Lấy nhà cung cấp theo ID
    public function selectSupplierById($id) {
        $p = new clsKetNoi();
        $con = $p->MoKetNoi();

        $id = intval($id);
        $query = "SELECT * FROM suppliers WHERE id = $id";
        $result = mysqli_query($con, $query);

        $p->DongKetNoi($con);
        return $result;
    }
}
?>
