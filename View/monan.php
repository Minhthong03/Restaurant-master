<?php
session_start();

include_once("Controller/controlCart.php");

// Xử lý khi người dùng nhấn nút "Thêm vào giỏ hàng"
if (isset($_POST['addToCart'])) {
    if (!isset($_SESSION['UserID'])) {
        $currentUrl = $_SERVER['REQUEST_URI']; // URL hiện tại (vd: /Restaurant-master/khachhang.php?action=xemctsp&id=2)
        $_SESSION['redirect_after_login'] = $currentUrl;

        header('Location: index.php?dangnhap');
        exit;
            }

    $customer_id = $_SESSION['UserID'];
    $quantity = intval($_POST['quantity']);
    if ($quantity < 1) $quantity = 1;

    $dish_id = intval($_REQUEST['id']); // Lấy id món từ tham số URL

    $controlCart = new controlCart();

    // Lấy id giỏ hàng của khách
    $cart_id = $controlCart->getCartIdByCustomer($customer_id);

    // Nếu chưa có giỏ hàng, tạo giỏ hàng mới (bạn cần viết hàm createCartForCustomer nếu chưa có)
    if ($cart_id == 0) {
        // Giả sử có hàm này, nếu chưa có bạn cần tạo trong modelCart và controlCart
        if (method_exists($controlCart, 'createCartForCustomer')) {
            $cart_id = $controlCart->createCartForCustomer($customer_id);
        } else {
            echo "<script>alert('Bạn chưa có giỏ hàng, vui lòng thử lại sau.'); window.location.href='khachhang.php';</script>";
            exit;
        }
    }

    // Kiểm tra món trong giỏ hàng
    $items = $controlCart->getCartItems($cart_id);
    $found = false;
    while ($item = mysqli_fetch_assoc($items)) {
        if ($item['dish_id'] == $dish_id) {
            $found = true;
            $new_quantity = $item['quantity'] + $quantity;
            $controlCart->updateCartItemQuantity($cart_id, $dish_id, $new_quantity);
            break;
        }
    }

    if (!$found) {
        // Thêm món mới vào giỏ hàng
        if (method_exists($controlCart, 'addCartItem')) {
            $controlCart->addCartItem($cart_id, $dish_id, $quantity);
        } else {
            echo "<script>alert('Chức năng thêm món vào giỏ hàng chưa được hỗ trợ.'); window.location.href='khachhang.php';</script>";
            exit;
        }
    }

    echo "<script>window.location.href='khachhang.php';</script>";
    exit;
}

// --- Hiển thị chi tiết món ăn ---
echo "<h2>Chi tiết sản phẩm</h2>"; 
include_once("Controller/controlDishes.php");
$p = new controlDishes();

// Lấy mã món ăn từ tham số 'id' trong URL
$maSP = $_REQUEST["id"] ?? 0;

$sp = $p->getDishById($maSP);

if ($sp) {
    $r = mysqli_fetch_assoc($sp);

    $tensp = $r["dish_name"];
    $gia = $r["price"];
    $motasp = $r["description"];
    $hinh = $r["image"];
    $thuonghieu = $r["category_name"];
    $status = $r["status"] == 'active' ? 'Active' : 'Inactive';
} else {
    echo "<script>alert('Mã sản phẩm không tồn tại!');</script>";
    header("refresh:0;url='index.php'");
    exit;
}
?>

<head>
    <style>
       /* Cải thiện giao diện nút */
input[type="submit"] {
    font-size: 16px;
    padding: 12px 25px;
    border-radius: 5px;
    cursor: pointer;
    border: none;
    transition: background-color 0.3s ease;
}

input[type="submit"]:hover {
    opacity: 0.9;
}

/* Cải thiện kiểu dáng input chọn số lượng */
input[type="number"] {
    font-size: 16px;
    padding: 8px;
    width: 60px;
    border-radius: 5px;
    border: 1px solid #ccc;
    margin-top: 10px;
    color: #333;  /* Màu chữ trong trường nhập liệu */
    background-color: #f8f9fa;  /* Màu nền của input */
}

/* Màu chữ của label */
label {
    color: #555;  /* Màu chữ cho nhãn (label) */
    font-weight: bold;  /* Làm đậm chữ */
    font-size: 16px;  /* Kích thước chữ */
    margin-bottom: 8px;  /* Khoảng cách giữa nhãn và trường nhập liệu */
}
    </style>
</head>

<form method="POST" action="" enctype="multipart/form-data" style="display: flex; align-items: center; justify-content: space-between; padding: 20px; background-color: #f8f9fa;">
    <!-- Cột bên trái: Hình ảnh sản phẩm -->
    <div style="flex: 1; text-align: center;">
        <?php echo "<img src='images/Dishes/".$hinh."' alt='".$tensp."' style='width: 300px; border-radius: 15px;' />"; ?>
    </div>

    <!-- Cột bên phải: Thông tin sản phẩm -->
    <div style="flex: 1; padding: 20px; background-color: #ffffff; border-radius: 15px; box-shadow: 0 4px 8px rgba(0,0,0,0.1);">
        <h2 style="color: #333;"><?php echo $tensp; ?></h2>
        <p style="font-size: 1.2em; color: #28a745;">Giá: <?php echo number_format($gia, 0, ',', '.'); ?> VND</p>
        <p><strong>Mô tả sản phẩm:</strong></p>
        <p style="color: #555;"><?php echo nl2br($motasp); ?></p>
        <p><strong>Thương hiệu:</strong> <?php echo $thuonghieu; ?></p>
        <p><strong>Trạng thái:</strong> <?php echo $status; ?></p>
        <!-- Chọn số lượng -->
        <div style="margin-top: 15px;">
            <label for="quantity" style="font-weight: bold;">Số lượng:</label>
            <input type="number" id="quantity" name="quantity" value="1" min="1" style="padding: 8px; width: 60px; border-radius: 5px; border: 1px solid #ccc; font-size: 16px;">
        </div>

        <!-- Nút thêm vào giỏ hàng -->
        <div style="text-align: center; margin-top: 15px;">
            <input type="submit" name="addToCart" value="Thêm vào giỏ hàng" style="padding: 15px 30px; background-color: #007bff; color: white; border: none; border-radius: 5px; cursor: pointer; font-size: 16px;">
        </div>
    </div>
</form>
