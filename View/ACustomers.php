<?php
include_once("Controller/cNguoiDung.php");
$p = new cNguoiDung();

$editId = isset($_GET['editUser']) ? intval($_GET['editUser']) : 0;
echo '<style>
    .table td, .table th { color: #000; vertical-align: middle; }
    .user-container { margin-top: 40px; }
</style>';

echo '<div class="user-container container">';

if ($editId > 0) {
    $user = $p->getOneNguoiDung($editId);
    echo '<h4 class="text-center mb-4">Xem thông tin khách hàng #' . $editId . '</h4>';
    if ($user && mysqli_num_rows($user) > 0) {
        $r = mysqli_fetch_assoc($user);

        // Thay form thành div hoặc giữ form nhưng input chỉ đọc
        echo '<form>';

        // Tên người dùng (readonly)
        echo '<div class="mb-3"><label>Tên:</label><input type="text" value="' . htmlspecialchars($r['username']) . '" class="form-control" readonly></div>';

        // Email (readonly)
        echo '<div class="mb-3">
            <label for="email">Email:</label>
            <input type="email" value="' . htmlspecialchars($r['email']) . '" class="form-control" readonly>
        </div>';

        // Điện thoại (readonly)
        echo '<div class="mb-3"><label>Điện thoại:</label><input type="text" value="' . htmlspecialchars($r['phone']) . '" class="form-control" readonly></div>';

        // Địa chỉ (readonly)
        echo '<div class="mb-3"><label>Địa chỉ:</label><input type="text" value="' . htmlspecialchars($r['address']) . '" class="form-control" readonly></div>';

        // Vai trò (readonly, dùng select disabled hoặc span)
        echo '<div class="mb-3">
                <label>Vai trò:</label>
                <input type="text" value="' . htmlspecialchars($r['role_name']) . '" class="form-control" readonly>
              </div>';

        // Trạng thái (readonly)
        echo '<div class="mb-3">
                <label>Trạng thái:</label>
                <input type="text" value="' . ($r['status'] === 'active' ? 'Hoạt động' : 'Ngưng hoạt động') . '" class="form-control" readonly>
              </div>';

        // Bỏ nút lưu thay đổi
        echo '<a href="nhanvientieptan.php?action=ACustomers" class="btn btn-danger">Quay lại</a>';


        echo '</form>';
    }
}


else {
// Lấy thông tin tìm kiếm theo mã người dùng (ID)
$searchId = isset($_GET['searchId']) ? trim($_GET['searchId']) : '';

// Nếu có tìm kiếm theo ID, lấy dữ liệu tìm kiếm
if ($searchId != '') {
    // Lọc theo ID người dùng
    $kq = $p->getUsersByIdNV($searchId);
} else {
    // Nếu không tìm kiếm, hiển thị tất cả người dùng
    $kq = $p->getAllNguoiDungNV();
}
echo '<h2 class="mb-4 text-center text-dark">Quản lý khách hàng</h2>';

echo '<div class="user-container container">';
// Form tìm kiếm
echo '<form method="GET" class="row mb-4 justify-content-center">';
echo '<input type="hidden" name="action" value="ACustomers">';

// Tìm kiếm theo ID người dùng
echo '<div class="col-md-3">';
echo '<input type="text" name="searchId" class="form-control" placeholder="Nhập ID người dùng" value="' . htmlspecialchars($searchId) . '">';
echo '</div>';

// Nút tìm kiếm
echo '<div class="col-auto">';
echo '<button type="submit" class="btn btn-primary">Tìm kiếm</button>';
echo '</div>';
echo '</form>';

// Hiển thị kết quả tìm kiếm hoặc tất cả người dùng
if (!$kq || mysqli_num_rows($kq) == 0) {
    echo '<div class="alert alert-warning text-center">❌ Không có dữ liệu người dùng.</div>';
} else {
    echo '<table class="table table-bordered table-hover text-center bg-light">';
    echo '<thead><tr class="bg-light text-dark">
            <th>Mã ND</th>
            <th>Tên ND</th>
            <th>Email</th>
            <th>Điện thoại</th>
            <th>Quyền</th>
            <th>Trạng thái</th>
            <th>Thao tác</th>
          </tr></thead><tbody>';

    while ($r = mysqli_fetch_assoc($kq)) {
        echo '<tr>';
        echo '<td>' . $r["id"] . '</td>';
        echo '<td>' . htmlspecialchars($r["username"]) . '</td>';
        echo '<td>' . htmlspecialchars($r["email"]) . '</td>';
        echo '<td>' . htmlspecialchars($r["phone"]) . '</td>';
        echo '<td>' . htmlspecialchars($r["role_name"]) . '</td>';
        echo '<td>' . ($r["status"] === "active" ? "<span class='text-success'>Hoạt động</span>" : "<span class='text-danger'>Ngưng hoạt động</span>") . '</td>';
        echo '<td><a href="nhanvientieptan.php?action=ACustomers&editUser=' . $r['id'] . '" class="btn btn-sm btn-primary">Xem</a></td>';
        echo '</tr>';
    }
    echo '</tbody></table>';
}}
echo '</div>';
?>
