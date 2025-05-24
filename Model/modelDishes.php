<?php
include_once("ketNoi.php");

class modelDishes {
    // Lấy tất cả món ăn kèm thông tin danh mục và trạng thái
    public function selectAllDishes() {
        $p = new clsKetnoi();
        $con = $p->MoKetNoi();

        $truyvan = "SELECT d.*, c.category_name 
                    FROM dishes d 
                    JOIN categories c ON d.category_id = c.id 
                    WHERE d.status = 'active'";

        $kq = mysqli_query($con, $truyvan);

        $p->DongKetNoi($con);
        return $kq;
    }
    public function selectDishesByName($searchName) {
    $p = new clsKetnoi();
    $con = $p->MoKetNoi();

    $searchName = mysqli_real_escape_string($con, $searchName);

    $truyvan = "SELECT d.*, c.category_name 
                FROM dishes d 
                JOIN categories c ON d.category_id = c.id 
                WHERE d.status = 'active' AND d.dish_name LIKE '%$searchName%'";

    $kq = mysqli_query($con, $truyvan);

    $p->DongKetNoi($con);
    return $kq;
}
public function getDishByName($dishName) {
    $p = new clsKetNoi();
    $con = $p->MoKetNoi();
    // Chuẩn bị câu truy vấn, sử dụng LIKE hoặc so sánh chính xác tùy bạn
    $truyvan = "SELECT * FROM dishes WHERE dish_name = '$dishName'";
    $kq = mysqli_query($con, $truyvan);
    $p->DongKetNoi($con);
    return $kq;
}
public function getDishByNameExceptId($dish_name, $id) {
    $p = new clsKetNoi();
    $con = $p->MoKetNoi();
    $truyvan = "SELECT * FROM dishes WHERE dish_name = '$dish_name' AND id != $id";
    $kq = mysqli_query($con, $truyvan);
    $p->DongKetNoi($con);
    return $kq;
}

    public function selectAllDishesQL() {
        $p = new clsKetnoi();
        $con = $p->MoKetNoi();
        $truyvan = "SELECT d.*, c.category_name 
        FROM Dishes d JOIN Categories c 
        ON d.category_id = c.id";
        $kq = mysqli_query($con, $truyvan);
        $p->DongKetNoi($con);
        return $kq;
    }
    // Lấy món ăn theo ID
    public function selectDishById($dishID) {
        $p = new clsKetnoi();
        $con = $p->MoKetNoi();

        $truyvan = "SELECT d.*, c.category_name FROM Dishes d JOIN Categories c ON d.category_id = c.id WHERE d.id = $dishID";
        $kq = mysqli_query($con, $truyvan);

        $p->DongKetNoi($con);
        return $kq;
    }
    public function selectDishesByCategory($categoryId) {
    $p = new clsKetnoi();
    $con = $p->MoKetNoi();

    // Truy vấn lấy món ăn theo category_id truyền vào và trạng thái active (nếu cần)
    $truyvan = "SELECT d.*, c.category_name 
                FROM dishes d 
                JOIN categories c ON d.category_id = c.id 
                WHERE d.category_id = $categoryId AND d.status = 'active'";

    $kq = mysqli_query($con, $truyvan);
    $p->DongKetNoi($con);
    return $kq;
}
    // Thêm món ăn mới
    public function insertDish($dish_name, $price, $category_id, $image_name, $description, $status) {
        $p = new clsKetnoi();
        $con = $p->MoKetNoi();

        $truyvan = "INSERT INTO Dishes (dish_name, price, category_id, image, description, status) 
                    VALUES ('$dish_name', $price, $category_id, '$image_name', '$description', '$status')";
        $kq = mysqli_query($con, $truyvan);

        $p->DongKetNoi($con);
        return $kq;
    }

    // Cập nhật món ăn
    public function updateDish($id, $dish_name, $price, $category_id, $image_name, $description, $status) {
        $p = new clsKetnoi();
        $con = $p->MoKetNoi();

        $truyvan = "UPDATE Dishes 
                    SET dish_name = '$dish_name', price = $price, category_id = $category_id, image = '$image_name', description = '$description', status = '$status'
                    WHERE id = $id";
        $kq = mysqli_query($con, $truyvan);

        $p->DongKetNoi($con);
        return $kq;
    }
}
?>
