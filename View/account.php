<style>
    input[type="submit"],
    input[type="reset"] {
        font-size: 16px;
        padding: 12px 25px;
        border-radius: 5px;
        cursor: pointer;
        border: none;
        transition: background-color 0.3s ease;
        color: white;
        margin-right: 10px;
    }

    input[type="submit"] {
        background-color: #007bff;
    }

    input[type="reset"] {
        background-color: #dc3545;
    }

    input[type="submit"]:hover,
    input[type="reset"]:hover {
        opacity: 0.9;
    }

    .box-dangky {
        max-width: 500px;
        margin: 20px auto;
        padding: 25px;
        background-color: #ffffff;
        border-radius: 15px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        font-family: Arial, sans-serif;
    }

    .form-group {
        display: flex;
        align-items: center;
        margin-bottom: 15px;
    }

    .form-group label {
        width: 130px; /* độ rộng cố định cho label */
        margin-right: 10px;
        font-weight: bold;
        color: #555;
        font-size: 16px;
    }

    .form-group input {
        flex: 1;
        font-size: 16px;
        padding: 10px;
        border-radius: 5px;
        border: 1px solid #ccc;
        background-color: #f8f9fa;
        color: #333;
    }
</style>

<?php
if (isset($_POST["btnDK"])) {
    include_once("Controller/cNguoiDung.php");
    $p = new cNguoiDung();
    $username = $_POST["txtUsername"];
    $email = $_POST["txtEmail"];
    $mknd = $_POST["txtPassword"];
    $phone = $_POST["txtPhone"];
    $address = $_POST["txtAddress"];
    $role_id = 4;
    $status = "active";

    // Kiểm tra email có tồn tại trong cơ sở dữ liệu
        if ($p->checkEmailExists($email)) {
            echo "<script>alert('Email này đã tồn tại. Vui lòng sử dụng email khác.');</script>";
        } else {
            // Thêm người dùng
            $kq = $p->themNguoiDung($tennd, $email, $mknd, $phone, $address,$role_id, $status);

            // Kiểm tra kết quả
            if ($kq) {
                echo "<script>alert('Thêm người dùng thành công!'); window.location.href = 'index.php';</script>";   
            } else {
                echo "<script>alert('Thêm người dùng thất bại!');</script>";
            }
        }
    
}
?>

<div class="box-dangky">
    <form method="POST" action="#">
        <div class="form-group">
            <label for="txtUsername">Tên đăng nhập:</label>
            <input type="text" name="txtUsername" id="txtUsername" required>
        </div>

        <div class="form-group">
            <label for="txtPassword">Mật khẩu:</label>
            <input type="password" name="txtPassword" id="txtPassword" required>
        </div>

        <div class="form-group">
            <label for="txtEmail">Email:</label>
            <input type="email" name="txtEmail" id="txtEmail" required>
        </div>

        <div class="form-group">
            <label for="txtPhone">Điện thoại:</label>
            <input type="tel" name="txtPhone" id="txtPhone" required pattern="[0-9]{9,15}">
        </div>

        <div class="form-group">
            <label for="txtAddress">Địa chỉ:</label>
            <input type="text" name="txtAddress" id="txtAddress" required>
        </div>

        <div style="text-align:center;">
            <input type="submit" name="btnDK" value="Đăng ký">
            <input type="reset" value="Nhập lại">
        </div>
    </form>
</div>
