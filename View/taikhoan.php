<?php
session_start();
if (!isset($_SESSION["RoleID"]) || $_SESSION["RoleID"] != 4) {
    echo "<script>alert('Không có quyền truy cập');</script>";
    header("refresh:0;url='index.php'");
    exit;
}

include_once("Controller/cNguoiDung.php");

$controller = new cNguoiDung();
$userId = $_SESSION["UserID"];

// Lấy dữ liệu người dùng hiện tại
$userDataResult = $controller->getOneNguoiDung($userId);
if (!$userDataResult || mysqli_num_rows($userDataResult) == 0) {
    echo "Không tìm thấy người dùng.";
    exit;
}
$userData = mysqli_fetch_assoc($userDataResult);

$success = null;
$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['btnUpdate'])) {
    $newUsername = trim($_POST['username'] ?? '');
    $newEmail = trim($_POST['email'] ?? '');
    $newPassword = trim($_POST['password'] ?? '');
    $newPhone = trim($_POST['phone'] ?? '');
    $newAddress = trim($_POST['address'] ?? '');
    
    // Nếu không nhập mật khẩu mới thì giữ mật khẩu cũ
    if (empty($newPassword)) {
        $newPassword = $userData['password']; // đã là mã hóa md5 trong db
    }

    // Các thông tin khác giữ nguyên role_id và status của người dùng hiện tại
    $roleId = $userData['role_id'];
    $status = $userData['status'];

    // Cập nhật người dùng
    $updateResult = $controller->updateNguoiDung(
        $userId,
        $newUsername,
        $newEmail,
        $newPassword,
        $newPhone,
        $newAddress,
        $roleId,
        $status
    );

    if ($updateResult) {
        $success = "Cập nhật thông tin thành công!";
        // Cập nhật session email và username nếu thay đổi
        $_SESSION["Email"] = $newEmail;
        // Nếu bạn lưu username trong session, cập nhật luôn tại đây
    } else {
        $error = "Cập nhật thông tin thất bại hoặc email đã tồn tại!";
    }

    // Lấy lại dữ liệu mới nhất để hiển thị
    $userDataResult = $controller->getOneNguoiDung($userId);
    $userData = mysqli_fetch_assoc($userDataResult);
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Quản lý tài khoản</title>
    <link rel="stylesheet" href="css/normalize.css">
    <link rel="stylesheet" href="css/main.css" media="screen" type="text/css">
    <link href='http://fonts.googleapis.com/css?family=Pacifico' rel='stylesheet' type='text/css'>
    <link href='http://fonts.googleapis.com/css?family=Playball' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" href="css/style-portfolio.css">
    <link rel="stylesheet" href="css/picto-foundry-food.css" />
    <link rel="stylesheet" href="css/jquery-ui.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="css/font-awesome.min.css" rel="stylesheet">
    <link rel="icon" href="favicon-1.ico" type="image/x-icon">
    <style>
        .profile-form-container {
            max-width: 450px;
            margin: 30px auto;
            padding: 20px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px #ccc;
            color: black; /* Màu chữ đen */
        }
        .profile-form-container label {
            font-weight: bold;
        }
        .profile-form-container input[type="text"],
        .profile-form-container input[type="email"],
        .profile-form-container input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 5px;
            border: 1px solid #ccc;
            box-sizing: border-box;
            color: black; /* Màu chữ đen */
        }
        .profile-form-container button {
            margin-right: 10px;
        }
        .alert-success {
            color: green;
            font-weight: bold;
            margin-bottom: 15px;
        }
        .alert-error {
            color: red;
            font-weight: bold;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>

<div class="profile-form-container">
    <h3>Thông tin tài khoản</h3>

    <?php if ($success): ?>
        <div class="alert-success"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>
    <?php if ($error): ?>
        <div class="alert-error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="post" action="">
        <label for="username">Tên đăng nhập:</label>
        <input type="text" id="username" name="username" value="<?= htmlspecialchars($userData['username']) ?>" required>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" value="<?= htmlspecialchars($userData['email']) ?>" required>

        <label for="password">Mật khẩu:</label>
        <input type="password" id="password" name="password" placeholder="Nhập mật khẩu mới nếu muốn thay đổi">

        <label for="phone">Số điện thoại:</label>
        <input type="text" id="phone" name="phone" value="<?= htmlspecialchars($userData['phone']) ?>">

        <label for="address">Địa chỉ:</label>
        <input type="text" id="address" name="address" value="<?= htmlspecialchars($userData['address']) ?>">

        <button type="submit" name="btnUpdate" class="btn btn-primary">Cập nhật</button>
        <button type="button" onclick="window.location.href='khachhang.php';" class="btn btn-secondary">Quay lại</button>
    </form>
</div>

</body>
</html>
