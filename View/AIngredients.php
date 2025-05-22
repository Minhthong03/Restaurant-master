<?php
include_once("Controller/controlIngredients.php");
include_once("Controller/controlSuppliers.php");

$p = new controlIngredients();
$supplierCtrl = new controlSuppliers();

// Lấy danh sách nhà cung cấp để dùng cho dropdown
$suppliers = $supplierCtrl->getAllSuppliers();

$editId = isset($_GET['editIngredient']) ? intval($_GET['editIngredient']) : 0;
$addMode = isset($_GET['addIngredient']) ? true : false;

echo '<div class="container mt-5">';

// Xử lý form sửa nguyên liệu
if ($editId > 0) {
    $ingredient = $p->getIngredientById($editId);
    echo '<h4 class="text-center mb-4">Cập nhật nguyên liệu #' . $editId . '</h4>';
    if ($ingredient && mysqli_num_rows($ingredient) > 0) {
        $r = mysqli_fetch_assoc($ingredient);
        echo '<form method="POST" action="nhanvienkho.php?action=AIngredients&editIngredient=' . $r['id'] . '">';

        echo '<div class="mb-3"><label>Tên nguyên liệu:</label><input type="text" name="name" value="' . htmlspecialchars($r['name']) . '" class="form-control" required></div>';

        echo '<div class="mb-3"><label>Mô tả:</label><textarea name="description" class="form-control">' . htmlspecialchars($r['description']) . '</textarea></div>';

        // Dropdown nhà cung cấp, chọn đúng nhà cung cấp hiện tại
        echo '<div class="mb-3"><label>Nhà cung cấp:</label><select name="supplier_id" class="form-control" required>';
        echo '<option value="">-- Chọn nhà cung cấp --</option>';
        mysqli_data_seek($suppliers, 0); // reset con trỏ dữ liệu suppliers
        while ($supplier = mysqli_fetch_assoc($suppliers)) {
            $selected = ($r['supplier_id'] == $supplier['id']) ? ' selected' : '';
            echo '<option value="' . $supplier['id'] . '"' . $selected . '>' . htmlspecialchars($supplier['name']) . '</option>';
        }
        echo '</select></div>';

        echo '<div class="mb-3"><label>Số lượng tồn kho:</label>';
        echo '<input type="number" value="' . htmlspecialchars($r['stock_quantity']) . '" class="form-control" disabled></div>';

        // Thêm 1 input hidden để gửi giá trị stock_quantity lên server vì input disabled không gửi
        echo '<input type="hidden" name="stock_quantity" value="' . htmlspecialchars($r['stock_quantity']) . '">';

        echo '<div class="mb-3"><label>Đơn vị:</label><input type="text" name="unit" value="' . htmlspecialchars($r['unit']) . '" class="form-control" required></div>';

        echo '<div class="mb-3"><label>Đơn giá:</label><input type="number" step="0.01" name="unit_price" value="' . htmlspecialchars($r['unit_price']) . '" class="form-control" required></div>';

        echo '<div class="mb-3"><label>Trạng thái:</label>
                <select name="status" class="form-control" required>
                    <option value="active"' . ($r['status'] === 'active' ? ' selected' : '') . '>Active</option>
                    <option value="inactive"' . ($r['status'] === 'inactive' ? ' selected' : '') . '>Inactive</option>
                </select>
              </div>';

        echo '<button type="submit" class="btn btn-primary" name="btnUpdate">Lưu thay đổi</button> ';
        echo '<a href="nhanvienkho.php?action=AIngredients" class="btn btn-danger">Hủy</a>';
        echo '</form>';

        if (isset($_POST["btnUpdate"])) {
            $name = $_POST["name"];
            $description = $_POST["description"];
            $supplier_id = intval($_POST["supplier_id"]);
            $stock_quantity = intval($_POST["stock_quantity"]); // giữ nguyên số lượng cũ
            $unit = $_POST["unit"];
            $unit_price = floatval($_POST["unit_price"]);
            $status = $_POST["status"];

            $updateResult = $p->updateIngredient($editId, $name, $description, $supplier_id, $stock_quantity, $unit, $unit_price, $status);
            if ($updateResult) {
                echo "<script>alert('Cập nhật nguyên liệu thành công!'); window.location.href = 'nhanvienkho.php?action=AIngredients';</script>";
            } else {
                echo "<script>alert('Cập nhật nguyên liệu thất bại!');</script>";
            }
        }
    }
}

