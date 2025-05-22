<?php
include_once("Model/modelIngredients.php");

class controlIngredients {
    // Lấy tất cả nguyên liệu
    public function getAllIngredients() {
        $p = new modelIngredients();
        $result = $p->selectAllIngredients();
        if (mysqli_num_rows($result) > 0) {
            return $result;
        } else {
            return false;
        }
    }

    // Thêm nguyên liệu mới
    public function insertIngredient($name, $description, $supplier_id, $stock_quantity, $unit, $unit_price, $status) {
        $p = new modelIngredients();
        return $p->insertIngredient($name, $description, $supplier_id, $stock_quantity, $unit, $unit_price, $status);
    }

    // Cập nhật nguyên liệu
    public function updateIngredient($id, $name, $description, $supplier_id, $stock_quantity, $unit, $unit_price, $status) {
        $p = new modelIngredients();
        return $p->updateIngredient($id, $name, $description, $supplier_id, $stock_quantity, $unit, $unit_price, $status);
    }

    // Lấy nguyên liệu theo ID
    public function getIngredientById($id) {
        $p = new modelIngredients();
        return $p->selectIngredientById($id);
    }

    // Lấy nguyên liệu theo tên
    public function getIngredientByName($name) {
        $p = new modelIngredients();
        return $p->selectIngredientByName($name);
    }
}
?>
