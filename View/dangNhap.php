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

    input[type="text"],
    input[type="password"] {
        font-size: 16px;
        padding: 10px;
        width: 95%;
        border-radius: 5px;
        border: 1px solid #ccc;
        background-color: #f8f9fa;
        color: #333;
        margin-top: 5px;
        margin-bottom: 15px;
    }

    label {
        color: #555;
        font-weight: bold;
        font-size: 16px;
        display: block;
        margin-bottom: 5px;
    }

    .box-dangnhap {
        max-width: 400px;
        margin: 20px auto;
        padding: 25px;
        background-color: #ffffff;
        border-radius: 15px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
</style>

<?php
if (isset($_POST["btnDN"])) {
    include_once("Controller/cNguoiDung.php");
    $p = new cNguoiDung();
    $kq = $p->get01NguoiDung($_POST["txtDN"], $_POST["txtMK"]);

    if (!$kq) {
        echo "<p style='color:red; text-align:center;'>❌ Tài khoản hoặc mật khẩu không đúng</p>";
    }
}
?>

<div class="box-dangnhap">
    <form method="POST" action="#">
        <label for="txtDN">Email:</label>
        <input type="text" name="txtDN" id="txtDN" required>

        <label for="txtMK">Mật khẩu:</label>
        <input type="password" name="txtMK" id="txtMK" required>

        <div style="text-align:center;">
            <input type="submit" name="btnDN" value="Đăng nhập">
            <input type="reset" value="Nhập lại">
        </div>
    </form>
</div>