// Form thêm nguyên liệu
elseif ($addMode) {
    echo '<h4 class="text-center mb-4">Thêm nguyên liệu mới</h4>';
    echo '<form method="POST" action="nhanvienkho.php?action=AIngredients&addIngredient=true">';

    echo '<div class="mb-3"><label>Tên nguyên liệu:</label><input type="text" name="name" class="form-control" required></div>';

    echo '<div class="mb-3"><label>Mô tả:</label><textarea name="description" class="form-control"></textarea></div>';

    // Dropdown chọn nhà cung cấp
    echo '<div class="mb-3"><label>Nhà cung cấp:</label><select name="supplier_id" class="form-control" required>';
    echo '<option value="">-- Chọn nhà cung cấp --</option>';
    mysqli_data_seek($suppliers, 0);
    while ($supplier = mysqli_fetch_assoc($suppliers)) {
        echo '<option value="' . $supplier['id'] . '">' . htmlspecialchars($supplier['name']) . '</option>';
    }
    echo '</select></div>';

    // Thay vì input number cho stock_quantity
    echo '<input type="hidden" name="stock_quantity" value="0">';
    // Hiển thị số lượng tồn kho = 0 nhưng không cho sửa
    echo '<div class="mb-3"><label>Số lượng tồn kho:</label><input type="number" value="0" class="form-control" disabled></div>';

    echo '<div class="mb-3"><label>Đơn giá:</label><input type="number" step="0.01" name="unit_price" class="form-control" required></div>';

    echo '<div class="mb-3"><label>Trạng thái:</label>
            <select name="status" class="form-control" required>
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
            </select>
          </div>';

    echo '<button type="submit" class="btn btn-success" name="btnInsert">Thêm mới</button> ';
    echo '<a href="nhanvienkho.php?action=AIngredients" class="btn btn-danger">Hủy</a>';
    echo '</form>';

    if (isset($_POST["btnInsert"])) {
        $name = $_POST["name"];
        $description = $_POST["description"];
        $supplier_id = intval($_POST["supplier_id"]);
        $stock_quantity = 0;  // Ép số lượng bằng 0 khi thêm mới
        $unit = $_POST["unit"];
        $unit_price = floatval($_POST["unit_price"]);
        $status = $_POST["status"];

        $insertResult = $p->insertIngredient($name, $description, $supplier_id, $stock_quantity, $unit, $unit_price, $status);
        if ($insertResult) {
            echo "<script>alert('Thêm nguyên liệu thành công!'); window.location.href = 'nhanvienkho.php?action=AIngredients';</script>";
        } else {
            echo "<script>alert('Thêm nguyên liệu thất bại!');</script>";
        }
    }
}

// Hiển thị danh sách và form tìm kiếm
else {
    $searchId = isset($_GET['searchId']) ? trim($_GET['searchId']) : '';
    $searchName = isset($_GET['searchName']) ? trim($_GET['searchName']) : '';

    if ($searchId != '') {
        $kq = $p->getIngredientById($searchId);
    } elseif ($searchName != '') {
        $kq = $p->getIngredientByName($searchName);
    } else {
        $kq = $p->getAllIngredients();
    }

    echo '<h2 class="mb-4 text-center text-dark">Quản lý nguyên liệu</h2>';
    echo '<a href="nhanvienkho.php?action=AIngredients&addIngredient=true" class="btn btn-success mb-3" style="text-decoration: none;">Thêm nguyên liệu</a>';

    // Form tìm kiếm
    echo '<form method="GET" class="row mb-4 justify-content-center">';
    echo '<input type="hidden" name="action" value="AIngredients">';
    echo '<div class="col-md-3">';
    echo '<input type="text" name="searchId" class="form-control" placeholder="Nhập ID nguyên liệu" value="' . htmlspecialchars($searchId) . '">';
    echo '</div>';
    echo '<div class="col-md-3">';
    echo '<input type="text" name="searchName" class="form-control" placeholder="Nhập tên nguyên liệu" value="' . htmlspecialchars($searchName) . '">';
    echo '</div>';
    echo '<div class="col-auto">';
    echo '<button type="submit" class="btn btn-primary">Tìm kiếm</button>';
    echo '<a href="nhanvienkho.php?action=AIngredients" class="btn btn-danger">Hủy</a>';
    echo '</div>';
    echo '</form>';

    if (!$kq || mysqli_num_rows($kq) == 0) {
        echo '<div class="alert alert-warning text-center">❌ Không có dữ liệu nguyên liệu.</div>';
    } else {
        echo '<table class="table table-bordered table-hover text-center bg-light">';
        echo '<thead><tr class="bg-light text-dark">
                <th>ID</th>
                <th>Tên nguyên liệu</th>
                <th>Mô tả</th>
                <th>Nhà cung cấp</th>
                <th>Số lượng tồn kho</th>
                <th>Đơn vị</th>
                <th>Đơn giá</th>
                <th>Trạng thái</th>
                <th>Thao tác</th>
              </tr></thead><tbody>';

        while ($r = mysqli_fetch_assoc($kq)) {
            echo '<tr>';
            echo '<td>' . $r["id"] . '</td>';
            echo '<td>' . htmlspecialchars($r["name"]) . '</td>';
            echo '<td>' . htmlspecialchars($r["description"]) . '</td>';
            // Hiển thị tên nhà cung cấp
            echo '<td>' . htmlspecialchars($r["supplier_name"] ?? $r["supplier_id"]) . '</td>';
            echo '<td>' . $r["stock_quantity"] . '</td>';
            echo '<td>' . htmlspecialchars($r["unit"]) . '</td>';
            echo '<td>' . number_format($r["unit_price"], 2) . '</td>';
            echo '<td>' . ucfirst($r["status"]) . '</td>';
            echo '<td><a href="nhanvienkho.php?action=AIngredients&editIngredient=' . $r['id'] . '" class="btn btn-sm btn-primary">Sửa</a></td>';
            echo '</tr>';
        }
        echo '</tbody></table>';
    }
}

echo '</div>';
?>
