<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include_once("Model/mNguoiDung.php");
include_once("controlCart.php");
class cNguoiDung {
    public function get01NguoiDung($email, $password) {
    $password = md5($password);
    $p = new mNguoiDung();
    $ketqua = $p->select01NguoiDung($email, $password);

    if ($ketqua && mysqli_num_rows($ketqua) > 0) {
        $r = mysqli_fetch_assoc($ketqua);

        $_SESSION["UserID"] = $r["id"];
        $_SESSION["Email"] = $r["email"];
        $_SESSION["RoleID"] = $r["role_id"];

        // Nếu là khách hàng (role_id = 4), kiểm tra và tạo cart nếu chưa có
        if ($r["role_id"] == 4) {
            include_once('controlCart.php'); // Đảm bảo đường dẫn đúng
            $controlCart = new controlCart();

            $existingCartId = $controlCart->getCartIdByCustomer($r["id"]);
            if (!$existingCartId) {
                // Tạo giỏ hàng rỗng cho khách hàng mới
                $controlCart->createCartForUser($r["id"]);
            }
        }

        // Kiểm tra có URL cần redirect không
        $redirectUrl = $_SESSION['redirect_after_login'] ?? null;
        if ($redirectUrl) {
            unset($_SESSION['redirect_after_login']);

            // Thay 'index.php' bằng 'khachhang.php' trong URL
            $redirectUrl = str_replace('index.php', 'khachhang.php', $redirectUrl);

            header("Location: $redirectUrl");
            exit();
        }

        // Nếu không có URL redirect, chuyển trang mặc định theo role
        switch ($r["role_id"]) {
            case 1: header("Location: admin.php"); break;
            case 2: header("Location: nhanvienkho.php"); break;
            case 3: header("Location: nhanvientieptan.php"); break;
            case 4: header("Location: khachhang.php"); break;
            default:
                header("Location: index.php?dangnhap&error=invalid_role");
        }
        exit();
    } else {
        header("Location: index.php?dangnhap&error=invalid_credentials");
        exit();
    }
}


    public function getOneNguoiDung($maND){
            $p= new mNguoiDung();
            $kq= $p->selectOneNguoiDung($maND);
            if(mysqli_num_rows($kq)>0){
                return $kq;
            }else{
                return false;
            }
        }
    public function getAllNguoiDungNV() {
        $p = new mNguoiDung();
        $kq = $p->selectAllNguoiDungNV();
        if (mysqli_num_rows($kq) > 0) {
            return $kq;
        } else {
            return false;
        }
    }
    public function getAllNguoiDung() {
        $p = new mNguoiDung();
        $kq = $p->selectAllNguoiDung();
        if (mysqli_num_rows($kq) > 0) {
            return $kq;
        } else {
            return false;
        }
    }
    public function themNguoiDung($username, $email, $password, $phone,$address, $role_id, $status) {
        // Kiểm tra xem email có trùng không
        $existingEmail = $this->checkEmailExists($email);
        
        if ($existingEmail) {
            return false; // Email đã tồn tại, không thêm
        }

        $p = new mNguoiDung();
        return $p->insertNguoiDung($username, $email, $password, $phone,$address, $role_id, $status);
    }
    // Phương thức sửa người dùng
public function updateNguoiDung($id, $username, $email, $password, $phone, $address, $role_id, $status) {
    // Kiểm tra email trùng với người khác
    $existingEmail = $this->checkEmailExistsForUpdate($email, $id); 
    
    if ($existingEmail) {
        return false; // Email đã tồn tại, không sửa
    }

    $p = new mNguoiDung();
    return $p->udNguoiDung($id, $username, $email, $password, $phone, $address, $role_id, $status);
}

    // Kiểm tra email có trùng trong cơ sở dữ liệu
public function checkEmailExists($email) {
    $p = new mNguoiDung();
    $result = $p->getEmailByEmail($email);
    return mysqli_num_rows($result) > 0; // Nếu email đã tồn tại trong cơ sở dữ liệu
}

// Kiểm tra email trùng lặp khi sửa người dùng (ngoại trừ ID hiện tại)
public function checkEmailExistsForUpdate($email, $id) {
    $p = new mNguoiDung();
    $result = $p->getEmailByEmailExceptId($email, $id);
    return mysqli_num_rows($result) > 0; // Nếu email đã tồn tại (ngoại trừ ID hiện tại)
}
    public function getAllRoles() {
        $p = new mNguoiDung();
        return $p->getAllRoles();  // Gọi phương thức model để lấy các vai trò
    }
    public function getAllRolesNV() {
        $p = new mNguoiDung();
        return $p->getAllRolesNV();  // Gọi phương thức model để lấy các vai trò
    }
    public function getUsersById($searchId) {
        $p = new mNguoiDung();
        $kq = $p->getUserById($searchId);
        return $kq;       
    }
    public function getUsersByIdNV($searchId) {
        $p = new mNguoiDung();
        $kq = $p->getUserByIdNV($searchId);
        return $kq;     
    }
}
?>
