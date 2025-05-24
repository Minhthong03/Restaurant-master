<?php
include_once(__DIR__ . '/../Model/modelDishes.php');

class controlDishes {
    public function getAllDishes() {
        $p = new modelDishes();
        $kq = $p->selectAllDishes();

        if (mysqli_num_rows($kq) > 0) {
            return $kq;
        } else {
            return false;
        }
    }
    public function getAllDishesQL() {
        $p = new modelDishes();
        $kq = $p->selectAllDishesQL();

        if (mysqli_num_rows($kq) > 0) {
            return $kq;
        } else {
            return false;
        }
    }
    public function getDishesByName($searchName) {
    $p = new modelDishes();
    $kq = $p->selectDishesByName($searchName);

    if (mysqli_num_rows($kq) > 0) {
        return $kq;
    } else {
        return false;
    }
}
public function checkDishNameExists($dishName) {
    $p = new modelDishes(); // Giả sử bạn có class mDishes để thao tác với bảng dishes
    $result = $p->getDishByName($dishName); // Gọi hàm getDishByName trong model để lấy kết quả
    return mysqli_num_rows($result) > 0; // Nếu có dữ liệu trả về thì món đã tồn tại
}
public function checkDishNameExistsExceptId($dishName, $id) {
    $p = new modelDishes(); // Class model thao tác với bảng dishes
    $result = $p->getDishByNameExceptId($dishName, $id); // Gọi hàm model kiểm tra tên món trùng ngoại trừ món hiện tại
    return mysqli_num_rows($result) > 0; // Nếu có dữ liệu trả về thì tên món đã tồn tại
}

    public function getDishById($dishID) {
        $p = new modelDishes();
        $kq = $p->selectDishById($dishID);

        if (mysqli_num_rows($kq) > 0) {
            return $kq;
        } else {
            return false;
        }
    }
    public function getDishesByCategory($categoryId) {
    $p = new modelDishes();
    $kq = $p->selectDishesByCategory($categoryId);

    if (mysqli_num_rows($kq) > 0) {
        return $kq;
    } else {
        return false;
    }
}

    public function insertDish($dish_name, $price, $category_id, $image_name, $description, $status) {
        $p = new modelDishes();
        return $p->insertDish($dish_name, $price, $category_id, $image_name, $description, $status);
    }

    public function updateDish($id, $dish_name, $price, $category_id, $image_name, $description, $status) {
        $p = new modelDishes();
        return $p->updateDish($id, $dish_name, $price, $category_id, $image_name, $description, $status);
    }
}
?>
