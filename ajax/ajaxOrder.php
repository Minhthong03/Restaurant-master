<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['UserID'])) {
    echo json_encode(['success' => false, 'message' => 'Chưa đăng nhập']);
    exit;
}

$customer_id = $_SESSION['UserID'];

include_once("../Model/ketNoi.php");

$p = new clsKetNoi();
$con = $p->MoKetNoi();

$action = $_POST['action'] ?? 'get_orders';

if ($action === 'get_orders') {
    $status = $_POST['status'] ?? 'Tất cả';

    if ($status !== 'Tất cả') {
        $sql = "SELECT * FROM orders WHERE customer_id = ? AND status = ? ORDER BY order_date DESC";
        $stmt = $con->prepare($sql);
        $stmt->bind_param("is", $customer_id, $status);
    } else {
        $sql = "SELECT * FROM orders WHERE customer_id = ? ORDER BY order_date DESC";
        $stmt = $con->prepare($sql);
        $stmt->bind_param("i", $customer_id);
    }

    if (!$stmt->execute()) {
        $p->DongKetNoi($con);
        echo json_encode(['success' => false, 'message' => 'Lỗi truy vấn lấy đơn hàng']);
        exit;
    }

    $result = $stmt->get_result();

    $orders = [];
    while ($row = $result->fetch_assoc()) {
        $orders[] = $row;
    }

    $p->DongKetNoi($con);

    echo json_encode(['success' => true, 'orders' => $orders]);
    exit;
}

elseif ($action === 'cancel_order') {
    $order_id = isset($_POST['order_id']) ? intval($_POST['order_id']) : 0;

    if ($order_id <= 0) {
        $p->DongKetNoi($con);
        echo json_encode(['success' => false, 'message' => 'Thiếu ID đơn hàng hoặc không hợp lệ']);
        exit;
    }

    $sqlCheck = "SELECT status FROM orders WHERE id = ? AND customer_id = ?";
    $stmtCheck = $con->prepare($sqlCheck);
    $stmtCheck->bind_param("ii", $order_id, $customer_id);
    $stmtCheck->execute();
    $resultCheck = $stmtCheck->get_result();

    if ($resultCheck->num_rows === 0) {
        $p->DongKetNoi($con);
        echo json_encode(['success' => false, 'message' => 'Đơn hàng không tồn tại hoặc không được phép hủy']);
        exit;
    }

    $row = $resultCheck->fetch_assoc();
    if ($row['status'] !== 'Chờ xác nhận') {
        $p->DongKetNoi($con);
        echo json_encode(['success' => false, 'message' => 'Đơn hàng không ở trạng thái Chờ xác nhận']);
        exit;
    }

    $sqlUpdate = "UPDATE orders SET status = 'Đã hủy' WHERE id = ? AND customer_id = ?";
    $stmtUpdate = $con->prepare($sqlUpdate);
    $stmtUpdate->bind_param("ii", $order_id, $customer_id);

    if ($stmtUpdate->execute()) {
        $p->DongKetNoi($con);
        echo json_encode(['success' => true]);
    } else {
        $p->DongKetNoi($con);
        echo json_encode(['success' => false, 'message' => 'Lỗi cập nhật trạng thái đơn hàng']);
    }
    exit;
}

elseif ($action === 'get_order_details') {
    $order_id = isset($_POST['order_id']) ? intval($_POST['order_id']) : 0;

    if ($order_id <= 0) {
        $p->DongKetNoi($con);
        echo json_encode(['success' => false, 'message' => 'Thiếu ID đơn hàng hoặc không hợp lệ']);
        exit;
    }

    // Lấy thông tin đơn hàng (để lấy ghi chú)
    $sqlOrder = "SELECT * FROM orders WHERE id = ? AND customer_id = ?";
    $stmtOrder = $con->prepare($sqlOrder);
    $stmtOrder->bind_param("ii", $order_id, $customer_id);
    $stmtOrder->execute();
    $resultOrder = $stmtOrder->get_result();

    if ($resultOrder->num_rows === 0) {
        $p->DongKetNoi($con);
        echo json_encode(['success' => false, 'message' => 'Đơn hàng không tồn tại']);
        exit;
    }
    $order = $resultOrder->fetch_assoc();

    // Lấy chi tiết món theo order_id, join với dishes để lấy tên món
    $sqlDetails = "SELECT od.*, d.dish_name 
                   FROM orderdetails od 
                   LEFT JOIN dishes d ON od.dish_id = d.id
                   WHERE od.order_id = ?";
    $stmtDetails = $con->prepare($sqlDetails);
    $stmtDetails->bind_param("i", $order_id);
    $stmtDetails->execute();
    $resultDetails = $stmtDetails->get_result();

    $details = [];
    while ($row = $resultDetails->fetch_assoc()) {
        $details[] = $row;
    }

    $p->DongKetNoi($con);

    echo json_encode(['success' => true, 'order' => $order, 'details' => $details]);
    exit;
}

else {
    $p->DongKetNoi($con);
    echo json_encode(['success' => false, 'message' => 'Action không hợp lệ']);
    exit;
}
