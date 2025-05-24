<?php
include_once("Model/modelCategories.php");

class controlCategories {
    // Lấy tất cả danh mục món ăn
    public function getAllCategories() {
        $p = new modelCategories();
        $result = $p->selectAllCategories();
        if (mysqli_num_rows($result) > 0) {
            return $result;
        } else {
            return false;
        }
    }

    // Thêm danh mục mới
    public function insertCategory($category_name, $description) {
        $p = new modelCategories();
        return $p->insertCategory($category_name, $description);
    }

    // Cập nhật danh mục
    public function updateCategory($id, $category_name, $description) {
        $p = new modelCategories();
        return $p->updateCategory($id, $category_name, $description);
    }
    // Lấy danh mục theo ID
public function getCategoryById($searchId) {
    $p = new modelCategories();
    return $p->selectCategoryById($searchId); // Gọi phương thức trong model để truy vấn theo ID
}
// Kiểm tra tên danh mục đã tồn tại (thêm mới)
public function checkCategoryNameExists($category_name) {
    $p = new modelCategories();
    return $p->checkCategoryNameExists($category_name);
}

// Kiểm tra tên danh mục đã tồn tại khi cập nhật (ngoại trừ id hiện tại)
public function checkCategoryNameExistsExceptId($category_name, $id) {
    $p = new modelCategories();
    return $p->checkCategoryNameExistsExceptId($category_name, $id);
}

    
}
?>
