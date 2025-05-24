<?php
include_once("Controller/controlIngredients.php");
include_once("Controller/controlSuppliers.php");

$p = new controlIngredients();
$supplierCtrl = new controlSuppliers();

$suppliers = $supplierCtrl->getAllSuppliers();

$editId = isset($_GET['editIngredient']) ? intval($_GET['editIngredient']) : 0;
$addMode = isset($_GET['addIngredient']) ? true : false;

echo '<div class="container mt-5">';

// --- XỬ LÝ FORM SỬA NGUYÊN LIỆU ---
if ($editId > 0) {
    $ingredient = $p->getIngredientById($editId);
    echo '<h4 class="text-center mb-4">Cập nhật nguyên liệu #' . $editId . '</h4>';
    if ($ingredient && mysqli_num_rows($ingredient) > 0) {
        $r = mysqli_fetch_assoc($ingredient);
        echo '<form method="POST" action="nhanvienkho.php?action=AIngredients&editIngredient=' . $r['id'] . '">';

        echo '<div class="mb-3"><label>Tên nguyên liệu:</label><input type="text" name="name" value="' . htmlspecialchars($r['name']) . '" class="form-control" required></div>';

        echo '<div class="mb-3"><label>Mô tả:</label><textarea name="description" class="form-control">' . htmlspecialchars($r['description']) . '</textarea></div>';

        echo '<div class="mb-3"><label>Nhà cung cấp:</label><select name="supplier_id" class="form-control" required>';
        echo '<option value="">-- Chọn nhà cung cấp --</option>';
        mysqli_data_seek($suppliers, 0);
        while ($supplier = mysqli_fetch_assoc($suppliers)) {
            $selected = ($r['supplier_id'] == $supplier['id']) ? ' selected' : '';
            echo '<option value="' . $supplier['id'] . '"' . $selected . '>' . htmlspecialchars($supplier['name']) . '</option>';
        }
        echo '</select></div>';

        echo '<div class="mb-3"><label>Số lượng tồn kho:</label>';
        echo '<input type="number" value="' . htmlspecialchars($r['stock_quantity']) . '" class="form-control" disabled></div>';

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
            $stock_quantity = intval($_POST["stock_quantity"]);
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

// --- FORM THÊM NGUYÊN LIỆU ---
elseif ($addMode) {
    echo '<h4 class="text-center mb-4">Thêm nguyên liệu mới</h4>';
    echo '<form method="POST" action="nhanvienkho.php?action=AIngredients&addIngredient=true" id="formAddIngredient">';

    echo '<div class="mb-3"><label>Tên nguyên liệu:</label><input type="text" name="name" class="form-control" required></div>';

    echo '<div class="mb-3"><label>Mô tả:</label><textarea name="description" class="form-control"></textarea></div>';

    echo '<div class="mb-3"><label>Nhà cung cấp:</label><select id="supplierSelect" name="supplier_id" class="form-control" required>';
    echo '<option value="">-- Chọn nhà cung cấp --</option>';
    mysqli_data_seek($suppliers, 0);
    while ($supplier = mysqli_fetch_assoc($suppliers)) {
        echo '<option value="' . $supplier['id'] . '">' . htmlspecialchars($supplier['name']) . '</option>';
    }
    echo '</select></div>';

    echo '<div id="newSupplierFields" style="display:none;">';
    echo '<div class="mb-3"><label>Tên nhà cung cấp mới:</label><input type="text" id="new_supplier_name" name="new_supplier_name" class="form-control"></div>';
    echo '<div class="mb-3"><label>Số điện thoại nhà cung cấp mới (10 số):</label><input type="text" id="new_supplier_contact" name="new_supplier_contact" class="form-control" maxlength="10" pattern="\\d{10}" title="Số điện thoại phải có đúng 10 chữ số"></div>';
    echo '</div>';

    echo '<div class="mb-3"><label>Đơn vị:</label><input type="text" name="unit" class="form-control" required></div>';

    echo '<input type="hidden" name="stock_quantity" value="0">';

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

    // JS điều khiển show/hide, kiểm tra số điện thoại
    echo <<<JS
<script>
document.getElementById('supplierSelect').addEventListener('change', function() {
    if (this.value === '1') {
        document.getElementById('newSupplierFields').style.display = 'block';
        // Bắt buộc nhập trường tên và số điện thoại
        document.getElementById('new_supplier_name').required = true;
        document.getElementById('new_supplier_contact').required = true;
    } else {
        document.getElementById('newSupplierFields').style.display = 'none';
        document.getElementById('new_supplier_name').required = false;
        document.getElementById('new_supplier_contact').required = false;
    }
});

// Kiểm tra số điện thoại khi submit form
document.getElementById('formAddIngredient').addEventListener('submit', function(e) {
    var supplierSelect = document.getElementById('supplierSelect');
    if (supplierSelect.value === '1') {
        var phoneInput = document.getElementById('new_supplier_contact');
        var phone = phoneInput.value.trim();
        var phoneRegex = /^\\d{10}$/;
        if (!phoneRegex.test(phone)) {
            alert('Số điện thoại nhà cung cấp mới phải đúng 10 chữ số!');
            phoneInput.focus();
            e.preventDefault();
            return false;
        }
    }
});
</script>
JS;

    // Xử lý submit thêm mới
    if (isset($_POST["btnInsert"])) {
        $name = $_POST["name"];
        $description = $_POST["description"];
        $supplier_id = intval($_POST["supplier_id"]);
        $stock_quantity = 0;
        $unit = $_POST["unit"];
        $unit_price = floatval($_POST["unit_price"]);
        $status = $_POST["status"];

        if ($supplier_id === 1) {
            $new_supplier_name = trim($_POST["new_supplier_name"] ?? "");
            $new_supplier_contact = trim($_POST["new_supplier_contact"] ?? "");

            // Kiểm tra tên nhà cung cấp mới
            if ($new_supplier_name === "") {
                echo "<script>alert('Vui lòng nhập tên nhà cung cấp mới!');</script>";
            }
            // Kiểm tra số điện thoại hợp lệ (10 số)
            elseif (!preg_match('/^\d{10}$/', $new_supplier_contact)) {
                echo "<script>alert('Số điện thoại nhà cung cấp mới phải đúng 10 chữ số!');</script>";
            }
            else {
                // Kiểm tra nhà cung cấp có trùng không
                if ($supplierCtrl->checkSupplierNameExists($new_supplier_name)) {
                    echo "<script>alert('Nhà cung cấp đã tồn tại! Vui lòng chọn nhà cung cấp khác hoặc sử dụng nhà cung cấp hiện có.');</script>";
                } else {
                    // Thêm nhà cung cấp mới, lấy id
                    $newSupplierId = $supplierCtrl->insertSupplier($new_supplier_name, $new_supplier_contact);
                    if ($newSupplierId) {
                        $supplier_id = $newSupplierId;
                    } else {
                        echo "<script>alert('Thêm nhà cung cấp mới thất bại!');</script>";
                    }
                }
            }
        }

        if ($supplier_id !== 1) {
            $insertResult = $p->insertIngredient($name, $description, $supplier_id, $stock_quantity, $unit, $unit_price, $status);
            if ($insertResult) {
                echo "<script>alert('Thêm nguyên liệu thành công!'); window.location.href = 'nhanvienkho.php?action=AIngredients';</script>";
            } else {
                echo "<script>alert('Thêm nguyên liệu thất bại!');</script>";
            }
        }
    }
}

// --- HIỂN THỊ DANH SÁCH NGUYÊN LIỆU & TÌM KIẾM ---
else {
    $searchId = isset($_GET['searchId']) ? trim($_GET['searchId']) : '';
    $searchName = isset($_GET['searchName']) ? trim($_GET['searchName']) : '';

    if ($searchId !== '') {
        $kq = $p->getIngredientById($searchId);
    } elseif ($searchName !== '') {
        $kq = $p->getIngredientByName($searchName);
    } else {
        $kq = $p->getAllIngredients();
    }

    echo '<h2 class="mb-4 text-center text-dark">Quản lý nguyên liệu</h2>';
    echo '<a href="nhanvienkho.php?action=AIngredients&addIngredient=true" class="btn btn-success mb-3" style="text-decoration:none;">Thêm nguyên liệu</a>';

    echo '<form method="GET" class="row mb-4 justify-content-center">';
    echo '<input type="hidden" name="action" value="AIngredients">';
    echo '<div class="col-md-3">';
    echo '<input type="text" name="searchId" class="form-control" placeholder="Nhập ID nguyên liệu" value="' . htmlspecialchars($searchId) . '">';
    echo '</div>';
    echo '<div class="col-md-3">';
    echo '<input type="text" name="searchName" class="form-control" placeholder="Nhập tên nguyên liệu" value="' . htmlspecialchars($searchName) . '">';
    echo '</div>';
    echo '<div class="col-auto">';
    echo '<button type="submit" class="btn btn-primary">Tìm kiếm</button> ';
    echo '<a href="nhanvienkho.php?action=AIngredients" class="btn btn-danger">Hủy</a>';
    echo '</div>';
    echo '</form>';

    if (!$kq || mysqli_num_rows($kq) == 0) {
        echo '<div class="alert alert-warning text-center">❌ Không có dữ liệu nguyên liệu.</div>';
    } else {
        echo '<table class="table table-bordered table-hover text-center bg-light">';
        echo '<thead><tr class="bg-light text-dark">';
        echo '<th>ID</th><th>Tên nguyên liệu</th><th>Mô tả</th><th>Nhà cung cấp</th><th>Số lượng tồn kho</th><th>Đơn vị</th><th>Đơn giá</th><th>Trạng thái</th><th>Thao tác</th>';
        echo '</tr></thead><tbody>';

        while ($r = mysqli_fetch_assoc($kq)) {
            echo '<tr>';
            echo '<td>' . htmlspecialchars($r["id"]) . '</td>';
            echo '<td>' . htmlspecialchars($r["name"]) . '</td>';
            echo '<td>' . htmlspecialchars($r["description"]) . '</td>';
            echo '<td>' . htmlspecialchars($r["supplier_name"] ?? $r["supplier_id"]) . '</td>';
            echo '<td>' . htmlspecialchars($r["stock_quantity"]) . '</td>';
            echo '<td>' . htmlspecialchars($r["unit"]) . '</td>';
            echo '<td>' . number_format($r["unit_price"], 2) . '</td>';
            echo '<td>' . ucfirst(htmlspecialchars($r["status"])) . '</td>';
            echo '<td><a href="nhanvienkho.php?action=AIngredients&editIngredient=' . htmlspecialchars($r['id']) . '" class="btn btn-sm btn-primary">Sửa</a></td>';
            echo '</tr>';
        }

        echo '</tbody></table>';
    }
}

echo '</div>';
?>
