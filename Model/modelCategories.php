<?php
include_once("ketnoi.php");

class modelCategories {
    // Lấy tất cả danh mục món ăn
    public function selectAllCategories() {
        $p = new clsKetnoi();
        $con = $p->MoKetNoi();
        $query = "SELECT * FROM Categories";
        $result = mysqli_query($con, $query);
        $p->DongKetNoi($con);
        return $result;
    }

    // Thêm danh mục mới
    public function insertCategory($category_name, $description) {
        $p = new clsKetnoi();
        $con = $p->MoKetNoi();
        $query = "INSERT INTO Categories (category_name, description) VALUES ('$category_name', '$description')";
        $result = mysqli_query($con, $query);
        $p->DongKetNoi($con);
        return $result;
    }

    // Cập nhật danh mục
    public function updateCategory($id, $category_name, $description) {
        $p = new clsKetnoi();
        $con = $p->MoKetNoi();
        $query = "UPDATE Categories SET category_name = '$category_name', description = '$description' WHERE id = $id";
        $result = mysqli_query($con, $query);
        $p->DongKetNoi($con);
        return $result;
    }
    // Lấy danh mục theo ID
public function selectCategoryById($searchId) {
    $p = new clsKetnoi();
    $con = $p->MoKetNoi();
    $query = "SELECT * FROM Categories WHERE id = $searchId";
    $result = mysqli_query($con, $query);
    $p->DongKetNoi($con);
    return $result;
}
// Kiểm tra tên danh mục đã tồn tại chưa (thêm mới)
public function checkCategoryNameExists($category_name) {
    $p = new clsKetnoi();
    $con = $p->MoKetNoi();
    $query = "SELECT * FROM Categories WHERE category_name = '$category_name'";
    $result = mysqli_query($con, $query);
    $p->DongKetNoi($con);
    return mysqli_num_rows($result) > 0; // Nếu có dữ liệu trả về là đã tồn tại
}

// Kiểm tra tên danh mục đã tồn tại chưa khi cập nhật (ngoại trừ id hiện tại)
public function checkCategoryNameExistsExceptId($category_name, $id) {
    $p = new clsKetnoi();
    $con = $p->MoKetNoi();
    $query = "SELECT * FROM Categories WHERE category_name = '$category_name' AND id != $id";
    $result = mysqli_query($con, $query);
    $p->DongKetNoi($con);
    return mysqli_num_rows($result) > 0;
}

    
}
?>
