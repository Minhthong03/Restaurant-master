<?php
include_once("Controller/controlCategories.php");

$p = new controlCategories();

$editId = isset($_GET['editCategory']) ? intval($_GET['editCategory']) : 0;
$addMode = isset($_GET['addCategory']) ? true : false;

echo '<div class="container mt-5">';

// Xử lý khi form "Sửa danh mục" được gửi
if ($editId > 0) {
    $category = $p->getCategoryById($editId); // Lấy danh mục theo ID
    echo '<h4 class="text-center mb-4">Cập nhật danh mục #' . $editId . '</h4>';
    if ($category && mysqli_num_rows($category) > 0) {
        $r = mysqli_fetch_assoc($category);
        echo '<form method="POST" action="admin.php?action=ACategories&editCategory=' . $r['id'] . '">';

        // Tên danh mục
        echo '<div class="mb-3"><label>Tên danh mục:</label><input type="text" name="category_name" value="' . htmlspecialchars($r['category_name']) . '" class="form-control" required></div>';

        // Mô tả danh mục
        echo '<div class="mb-3"><label>Mô tả:</label><textarea name="description" class="form-control">' . htmlspecialchars($r['description']) . '</textarea></div>';

        // Nút Lưu thay đổi
        echo '<button type="submit" class="btn btn-primary" name="btnUpdate">Lưu thay đổi</button>';
        echo '<a href="admin.php?action=ACategories" class="btn btn-danger">Hủy</a>';
        echo '</form>';

        // Xử lý khi bấm "Lưu thay đổi"
        if (isset($_POST["btnUpdate"])) {
            $category_name = $_POST["category_name"];
            $description = $_POST["description"];

            // Cập nhật danh mục
            $updateResult = $p->updateCategory($editId, $category_name, $description);
            if ($updateResult) {
                echo "<script>alert('Cập nhật danh mục thành công!'); window.location.href = 'admin.php?action=ACategories';</script>";
            } else {
                echo "<script>alert('Cập nhật danh mục thất bại!');</script>";
            }
        }
    }
}


// Nếu là form "Thêm danh mục"
elseif ($addMode) {
    echo '<h4 class="text-center mb-4">Thêm danh mục mới</h4>';
    echo '<form method="POST" action="admin.php?action=ACategories&addCategory=true">';

    // Tên danh mục
    echo '<div class="mb-3"><label>Tên danh mục:</label><input type="text" name="category_name" class="form-control" required></div>';

    // Mô tả danh mục
    echo '<div class="mb-3"><label>Mô tả:</label><textarea name="description" class="form-control"></textarea></div>';

    // Nút Thêm mới
    echo '<button type="submit" class="btn btn-success" name="btnInsert">Thêm mới</button>';
    echo '<a href="admin.php?action=ACategories" class="btn btn-danger">Hủy</a>';

    echo '</form>';

    // Xử lý khi bấm "Thêm mới"
    if (isset($_POST["btnInsert"])) {
        $category_name = $_POST["category_name"];
        $description = $_POST["description"];

        // Thêm danh mục
        $insertResult = $p->insertCategory($category_name, $description);
        if ($insertResult) {
            echo "<script>alert('Thêm danh mục thành công!'); window.location.href = 'admin.php?action=ACategories';</script>";
        } else {
            echo "<script>alert('Thêm danh mục thất bại!');</script>";
        }
    }
} else {
    // Lấy tất cả danh mục món ăn hoặc tìm kiếm theo ID
    $searchId = isset($_GET['searchId']) ? trim($_GET['searchId']) : '';
    $kq = ($searchId != '') ? $p->getCategoryById($searchId) : $p->getAllCategories();

    echo '<h2 class="mb-4 text-center text-dark">Quản lý danh mục món ăn</h2>';
    echo '<a href="admin.php?action=ACategories&addCategory=true" class="btn btn-success" style="text-decoration: none;">Thêm danh mục</a>';

    echo '<div class="container">' ;

    // Form tìm kiếm
    echo '<form method="GET" class="row mb-4 justify-content-center">';
    echo '<input type="hidden" name="action" value="ACategories">';
    echo '<div class="col-md-3">';
    echo '<input type="text" name="searchId" class="form-control" placeholder="Nhập ID danh mục" value="' . htmlspecialchars($searchId) . '">';
    echo '</div>';
    echo '<div class="col-auto">';
    echo '<button type="submit" class="btn btn-primary">Tìm kiếm</button>';
    echo '</div>';
    echo '</form>';

    // Hiển thị danh sách danh mục món ăn
    if (!$kq) {
        echo '<div class="alert alert-warning text-center">❌ Không có dữ liệu danh mục món ăn.</div>';
    } else {
        echo '<table class="table table-bordered table-hover text-center bg-light">';
        echo '<thead><tr class="bg-light text-dark">
                <th>ID</th>
                <th>Tên danh mục</th>
                <th>Mô tả</th>
                <th>Thao tác</th>
              </tr></thead><tbody>';

        while ($r = mysqli_fetch_assoc($kq)) {
            echo '<tr>';
            echo '<td>' . $r["id"] . '</td>';
            echo '<td>' . htmlspecialchars($r["category_name"]) . '</td>';
            echo '<td>' . htmlspecialchars($r["description"]) . '</td>';
            echo '<td><a href="admin.php?action=ACategories&editCategory=' . $r['id'] . '" class="btn btn-sm btn-primary">Sửa</a></td>';
            echo '</tr>';
        }
        echo '</tbody></table>';
    }
}
echo '</div>';
?>
