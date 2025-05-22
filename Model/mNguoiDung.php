<?php
include_once("ketNoi.php");

class mNguoiDung {
    public function select01NguoiDung($email, $matkhau) {
        $p = new clsKetNoi();
        $con = $p->MoKetNoi();

        // Câu truy vấn: so sánh email và mật khẩu (đã mã hóa MD5 trước đó)
        $truyvan = "SELECT * FROM Users WHERE email = '$email' AND password = '$matkhau' LIMIT 1";
        
        $ketqua = mysqli_query($con, $truyvan);
        $p->DongKetNoi($con);
        return $ketqua;
    }

    public function selectOneNguoiDung($userID){
        $p = new clsKetNoi();
        $truyvan = "SELECT u.*, r.name AS role_name 
                    FROM Users u 
                    JOIN Roles r ON u.role_id = r.id 
                    WHERE u.id = $userID";
        $con = $p->MoKetNoi();
        $kq = mysqli_query($con, $truyvan);
        $p->DongKetNoi($con);
        return $kq;
    }

    public function selectAllNguoiDungNV() {
    $p = new clsKetnoi();
    $con = $p->MoKetNoi();

    $truyvan = "SELECT u.*, r.name AS role_name
                FROM Users u
                JOIN Roles r ON u.role_id = r.id
                WHERE u.role_id = 4";

    $kq = mysqli_query($con, $truyvan);
    if (!$kq) {
        $p->DongKetNoi($con);
        die("Lỗi truy vấn SQL ở selectAllNguoiDungNV: " . mysqli_error($con));
    }

    $p->DongKetNoi($con);
    return $kq;
}

    public function selectAllNguoiDung() {
        $p = new clsKetNoi();
        $truyvan = "SELECT u.*, r.name AS role_name
                    FROM Users u
                    JOIN Roles r ON u.role_id = r.id
                    WHERE u.role_id != 4";
        $con = $p->MoKetNoi();
        $kq = mysqli_query($con, $truyvan);
        $p->DongKetNoi($con);
        return $kq;
    }
    public function insertNguoiDung($username, $email, $password, $phone, $address, $role_id, $status) {
        $p = new clsKetNoi();
        $con = $p->MoKetNoi();
        $password = md5($password); // Hashing the password
        $truyvan = "INSERT INTO users (username, email, password, phone, address, role_id, status) 
                    VALUES ('$username', '$email', '$password', '$phone','$address', $role_id, '$status')";
        $kq = mysqli_query($con, $truyvan);
        $p->DongKetNoi($con);
        return $kq;
    }
    public function udNguoiDung($id, $username, $email, $password, $phone,$address, $role_id, $status) {
        $p = new clsKetNoi();
        $con = $p->MoKetNoi();
        $password = md5($password); // Hashing the password
        $sql = "UPDATE users SET 
                    username = '$username', 
                    email = '$email', 
                    password = '$password', 
                    phone = '$phone', 
                    address='$address'
                    role_id = $role_id, 
                    status = '$status'
                WHERE id = $id";
        $kq = mysqli_query($con, $sql);
        $p->DongKetNoi($con);
        return $kq;
    }
    // Kiểm tra email có tồn tại trong cơ sở dữ liệu
public function getEmailByEmail($email) {
    $p = new clsKetNoi();
    $con = $p->MoKetNoi();
    $truyvan = "SELECT * FROM users WHERE email = '$email'";
    $kq = mysqli_query($con, $truyvan);
    $p->DongKetNoi($con);
    return $kq;
}

// Kiểm tra email trùng với người khác (ngoại trừ ID hiện tại)
public function getEmailByEmailExceptId($email, $id) {
    $p = new clsKetNoi();
    $con = $p->MoKetNoi();
    $truyvan = "SELECT * FROM users WHERE email = '$email' AND id != $id";
    $kq = mysqli_query($con, $truyvan);
    $p->DongKetNoi($con);
    return $kq;
}
    public function getAllRoles() {
        $p = new clsKetNoi();
        $con = $p->MoKetNoi();

        $query = "SELECT * FROM roles
        WHERE id != 4"; 
        $result = mysqli_query($con, $query);
        $p->DongKetNoi($con);

        return $result;
    }
    public function getAllRolesNV() {
        $p = new clsKetNoi();
        $con = $p->MoKetNoi();

        $query = "SELECT * FROM roles
        WHERE id = 4"; 
        $result = mysqli_query($con, $query);
        $p->DongKetNoi($con);

        return $result;
    }
    public function getUserById($id) {
        $p = new clsKetNoi();
        $con = $p->MoKetNoi();
        $truyvan = "SELECT u.*, r.name AS role_name 
            FROM Users u 
            JOIN Roles r ON u.role_id = r.id 
            WHERE u.id = '$id' AND u.role_id != 4 
            LIMIT 1";
        $kq = mysqli_query($con, $truyvan);
        $p->DongKetNoi($con);
        return $kq;
    }
    public function getUserByIdNV($id) {
    $p = new clsKetNoi();
    $con = $p->MoKetNoi();

    if (!$con) {
        die("Kết nối thất bại: " . mysqli_connect_error());
    }

    $id = (int)$id;  // ép kiểu số an toàn

    $truyvan = "SELECT u.*, r.name AS role_name 
                FROM users u 
                JOIN roles r ON u.role_id = r.id 
                WHERE u.id = $id AND u.role_id = 4 
                LIMIT 1";

    $kq = mysqli_query($con, $truyvan);

    $p->DongKetNoi($con);
    return $kq;
}

}
?>
