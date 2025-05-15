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

<?php
echo "<h2>Chi tiết sản phẩm</h2>"; 
include_once("Controller/controlDishes.php");
$p = new controlDishes();

// Lấy mã món ăn từ tham số 'idct' trong URL
$maSP = $_REQUEST["id"]; 

// Gọi phương thức để lấy chi tiết món ăn
$sp = $p->getOneDish($maSP);

// Kiểm tra nếu có kết quả sản phẩm
if ($sp) {
    while ($r = mysqli_fetch_assoc($sp)) {
        // Lấy thông tin từ cơ sở dữ liệu
        $tensp = $r["dish_name"];
        $gia = $r["price"];
        $motasp = $r["description"];
        $hinh = $r["image"];
        $thuonghieu = $r["category_name"];  // Hoặc nếu bạn muốn lấy tên danh mục, bạn có thể điều chỉnh lại
    }
} else {
    // Nếu không có sản phẩm, hiển thị thông báo và chuyển hướng về trang quản trị
    echo "<script>alert('Mã sản phẩm không tồn tại!');</script>";
    header("refresh:0;url='admin.php'");
    exit;
}
?>

<!-- Form hiển thị chi tiết sản phẩm -->
<!-- Form hiển thị chi tiết sản phẩm -->
<form method="POST" action="#" enctype="multipart/form-data" style="display: flex; align-items: center; justify-content: space-between; padding: 20px; background-color: #f8f9fa;">
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

        <!-- Chọn số lượng -->
        <div style="margin-top: 15px;">
            <label for="quantity" style="font-weight: bold;">Số lượng:</label>
            <input type="number" id="quantity" name="quantity" value="1" min="1" style="padding: 8px; width: 60px; border-radius: 5px; border: 1px solid #ccc; font-size: 16px;">
        </div>

        <!-- Nút thêm vào giỏ hàng -->
        <div style="text-align: center; margin-top: 15px;">
            <input type="submit" name="addToCart" value="Thêm vào giỏ hàng" style="padding: 15px 30px; background-color: #007bff; color: white; border: none; border-radius: 5px; cursor: pointer; font-size: 16px;">
        </div>

        <!-- Nút đặt hàng -->
        <div style="text-align: center; margin-top: 15px;">
            <input type="submit" name="btndh" value="Đặt hàng" style="padding: 15px 30px; background-color: #28a745; color: white; border: none; border-radius: 5px; cursor: pointer; font-size: 16px;">
        </div>
    </div>
</form>
