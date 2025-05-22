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
