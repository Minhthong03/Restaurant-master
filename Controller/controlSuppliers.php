<?php
include_once("Model/modelSuppliers.php");

class controlSuppliers {
    // Lấy tất cả nhà cung cấp
    public function getAllSuppliers() {
        $p = new modelSuppliers();
        $result = $p->selectAllSuppliers();
        if ($result && mysqli_num_rows($result) > 0) {
            return $result;
        } else {
            return false;
        }
    }

    // Thêm nhà cung cấp mới, trả về ID hoặc false
    public function insertSupplier($name, $contact) {
        $model = new modelSuppliers();
        return $model->insertSupplier($name, $contact);
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

    // Kiểm tra tên nhà cung cấp đã tồn tại chưa, trả về true/false
    public function checkSupplierNameExists($supplierName) {
        $p = new modelSuppliers();
        $result = $p->getSupplierByName($supplierName);
        return $result && mysqli_num_rows($result) > 0;
    }
}
?>
