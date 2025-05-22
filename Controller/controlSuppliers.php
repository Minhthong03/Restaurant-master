<?php
include_once("Model/modelSuppliers.php");

class controlSuppliers {
    // Lấy tất cả nhà cung cấp
    public function getAllSuppliers() {
        $p = new modelSuppliers();
        $result = $p->selectAllSuppliers();
        if (mysqli_num_rows($result) > 0) {
            return $result;
        } else {
            return false;
        }
    }

    // Thêm nhà cung cấp mới
    public function insertSupplier($name, $contact) {
        $p = new modelSuppliers();
        return $p->insertSupplier($name, $contact);
    }

    // Cập nhật nhà cung cấp
    public function updateSupplier($id, $name, $contact) {
        $p = new modelSuppliers();
        return $p->updateSupplier($id, $name, $contact);
    }

    // Lấy nhà cung cấp theo ID
    public function getSupplierById($id) {
        $p = new modelSuppliers();
        return $p->selectSupplierById($id);
    }
}
?>
