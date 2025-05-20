<?php
include_once("Controller/controlOrder.php");
include_once("Controller/controlOrderDetail.php");

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die("Phương thức không hợp lệ");
}

// Lấy dữ liệu từ form
$customer_id = isset($_POST['customer_id']) && intval($_POST['customer_id']) > 0 ? intval($_POST['customer_id']) : null;
$table_id = intval($_POST['table_id'] ?? 0);
$customer_email = trim($_POST['customer_email'] ?? '');
if ($customer_email === '') {
    $customer_email = null; // hoặc để chuỗi rỗng tùy nhu cầu
}

$order_description = trim($_POST['order_description'] ?? '');
if ($order_description === '') {
    $order_description = null; // hoặc để chuỗi rỗng tùy nhu cầu
}

$dishes = $_POST['dishes'] ?? [];
$quantities = $_POST['quantities'] ?? [];
$prices = $_POST['prices'] ?? [];
$notes = $_POST['notes'] ?? [];

$save_action = $_POST['save_action'] ?? 'save';

if ($table_id <= 0 || empty($dishes)) {
    die("Dữ liệu không hợp lệ");
}

$controlOrder = new controlOrder();
$controlOrderDetail = new controlOrderDetail();

// Tính tổng tiền
$total_amount = 0;
for ($i = 0; $i < count($dishes); $i++) {
    $total_amount += floatval($prices[$i]) * intval($quantities[$i]);
}

// Xác định trạng thái đơn hàng theo nút bấm
$status = 'Chờ xác nhận';
if ($save_action === 'process') {
    $status = 'Đang xử lý';
}

// Thêm đơn hàng với mô tả và trạng thái
$order_id = $controlOrder->insertOrder(
    $customer_id, 
    $table_id, 
    $total_amount, 
    $status, 
    $order_description
);

if (!$order_id) {
    die("Lỗi thêm đơn hàng");
}

// Thêm chi tiết đơn hàng
for ($i = 0; $i < count($dishes); $i++) {
    $controlOrderDetail->addOrderDetail(
        $order_id,
        intval($dishes[$i]),
        intval($quantities[$i]),
        floatval($prices[$i]),
        trim($notes[$i] ?? '')
    );
}

// Thông báo trạng thái lưu đơn hàng và chuyển hướng
if ($save_action === 'process') {
    echo "<script>alert('Chờ thanh toán!'); window.location.href='nhanvientieptan.php?action=AOrderNV';</script>";
} else {
    echo "<script>alert('Đơn hàng thành công!'); window.location.href='nhanvientieptan.php?action=AOrderNV';</script>";
}
exit();
