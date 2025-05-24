<style>
    .table td, .table th { color: #000; vertical-align: middle; }
    .dish-img {
        width: 70px;
        height: 50px;
        object-fit: cover; /* Đảm bảo ảnh không bị kéo dài */
        border-radius: 5px;
    }
</style>
<?php
include_once("Controller/controlDishes.php");
include_once("Controller/controlCategories.php");

$p = new controlDishes();
$c = new controlCategories();  

$editId = isset($_GET['editDish']) ? intval($_GET['editDish']) : 0;
$addMode = isset($_GET['addDish']) ? true : false;

echo '<div class="container mt-5">';

// Xử lý khi form "Sửa món ăn" được gửi
if ($editId > 0) {
    $dish = $p->getDishById($editId);
    echo '<h3 class="text-center mb-4">Cập nhật món ăn #' . $editId . '</h3>';
    if ($dish && mysqli_num_rows($dish) > 0) {
        $r = mysqli_fetch_assoc($dish);
        echo '<form method="POST" action="admin.php?action=ADishes&editDish=' . $r['id'] . '" enctype="multipart/form-data">';
        echo '<input type="hidden" name="id" value="' . $r['id'] . '">' ;

        // Tên món ăn
        echo '<div class="mb-3"><label>Tên món:</label><input type="text" name="dish_name" value="' . htmlspecialchars($r['dish_name']) . '" class="form-control" required></div>';

        // Giá món ăn
        echo '<div class="mb-3"><label>Giá (VNĐ):</label><input type="number" name="price" value="' . htmlspecialchars($r['price']) . '" class="form-control" required></div>';

        // Loại món - Hiển thị danh sách các danh mục món ăn
        echo '<div class="mb-3"><label for="category_id">Loại món:</label><select name="category_id" class="form-control">';
        $categories = $c->getAllCategories(); 
        while ($category = mysqli_fetch_assoc($categories)) {
            $selected = ($category['id'] == $r['category_id']) ? 'selected' : '';  
            echo '<option value="' . $category['id'] . '" ' . $selected . '>' . htmlspecialchars($category['category_name']) . '</option>';
        }
        echo '</select></div>';

        // Trạng thái cho món ăn
        echo '<div class="mb-3"><label>Trạng thái:</label>
                <select name="status" class="form-control">
                    <option value="active" ' . ($r['status'] == 'active' ? 'selected' : '') . '>Hoạt động</option>
                    <option value="inactive" ' . ($r['status'] == 'inactive' ? 'selected' : '') . '>Ngưng hoạt động</option>
                </select></div>';

        // Ảnh món ăn
        echo '<div class="mb-3"><label>Ảnh món:</label><input type="file" name="image" class="form-control"></div>';

        // Mô tả món ăn
        echo '<div class="mb-3"><label>Mô tả:</label><textarea name="description" class="form-control">' . htmlspecialchars($r['description']) . '</textarea></div>';

        // Nút Lưu thay đổi
        echo '<button type="submit" class="btn btn-primary" name="btnUpdate">Lưu thay đổi</button>';
        echo '<a href="admin.php?action=ADishes" class="btn btn-danger">Hủy</a>';
        echo '</form>';

        // Xử lý khi bấm "Lưu thay đổi"
        if (isset($_POST["btnUpdate"])) {
    $id = $_POST["id"];
    $dish_name = $_POST["dish_name"];
    $price = $_POST["price"];
    $category_id = $_POST["category_id"];
    $description = $_POST["description"];
    $status = $_POST["status"];
    $image = isset($_FILES["image"]) ? $_FILES["image"] : null;

    // Kiểm tra trùng tên món ngoại trừ chính món hiện tại
    if ($p->checkDishNameExistsExceptId($dish_name, $id)) {
        echo "<script>alert('Tên món đã tồn tại. Vui lòng chọn tên khác!');</script>";
    } else {
        if ($image && $image['error'] == 0) {
            $image_name = $image['name'];
            move_uploaded_file($image['tmp_name'], 'images/Dishes/' . $image_name);
        } else {
            $image_name = $r['image']; // Không thay đổi ảnh nếu không có ảnh mới
        }

        // Cập nhật món ăn
        $updateResult = $p->updateDish($id, $dish_name, $price, $category_id, $image_name, $description, $status);
        if ($updateResult) {
            echo "<script>alert('Cập nhật món ăn thành công!'); window.location.href = 'admin.php?action=ADishes';</script>";
        } else {
            echo "<script>alert('Cập nhật món ăn thất bại!');</script>";
        }
    }
}

    }
}

// Nếu là form "Thêm món ăn"
elseif ($addMode) {
    echo '<h3 class="text-center mb-4">Thêm món ăn mới</h3>';
    echo '<form method="POST" action="admin.php?action=ADishes&addDish=true" enctype="multipart/form-data">';

    // Tên món ăn
    echo '<div class="mb-3"><label>Tên món:</label><input type="text" name="dish_name" class="form-control" required></div>';

    // Giá món ăn
    echo '<div class="mb-3"><label>Giá (VNĐ):</label><input type="number" name="price" class="form-control" required></div>';

    // Loại món - Hiển thị danh sách các danh mục món ăn
    echo '<div class="mb-3"><label for="category_id">Loại món:</label><select name="category_id" class="form-control">';
    $categories = $c->getAllCategories(); 
    while ($category = mysqli_fetch_assoc($categories)) {
        echo '<option value="' . $category['id'] . '">' . htmlspecialchars($category['category_name']) . '</option>';
    }
    echo '</select></div>';

    // Trạng thái món ăn
    echo '<div class="mb-3"><label for="status">Trạng thái:</label><select name="status" class="form-control">';
    echo '<option value="active">Active</option>';
    echo '<option value="inactive">Inactive</option>';
    echo '</select></div>';

    // Ảnh món ăn
    echo '<div class="mb-3"><label>Ảnh món:</label><input type="file" name="image" class="form-control" required></div>';

    // Mô tả món ăn
    echo '<div class="mb-3"><label>Mô tả:</label><textarea name="description" class="form-control"></textarea></div>';

    // Nút Thêm mới
    echo '<button type="submit" class="btn btn-success" name="btnInsert">Thêm mới</button>';
    echo '<a href="admin.php?action=ADishes" class="btn btn-danger">Hủy</a>';

    echo '</form>';

    // Xử lý khi bấm "Thêm mới"
    if (isset($_POST["btnInsert"])) {
    $dish_name = $_POST["dish_name"];
    $price = $_POST["price"];
    $category_id = $_POST["category_id"];
    $description = $_POST["description"];
    $status = $_POST["status"];
    $image = $_FILES["image"];
    $image_name = $image['name'];

    // Kiểm tra trùng tên món
    if ($p->checkDishNameExists($dish_name)) {
        echo "<script>alert('Tên món đã tồn tại. Vui lòng chọn tên khác!');</script>";
    } else {
        move_uploaded_file($image['tmp_name'], 'images/Dishes/' . $image_name);

        // Thêm món ăn
        $insertResult = $p->insertDish($dish_name, $price, $category_id, $image_name, $description, $status);
        if ($insertResult) {
            echo "<script>alert('Thêm món ăn thành công!'); window.location.href = 'admin.php?action=ADishes';</script>";
        } else {
            echo "<script>alert('Thêm món ăn thất bại!');</script>";
        }
    }
}
} else {
    // Tìm kiếm món ăn theo ID
    $searchId = isset($_GET['searchId']) ? trim($_GET['searchId']) : '';
    $kq = ($searchId != '') ? $p->getDishById($searchId) : $p->getAllDishesQL();

    echo '<h2 class="mb-4 text-center text-dark">Quản lý món ăn</h2>';
    echo '<a href="admin.php?action=ADishes&addDish=true" class="btn btn-success" style="text-decoration: none;">Thêm món</a>';

    echo '<div class="container">' ;

    // Form tìm kiếm
    echo '<form method="GET" class="row mb-4 justify-content-center">';
    echo '<input type="hidden" name="action" value="ADishes">';
    echo '<div class="col-md-3">';
    echo '<input type="text" name="searchId" class="form-control" placeholder="Nhập ID món ăn" value="' . htmlspecialchars($searchId) . '">';
    echo '</div>';
    echo '<div class="col-auto">';
    echo '<button type="submit" class="btn btn-primary">Tìm kiếm</button>';
    echo '</div>';
    echo '</form>';

    // Hiển thị danh sách món ăn
    if (!$kq) {
        echo '<div class="alert alert-warning text-center">❌ Không có dữ liệu món ăn.</div>';
    } else {
        echo '<table class="table table-bordered table-hover text-center bg-light">';
        echo '<thead><tr class="bg-light text-dark">
                <th>ID</th>
                <th>Ảnh</th>
                <th>Tên món</th>
                <th>Giá (VNĐ)</th>
                <th>Loại món</th>
                <th>Mô tả</th>
                <th>Trạng thái</th>
                <th>Thao tác</th>
              </tr></thead><tbody>';

        while ($r = mysqli_fetch_assoc($kq)) {
            echo '<tr>';
            echo '<td>' . $r["id"] . '</td>';
            echo '<td><img src="images/Dishes/' . $r["image"] . '" class="dish-img"></td>';
            echo '<td>' . htmlspecialchars($r["dish_name"]) . '</td>';
            echo '<td>' . number_format($r["price"], 0, ',', '.') . '</td>';
            echo '<td>' . htmlspecialchars($r["category_name"]) . '</td>';
            echo '<td>' . htmlspecialchars($r["description"]) . '</td>';
            echo '<td>' . ($r["status"] == 'active' ? 'Active' : 'Inactive') . '</td>';
            echo '<td><a href="admin.php?action=ADishes&editDish=' . $r['id'] . '" class="btn btn-sm btn-primary">Sửa</a></td>';
            echo '</tr>';
        }
        echo '</tbody></table>';
    }
}
echo '</div>';
?>
