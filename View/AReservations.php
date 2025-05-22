<style>
    .table td, .table th { color: #000; vertical-align: middle; }
</style>

<?php
include_once('Controller/controlReservations.php');
include_once('Controller/controlTable.php');

$p = new controlReservations();
$t = new controlTable();

$editId = isset($_GET['editReservation']) ? intval($_GET['editReservation']) : 0;
$addMode = isset($_GET['addReservation']) ? true : false;

echo '<div class="container mt-5">';

// --- Sửa đặt bàn ---
if ($editId > 0) {
    $reservation = $p->getAllReservationsWithDetails();
    if (!$reservation) {
        echo '<div class="alert alert-warning">Lỗi hoặc không có dữ liệu đặt bàn.</div>';
    } else {
        $reservationData = false;
        while ($row = mysqli_fetch_assoc($reservation)) {
            if ($row['id'] == $editId) {
                $reservationData = $row;
                break;
            }
        }
        if ($reservationData) {
            echo '<h3 class="text-center mb-4">Cập nhật đặt bàn #' . $editId . '</h3>';
            echo '<form method="POST" action="nhanvientieptan.php?action=AReservations&editReservation=' . $editId . '">';
            echo '<input type="hidden" name="id" value="' . $editId . '">';

            // Nhập email khách hàng (có thể để trống)
            $emailValue = htmlspecialchars($reservationData['email'] ?? '');
            echo '<div class="mb-3"><label>Email khách hàng:</label>';
            echo '<input type="email" name="customer_email" value="' . $emailValue . '" class="form-control" placeholder="Có thể để trống"></div>';
            
            // Dropdown bàn
            $tables = $t->getAllTables();
            echo '<div class="mb-3"><label>Bàn:</label><select name="table_id" class="form-control" required>';
            if ($tables && mysqli_num_rows($tables) > 0) {
                while ($table = mysqli_fetch_assoc($tables)) {
                    $selected = ($table['id'] == $reservationData['table_id']) ? 'selected' : '';
                    echo '<option value="' . $table['id'] . '" ' . $selected . '>Bàn số ' . $table['table_number'] . '</option>';
                }
            } else {
                echo '<option value="">Không có bàn</option>';
            }
            echo '</select></div>';

            // Lấy thời gian hiện tại cho datetime-local
            $now = date('Y-m-d\TH:i'); // thời gian hiện tại theo định dạng cho datetime-local

            // Nếu đang sửa, gán giá trị thời gian hiện tại vào ô nhập, nếu không để trống
            echo '<div class="mb-3"><label>Thời gian đặt:</label>';
            echo '<input type="datetime-local" name="reservation_time" class="form-control" value="' . date('Y-m-d\TH:i', strtotime($reservationData['reservation_time'])) . '" min="' . $now . '" required>';
            echo '</div>';

            // Số người
            echo '<div class="mb-3"><label>Số người:</label><input type="number" name="number_of_people" class="form-control" value="' . $reservationData['number_of_people'] . '" min="1" required></div>';

            // Mô tả
            $descVal = htmlspecialchars($reservationData['description'] ?? '');
            echo '<div class="mb-3"><label>Mô tả:</label><textarea name="description" class="form-control" rows="3">' . $descVal . '</textarea></div>';

            echo '<button type="submit" name="btnUpdate" class="btn btn-primary">Lưu thay đổi</button> ';
            echo '<a href="nhanvientieptan.php?action=AReservations" class="btn btn-danger">Hủy</a>';
            echo '</form>';

            // Xử lý cập nhật
            if (isset($_POST['btnUpdate'])) {
                $id = $_POST['id'];
                $customer_email = trim($_POST['customer_email'] ?? '');
                $table_id = $_POST['table_id'];
                $reservation_time = $_POST['reservation_time'];
                $number_of_people = $_POST['number_of_people'];
                $description = $_POST['description'] ?? '';

                // Kiểm tra thời gian nhập có hợp lệ (không được nhỏ hơn thời gian hiện tại)
                $now = date('Y-m-d H:i:s');
                if ($reservation_time < $now) {
                    echo "<script>alert('Thời gian đặt bàn không thể là thời gian đã qua. Vui lòng chọn thời gian hợp lệ!'); history.back();</script>";
                    exit;
                }

                $updateResult = $p->updateReservationByEmail($id, $customer_email, $table_id, $reservation_time, $number_of_people, $description);
                if ($updateResult) {
                    echo "<script>alert('Cập nhật đặt bàn thành công!'); window.location.href = 'nhanvientieptan.php?action=AReservations';</script>";
                    exit;
                } else {
                    echo "<script>alert('Cập nhật đặt bàn thất bại!');</script>";
                }
            }
        } else {
            echo '<div class="alert alert-warning">Không tìm thấy đặt bàn với ID này.</div>';
        }
    }
}

// --- Thêm đặt bàn mới ---
elseif ($addMode) {
    echo '<h3 class="text-center mb-4">Thêm đặt bàn mới</h3>';
    echo '<form method="POST" action="nhanvientieptan.php?action=AReservations&addReservation=true">';

    // Nhập email khách hàng (có thể để trống)
    echo '<div class="mb-3"><label>Email khách hàng:</label>';
    echo '<input type="email" name="customer_email" class="form-control" placeholder="Có thể để trống"></div>';

    $tables = $t->getAllTables();
    echo '<div class="mb-3"><label>Bàn:</label><select name="table_id" class="form-control" required>';
    if ($tables && mysqli_num_rows($tables) > 0) {
        while ($table = mysqli_fetch_assoc($tables)) {
            echo '<option value="' . $table['id'] . '">Bàn số ' . $table['table_number'] . '</option>';
        }
    } else {
        echo '<option value="">Không có bàn</option>';
    }
    echo '</select></div>';

    // Lấy thời gian hiện tại cho datetime-local
    $now = date('Y-m-d\TH:i'); // thời gian hiện tại theo định dạng cho datetime-local

    echo '<div class="mb-3"><label>Thời gian đặt:</label>';
    echo '<input type="datetime-local" name="reservation_time" class="form-control" min="' . $now . '" required>';
    echo '</div>';

    echo '<div class="mb-3"><label>Số người:</label><input type="number" name="number_of_people" class="form-control" min="1" required></div>';

    echo '<div class="mb-3"><label>Mô tả:</label><textarea name="description" class="form-control" rows="3"></textarea></div>';

    echo '<button type="submit" name="btnInsert" class="btn btn-success">Thêm mới</button> ';
    echo '<a href="nhanvientieptan.php?action=AReservations" class="btn btn-danger">Hủy</a>';
    echo '</form>';

    if (isset($_POST['btnInsert'])) {
        $customer_email = trim($_POST['customer_email'] ?? '');
        $table_id = $_POST['table_id'];
        $reservation_time = $_POST['reservation_time'];
        $number_of_people = $_POST['number_of_people'];
        $description = $_POST['description'] ?? '';

        // Kiểm tra thời gian nhập có hợp lệ (không được nhỏ hơn thời gian hiện tại)
        $now = date('Y-m-d H:i:s');
        if ($reservation_time < $now) {
            echo "<script>alert('Thời gian đặt bàn không thể là thời gian đã qua. Vui lòng chọn thời gian hợp lệ!'); history.back();</script>";
            exit;
        }

        $insertResult = $p->addReservationByEmail($customer_email, $table_id, $reservation_time, $number_of_people, $description);
        if ($insertResult) {
            echo "<script>alert('Thêm đặt bàn thành công!'); window.location.href = 'nhanvientieptan.php?action=AReservations';</script>";
            exit;
        } else {
            echo "<script>alert('Thêm đặt bàn thất bại!');</script>";
        }
    }
}

// --- Trang danh sách và tìm kiếm ---
else {
    $searchEmail = isset($_GET['searchEmail']) ? trim($_GET['searchEmail']) : '';
    $filtered = [];

    if ($searchEmail !== '') {
        $allReservations = $p->getReservationsByEmail($searchEmail);
    } else {
        // Xóa đặt bàn đã quá hạn trước khi lấy danh sách
        $p->deleteExpiredReservations();

        $allReservations = $p->getAllReservationsWithDetails();
    }

    if ($allReservations && mysqli_num_rows($allReservations) > 0) {
        while ($r = mysqli_fetch_assoc($allReservations)) {
            $filtered[] = $r;
        }
    }

    echo '<h2 class="mb-4 text-center text-dark">Quản lý đặt bàn</h2>';
    echo '<a href="nhanvientieptan.php?action=AReservations&addReservation=true" class="btn btn-success mb-3">Thêm đặt bàn</a>';

    // Form tìm kiếm theo email
    echo '<form method="GET" class="row mb-4 justify-content-center">';
    echo '<input type="hidden" name="action" value="AReservations">';
    echo '<div class="col-md-4">';
    echo '<input type="text" name="searchEmail" class="form-control" placeholder="Nhập email khách hàng" value="' . htmlspecialchars($searchEmail) . '">';
    echo '</div>';
    echo '<div class="col-auto">';
    echo '<button type="submit" class="btn btn-primary">Tìm kiếm</button>';
    echo '<a href="nhanvientieptan.php?action=AReservations#pricing" class="btn btn-danger">Hủy</a>';
    echo '</div>';
    echo '</form>';

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
                <th>Mô tả</th>
                <th>Thao tác</th>
              </tr></thead><tbody>';

        foreach ($filtered as $r) {
            echo '<tr>';
            echo '<td>' . $r["id"] . '</td>';
            echo '<td>' . htmlspecialchars($r["username"] ?? '') . '</td>';
            echo '<td>Bàn số ' . $r["table_number"] . '</td>';
            echo '<td>' . $r["reservation_time"] . '</td>';
            echo '<td>' . $r["number_of_people"] . '</td>';
            echo '<td>' . htmlspecialchars($r["description"] ?? '') . '</td>';
            echo '<td>
                    <a href="nhanvientieptan.php?action=AReservations&editReservation=' . $r['id'] . '" class="btn btn-sm btn-primary">Sửa</a>
                    <a href="nhanvientieptan.php?action=AReservations&deleteReservation=' . $r['id'] . '" class="btn btn-sm btn-danger" onclick="return confirm(\'Bạn có chắc muốn xóa đặt bàn này?\')">Xóa</a>
                  </td>';
            echo '</tr>';
        }

        echo '</tbody></table>';
    }
}

// --- Xóa đặt bàn ---
if (isset($_GET['deleteReservation'])) {
    $deleteId = intval($_GET['deleteReservation']);
    $delResult = $p->deleteReservation($deleteId);
    if ($delResult) {
        echo "<script>alert('Xóa đặt bàn thành công!'); window.location.href = 'nhanvientieptan.php?action=AReservations';</script>";
        exit;
    } else {
        echo "<script>alert('Xóa đặt bàn thất bại!');</script>";
    }
}

echo '</div>';
?>
