<style>
    .table td, .table th { color: #000; vertical-align: middle; }
</style>
<?php
include_once('Controller/controlReservations.php');
include_once('Controller/cNguoiDung.php');
include_once('Controller/controlTable.php');


$p = new controlReservations();
$u = new cNguoiDung();
$t = new controlTable();

$editId = isset($_GET['editReservation']) ? intval($_GET['editReservation']) : 0;
$addMode = isset($_GET['addReservation']) ? true : false;

echo '<div class="container mt-5">';

// Xử lý form Sửa đặt bàn
if ($editId > 0) {
    $reservation = $p->getAllReservationsWithDetails();
    // Lấy dữ liệu đặt bàn theo id
    $reservationData = false;
    while ($row = mysqli_fetch_assoc($reservation)) {
        if ($row['id'] == $editId) {
            $reservationData = $row;
            break;
        }
    }

    if ($reservationData) {
        echo '<h3 class="text-center mb-4">Cập nhật đặt bàn #' . $editId . '</h3>';
        echo '<form method="POST" action="admin.php?action=AReservations&editReservation=' . $editId . '">';

        echo '<input type="hidden" name="id" value="' . $editId . '">';

        // Chọn khách hàng (username)
        echo '<div class="mb-3"><label>Khách hàng:</label><select name="customer_id" class="form-control">';
        $users = $u->getAllUsers();
        while ($user = mysqli_fetch_assoc($users)) {
            $selected = ($user['id'] == $reservationData['customer_id']) ? 'selected' : '';
            echo '<option value="' . $user['id'] . '" ' . $selected . '>' . htmlspecialchars($user['username']) . '</option>';
        }
        echo '</select></div>';

        // Chọn bàn
        echo '<div class="mb-3"><label>Bàn:</label><select name="table_id" class="form-control">';
        $tables = $t->getAllTables();
        while ($table = mysqli_fetch_assoc($tables)) {
            $selected = ($table['id'] == $reservationData['table_id']) ? 'selected' : '';
            echo '<option value="' . $table['id'] . '" ' . $selected . '>Bàn số ' . $table['table_number'] . '</option>';
        }
        echo '</select></div>';

        // Thời gian đặt
        echo '<div class="mb-3"><label>Thời gian đặt:</label><input type="datetime-local" name="reservation_time" class="form-control" value="' . date('Y-m-d\TH:i', strtotime($reservationData['reservation_time'])) . '" required></div>';

        // Số người
        echo '<div class="mb-3"><label>Số người:</label><input type="number" name="number_of_people" class="form-control" value="' . $reservationData['number_of_people'] . '" required></div>';

        echo '<button type="submit" name="btnUpdate" class="btn btn-primary">Lưu thay đổi</button> ';
        echo '<a href="admin.php?action=AReservations" class="btn btn-danger">Hủy</a>';

        echo '</form>';

        // Xử lý cập nhật khi submit
        if (isset($_POST['btnUpdate'])) {
            $id = $_POST['id'];
            $customer_id = $_POST['customer_id'];
            $table_id = $_POST['table_id'];
            $reservation_time = $_POST['reservation_time'];
            $number_of_people = $_POST['number_of_people'];

            $updateResult = $p->updateReservation($id, $customer_id, $table_id, $reservation_time, $number_of_people);
            if ($updateResult) {
                echo "<script>alert('Cập nhật đặt bàn thành công!'); window.location.href = 'admin.php?action=AReservations';</script>";
            } else {
                echo "<script>alert('Cập nhật đặt bàn thất bại!');</script>";
            }
        }
    } else {
        echo '<div class="alert alert-warning">Không tìm thấy đặt bàn với ID này.</div>';
    }
}

// Thêm đặt bàn mới
elseif ($addMode) {
    echo '<h3 class="text-center mb-4">Thêm đặt bàn mới</h3>';
    echo '<form method="POST" action="admin.php?action=AReservations&addReservation=true">';

    // Chọn khách hàng
    echo '<div class="mb-3"><label>Khách hàng:</label><select name="customer_id" class="form-control" required>';
    $users = $u->getAllUsers();
    while ($user = mysqli_fetch_assoc($users)) {
        echo '<option value="' . $user['id'] . '">' . htmlspecialchars($user['username']) . '</option>';
    }
    echo '</select></div>';

    // Chọn bàn
    echo '<div class="mb-3"><label>Bàn:</label><select name="table_id" class="form-control" required>';
    $tables = $t->getAllTables();
    while ($table = mysqli_fetch_assoc($tables)) {
        echo '<option value="' . $table['id'] . '">Bàn số ' . $table['table_number'] . '</option>';
    }
    echo '</select></div>';

    // Thời gian đặt
    echo '<div class="mb-3"><label>Thời gian đặt:</label><input type="datetime-local" name="reservation_time" class="form-control" required></div>';

    // Số người
    echo '<div class="mb-3"><label>Số người:</label><input type="number" name="number_of_people" class="form-control" required></div>';

    echo '<button type="submit" name="btnInsert" class="btn btn-success">Thêm mới</button> ';
    echo '<a href="admin.php?action=AReservations" class="btn btn-danger">Hủy</a>';

    echo '</form>';

    // Xử lý thêm mới
    if (isset($_POST['btnInsert'])) {
        $customer_id = $_POST['customer_id'];
        $table_id = $_POST['table_id'];
        $reservation_time = $_POST['reservation_time'];
        $number_of_people = $_POST['number_of_people'];

        $insertResult = $p->addReservation($customer_id, $table_id, $reservation_time, $number_of_people);
        if ($insertResult) {
            echo "<script>alert('Thêm đặt bàn thành công!'); window.location.href = 'admin.php?action=AReservations';</script>";
        } else {
            echo "<script>alert('Thêm đặt bàn thất bại!');</script>";
        }
    }
}

// Trang danh sách hoặc tìm kiếm theo ID
else {
    $searchId = isset($_GET['searchId']) ? trim($_GET['searchId']) : '';
    $allReservations = $p->getAllReservationsWithDetails();

    // Nếu tìm kiếm theo ID
    if ($searchId !== '') {
        $filtered = [];
        while ($r = mysqli_fetch_assoc($allReservations)) {
            if ($r['id'] == $searchId) {
                $filtered[] = $r;
                break;
            }
        }
    } else {
        // Không tìm kiếm thì hiện tất cả
        $filtered = [];
        while ($r = mysqli_fetch_assoc($allReservations)) {
            $filtered[] = $r;
        }
    }

    echo '<h2 class="mb-4 text-center text-dark">Quản lý đặt bàn</h2>';
    echo '<a href="admin.php?action=AReservations&addReservation=true" class="btn btn-success mb-3">Thêm đặt bàn</a>';

    // Form tìm kiếm
    echo '<form method="GET" class="row mb-4 justify-content-center">';
    echo '<input type="hidden" name="action" value="AReservations">';
    echo '<div class="col-md-3">';
    echo '<input type="text" name="searchId" class="form-control" placeholder="Nhập ID đặt bàn" value="' . htmlspecialchars($searchId) . '">';
    echo '</div>';
    echo '<div class="col-auto">';
    echo '<button type="submit" class="btn btn-primary">Tìm kiếm</button>';
    echo '</div>';
    echo '</form>';

    // Hiển thị bảng đặt bàn
    if (count($filtered) == 0) {
        echo '<div class="alert alert-warning text-center">❌ Không có dữ liệu đặt bàn.</div>';
    } else {
        echo '<table class="table table-bordered table-hover text-center bg-light">';
        echo '<thead><tr class="bg-light text-dark">
                <th>ID</th>
                <th>Khách hàng</th>
                <th>Bàn</th>
                <th>Thời gian đặt</th>
                <th>Số người</th>
                <th>Thao tác</th>
              </tr></thead><tbody>';

        foreach ($filtered as $r) {
            echo '<tr>';
            echo '<td>' . $r["id"] . '</td>';
            echo '<td>' . htmlspecialchars($r["username"]) . '</td>';
            echo '<td>Bàn số ' . $r["table_number"] . '</td>';
            echo '<td>' . $r["reservation_time"] . '</td>';
            echo '<td>' . $r["number_of_people"] . '</td>';
            echo '<td>
                    <a href="admin.php?action=AReservations&editReservation=' . $r['id'] . '" class="btn btn-sm btn-primary">Sửa</a>
                    <a href="admin.php?action=AReservations&deleteReservation=' . $r['id'] . '" class="btn btn-sm btn-danger" onclick="return confirm(\'Bạn có chắc muốn xóa đặt bàn này?\')">Xóa</a>
                  </td>';
            echo '</tr>';
        }

        echo '</tbody></table>';
    }
}

// Xử lý xóa đặt bàn
if (isset($_GET['deleteReservation'])) {
    $deleteId = intval($_GET['deleteReservation']);
    $delResult = $p->deleteReservation($deleteId);
    if ($delResult) {
        echo "<script>alert('Xóa đặt bàn thành công!'); window.location.href = 'admin.php?action=AReservations';</script>";
    } else {
        echo "<script>alert('Xóa đặt bàn thất bại!');</script>";
    }
}

echo '</div>';
?>
