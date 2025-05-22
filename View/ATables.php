<?php
include_once('Controller/controlTable.php');

$t = new controlTable();

echo '<div class="container mt-5">';

// --- Xử lý thêm bàn ---
if (isset($_POST['btnAdd'])) {
    $table_number = $_POST['table_number'];
    $status = $_POST['status'];

    if ($t->addTable($table_number, $status)) {
        echo "<script>alert('Thêm bàn thành công'); window.location.href='nhanvientieptan.php?action=ATables';</script>";
        exit;
    } else {
        echo "<div class='alert alert-danger'>Thêm bàn thất bại!</div>";
    }
}

// --- Xử lý cập nhật trạng thái ---
if (isset($_POST['btnUpdateStatus'])) {
    $id = $_POST['id'];
    $status = $_POST['status'];

    if ($t->updateTableStatus($id, $status)) {
        echo "<script>alert('Cập nhật trạng thái bàn thành công'); window.location.href='nhanvientieptan.php?action=ATables';</script>";
        exit;
    } else {
        echo "<div class='alert alert-danger'>Cập nhật trạng thái thất bại!</div>";
    }
}

// --- Hiển thị danh sách bàn ---
$tables = $t->getAllTables();

echo '<h2>Quản lý bàn</h2>';
echo '<a href="nhanvientieptan.php?action=ATables&add=true#pricing" class="btn btn-success mb-3">Thêm bàn mới</a>';

// Nếu ở trang thêm bàn
if (isset($_GET['add'])) {
    echo '<form method="POST" class="mb-4">';
    echo '<div class="mb-3"><label>Số bàn:</label><input type="text" name="table_number" class="form-control" required></div>';
    echo '<div class="mb-3"><label>Trạng thái:</label><select name="status" class="form-control" required>';
    echo '<option value="Available">Available</option>';
    echo '<option value="Reserved">Reserved</option>';
    echo '<option value="Occupied">Occupied</option>';
    echo '</select></div>';
    echo '<button type="submit" name="btnAdd" class="btn btn-primary">Thêm bàn</button> ';
    echo '<a href="nhanvientieptan.php?action=ATables#pricing" class="btn btn-secondary btn-danger">Hủy</a>';
    echo '</form>';
}

// Hiển thị bảng bàn
if (!$tables) {
    echo '<div class="alert alert-warning">Không có bàn nào.</div>';
} else {
    echo '<table class="table table-bordered">';
    echo '<thead><tr><th>ID</th><th>Số bàn</th><th>Trạng thái</th><th>Thao tác</th></tr></thead><tbody>';

    while ($row = mysqli_fetch_assoc($tables)) {
        echo '<tr>';
        echo '<td>' . $row['id'] . '</td>';
        echo '<td>' . $row['table_number'] . '</td>';
        echo '<td>' . $row['status'] . '</td>';
        echo '<td>';
        // Form sửa trạng thái
        echo '<form method="POST" style="display:inline-block;">';
        echo '<input type="hidden" name="id" value="' . $row['id'] . '">';
        echo '<select name="status" class="form-select form-select-sm d-inline-block" style="width:auto;">';
        $statuses = ['Available', 'Reserved', 'Occupied'];
        foreach ($statuses as $status) {
            $sel = ($status == $row['status']) ? 'selected' : '';
            echo "<option value='$status' $sel>$status</option>";
        }
        echo '</select> ';
        echo '<button type="submit" name="btnUpdateStatus" class="btn btn-sm btn-primary">Cập nhật</button>';
        echo '</form>';
        echo '</td>';
        echo '</tr>';
    }
    echo '</tbody></table>';
}

echo '</div>';
?>
