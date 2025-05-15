<?php
include_once("Controller/cNguoiDung.php");
$p = new cNguoiDung();

$editId = isset($_GET['editUser']) ? intval($_GET['editUser']) : 0;
$addMode = isset($_GET['addUser']) ? true : false;

echo '<style>
    .table td, .table th { color: #000; vertical-align: middle; }
    .user-container { margin-top: 40px; }
</style>';

echo '<div class="user-container container">';

// Xử lý khi form "Sửa người dùng" được gửi
if ($editId > 0) {
    $user = $p->getOneNguoiDung($editId);
    echo '<h4 class="text-center mb-4">Cập nhật người dùng #' . $editId . '</h4>';
    if ($user && mysqli_num_rows($user) > 0) {
        $r = mysqli_fetch_assoc($user);
        echo '<form method="POST" action="admin.php?action=ANguoiDung&editUser=' . $r['id'] . '">';
        echo '<input type="hidden" name="id" value="' . $r['id'] . '">';

        // Tên người dùng
        echo '<div class="mb-3"><label>Tên:</label><input type="text" name="tennd" value="' . htmlspecialchars($r['username']) . '" class="form-control" required></div>';

        // Email
        echo '<div class="mb-3">
            <label for="email">Email:</label>
            <input type="email" name="email" id="email" value="' . htmlspecialchars($r['email']) . '" class="form-control" required 
                pattern="[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$" 
                title="Vui lòng nhập một email hợp lệ, ví dụ: user@example.com">
        </div>';

        // Điện thoại
        echo '<div class="mb-3"><label>Điện thoại:</label><input type="text" name="phone" value="' . htmlspecialchars($r['phone']) . '" class="form-control"></div>';

        // Mật khẩu
        echo '<div class="mb-3"><label>Mật khẩu mới:</label><input type="password" name="password" class="form-control"></div>';

        // Vai trò
        echo '<div class="mb-3">
            <label for="role_id">Vai trò:</label>
            <select name="role_id" id="role_id" class="form-control" required>';

        // Lấy tất cả các vai trò
        $roles = $p->getAllRoles();
        while ($role = mysqli_fetch_assoc($roles)) {
            $selected = ($role['id'] == $r['role_id']) ? 'selected' : '';
            echo '<option value="' . $role['id'] . '" ' . $selected . '>' . htmlspecialchars($role['name']) . '</option>';
        }
        echo '</select></div>';

        // Trạng thái
        echo '<div class="mb-3"><label>Trạng thái:</label>
            <select name="status" class="form-control">
                <option value="active" ' . ($r['status'] == 'active' ? 'selected' : '') . '>Hoạt động</option>
                <option value="inactive" ' . ($r['status'] == 'inactive' ? 'selected' : '') . '>Ngưng hoạt động</option>
            </select></div>';

        // Nút Lưu thay đổi và Hủy
        echo '<button type="submit" class="btn btn-primary" name="btnUpdate">Lưu thay đổi</button>';

        // Xử lý khi bấm "Lưu thay đổi"
        if (isset($_REQUEST["btnUpdate"])) {
    // Lấy dữ liệu từ form sửa
    $idnd = $_REQUEST["id"];
    $tennd = $_REQUEST["tennd"];
    $mknd = md5($_REQUEST["password"]);  // Mã hóa mật khẩu với MD5
    $email = $_REQUEST["email"];
    $phone = $_REQUEST["phone"];
    $role_id = $_REQUEST["role_id"];
    $status = $_REQUEST["status"];  // Lấy trạng thái từ form

            // Kiểm tra email trùng với người khác (ngoại trừ người dùng hiện tại)
            if ($p->checkEmailExistsForUpdate($email, $idnd)) {
                echo "<script>alert('Email này đã tồn tại. Vui lòng sử dụng email khác.');</script>";
            } else {
                // Sửa thông tin người dùng
                $kq = $p->updateNguoiDung($idnd, $tennd, $email, $mknd, $phone, $role_id, $status);

                // Kiểm tra kết quả
                if ($kq) {
                    echo "<script>alert('Cập nhật người dùng thành công!'); window.location.href = 'admin.php?action=ANguoiDung';</script>";
                } else {
                    echo "<script>alert('Cập nhật người dùng thất bại!');</script>";
                }
            }
        }
        echo '<a href="admin.php?action=ANguoiDung" class="btn btn-danger">Hủy</a>';
        echo '</form>';
    }
}

// Nếu là form "Thêm người dùng"
elseif ($addMode) {
    echo '<h4 class="text-center mb-4">Thêm người dùng mới</h4>';
    echo '<form method="POST" action="admin.php?action=ANguoiDung&addUser=true">';
    echo '<div class="mb-3"><label>Tên:</label><input type="text" name="tennd" class="form-control" required></div>';
    echo '<div class="mb-3">
        <label for="email">Email:</label>
        <input type="email" name="email" id="email" class="form-control" required 
            pattern="[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$" 
            title="Vui lòng nhập một email hợp lệ, ví dụ: user@example.com">
    </div>';
    echo '<div class="mb-3"><label>Điện thoại:</label><input type="text" name="phone" class="form-control"></div>';
    echo '<div class="mb-3"><label>Mật khẩu:</label><input type="password" name="password" class="form-control" required></div>';
    echo '<div class="mb-3">
    <label for="role_id">Vai trò:</label>
    <select name="role_id" id="role_id" class="form-control" required>';

    // Lặp qua các vai trò và tạo các option
    $roles = $p->getAllRoles();
    while ($role = mysqli_fetch_assoc($roles)) {
        echo '<option value="' . $role['id'] . '">' . htmlspecialchars($role['name']) . '</option>';
    }
    echo '</select></div>';

    echo '<button type="submit" class="btn btn-success" name="btnInsert">Thêm mới</button> ';

    // Xử lý khi bấm "Thêm mới"
    if (isset($_POST["btnInsert"])) {
        // Lấy dữ liệu từ form
        $tennd = $_POST["tennd"];
        $mknd = md5($_POST["password"]);  // Mã hóa mật khẩu với MD5
        $email = $_POST["email"];
        $phone = $_POST["phone"];
        $role_id = $_POST["role_id"];
        $status = "active"; // Trạng thái mặc định là "active"

        // Kiểm tra email có tồn tại trong cơ sở dữ liệu
        if ($p->checkEmailExists($email)) {
            echo "<script>alert('Email này đã tồn tại. Vui lòng sử dụng email khác.');</script>";
        } else {
            // Thêm người dùng
            $kq = $p->themNguoiDung($tennd, $email, $mknd, $phone, $role_id, $status);

            // Kiểm tra kết quả
            if ($kq) {
                echo "<script>alert('Thêm người dùng thành công!'); window.location.href = 'admin.php?action=ANguoiDung';</script>";
            } else {
                echo "<script>alert('Thêm người dùng thất bại!');</script>";
            }
        }
    }

    echo '<a href="admin.php?action=ANguoiDung" class="btn btn-danger">Hủy</a>';
    echo '</form>';
}

else {
// Lấy thông tin tìm kiếm theo mã người dùng (ID)
$searchId = isset($_GET['searchId']) ? trim($_GET['searchId']) : '';

// Nếu có tìm kiếm theo ID, lấy dữ liệu tìm kiếm
if ($searchId != '') {
    // Lọc theo ID người dùng
    $kq = $p->getUsersById($searchId);
} else {
    // Nếu không tìm kiếm, hiển thị tất cả người dùng
    $kq = $p->getAllNguoiDung();
}
echo '<h2 class="mb-4 text-center text-dark">Quản lý đơn hàng</h2>';
echo '<a href="admin.php?action=ANguoiDung&addUser=true" class="btn btn-success" style="text-decoration: none;">Thêm người dùng</a>';

echo '<div class="user-container container">';
// Form tìm kiếm
echo '<form method="GET" class="row mb-4 justify-content-center">';
echo '<input type="hidden" name="action" value="ANguoiDung">';

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
if (!$kq) {
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
        echo '<td><a href="admin.php?action=ANguoiDung&editUser=' . $r['id'] . '" class="btn btn-sm btn-primary">Sửa</a></td>';
        echo '</tr>';
    }
    echo '</tbody></table>';
}}
echo '</div>';
?>
