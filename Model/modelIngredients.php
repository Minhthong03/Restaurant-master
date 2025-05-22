<?php
include_once("ketnoi.php");

class modelIngredients {
    // Lấy tất cả nguyên liệu
    public function selectAllIngredients() {
    $p = new clsKetnoi();
    $con = $p->MoKetNoi();

    $query = "
        SELECT i.*, s.name AS supplier_name, s.contact AS supplier_contact
        FROM ingredients i
        LEFT JOIN suppliers s ON i.supplier_id = s.id
    ";

    $result = mysqli_query($con, $query);
    $p->DongKetNoi($con);
    return $result;
}


    // Thêm nguyên liệu mới
    public function insertIngredient($name, $description, $supplier_id, $stock_quantity, $unit, $unit_price, $status) {
        $p = new clsKetnoi();
        $con = $p->MoKetNoi();
        $query = "INSERT INTO ingredients (name, description, supplier_id, stock_quantity, unit, unit_price, status)
                  VALUES ('$name', '$description', $supplier_id, $stock_quantity, '$unit', $unit_price, '$status')";
        $result = mysqli_query($con, $query);
        $p->DongKetNoi($con);
        return $result;
    }

    // Cập nhật nguyên liệu
    public function updateIngredient($id, $name, $description, $supplier_id, $stock_quantity, $unit, $unit_price, $status) {
        $p = new clsKetnoi();
        $con = $p->MoKetNoi();
        $query = "UPDATE ingredients 
                  SET name = '$name', description = '$description', supplier_id = $supplier_id, 
                      stock_quantity = $stock_quantity, unit = '$unit', unit_price = $unit_price, status = '$status'
                  WHERE id = $id";
        $result = mysqli_query($con, $query);
        $p->DongKetNoi($con);
        return $result;
    }

    // Lấy nguyên liệu theo ID
    public function selectIngredientById($id) {
    $p = new clsKetnoi();
    $con = $p->MoKetNoi();

    $query = "
        SELECT i.*, s.name AS supplier_name, s.contact AS supplier_contact
        FROM ingredients i
        LEFT JOIN suppliers s ON i.supplier_id = s.id
        WHERE i.id = $id
    ";

    $result = mysqli_query($con, $query);
    $p->DongKetNoi($con);
    return $result;
}

    // Lấy nguyên liệu theo tên (tìm kiếm gần đúng)
public function selectIngredientByName($name) {
    $p = new clsKetnoi();
    $con = $p->MoKetNoi();

    $name = mysqli_real_escape_string($con, $name);

    $query = "
        SELECT i.*, s.name AS supplier_name, s.contact AS supplier_contact
        FROM ingredients i
        LEFT JOIN suppliers s ON i.supplier_id = s.id
        WHERE i.name LIKE '%$name%'
    ";

    $result = mysqli_query($con, $query);
    $p->DongKetNoi($con);
    return $result;
}
public function updateStockQuantity($ingredient_id, $new_quantity) {
    $p = new clsKetnoi();
    $con = $p->MoKetNoi();

    $ingredient_id = intval($ingredient_id);
    $new_quantity = intval($new_quantity);

    $sql = "UPDATE ingredients SET stock_quantity = $new_quantity WHERE id = $ingredient_id";

    $result = mysqli_query($con, $sql);
    $p->DongKetNoi($con);

    return $result;
}

    
}
?>
