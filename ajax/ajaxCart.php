<?php
session_start();
header('Content-Type: application/json');

$customer_id = $_SESSION['UserID'] ?? 0;
if ($customer_id === 0) {
    echo json_encode(['success' => false, 'message' => 'Chưa đăng nhập']);
    exit;
}

function connectDB() {
    $con = mysqli_connect("localhost", "cnmoi", "123456", "restaurantdb"); // sửa lại config DB nếu cần
    if (!$con) {
        die(json_encode(['success' => false, 'message' => 'Không thể kết nối database']));
    }
    mysqli_set_charset($con, "utf8");
    return $con;
}

function getCartIdByCustomer($con, $customer_id) {
    $sql = "SELECT id FROM cart WHERE customer_id = $customer_id LIMIT 1";
    $res = mysqli_query($con, $sql);
    $cart_id = 0;
    if ($row = mysqli_fetch_assoc($res)) {
        $cart_id = $row['id'];
    }
    return $cart_id;
}

function getCartItems($con, $cart_id) {
    $sql = "SELECT ci.*, d.dish_name, d.price 
            FROM cart_items ci
            JOIN dishes d ON ci.dish_id = d.id
            WHERE ci.cart_id = $cart_id";
    $res = mysqli_query($con, $sql);
    $items = [];
    while ($row = mysqli_fetch_assoc($res)) {
        $items[] = $row;
    }
    return $items;
}

function updateCartItemQuantity($con, $cart_id, $dish_id, $quantity) {
    $quantity = max(1, intval($quantity));
    $sql = "UPDATE cart_items SET quantity = $quantity WHERE cart_id = $cart_id AND dish_id = $dish_id";
    return mysqli_query($con, $sql);
}

function updateCartItemNote($con, $cart_id, $dish_id, $note) {
    $note_safe = mysqli_real_escape_string($con, $note);
    $sql = "UPDATE cart_items SET note = '$note_safe' WHERE cart_id = $cart_id AND dish_id = $dish_id";
    return mysqli_query($con, $sql);
}

function deleteCartItem($con, $cart_id, $dish_id) {
    $sql = "DELETE FROM cart_items WHERE cart_id = $cart_id AND dish_id = $dish_id";
    return mysqli_query($con, $sql);
}

function insertOrder($con, $customer_id, $table_id, $total_amount, $status, $description) {
    $customer_id_value = is_null($customer_id) ? "NULL" : intval($customer_id);
    $table_id_value = is_null($table_id) ? "NULL" : intval($table_id);
    $description_safe = mysqli_real_escape_string($con, $description);
    $sql = "INSERT INTO orders (customer_id, table_id, total_amount, status, description) VALUES ($customer_id_value, $table_id_value, $total_amount, '$status', '$description_safe')";
    if (mysqli_query($con, $sql)) {
        return mysqli_insert_id($con);
    } else {
        return false;
    }
}

function insertOrderDetail($con, $order_id, $dish_id, $quantity, $unit_price, $note) {
    $note_safe = mysqli_real_escape_string($con, $note);
    $sql = "INSERT INTO orderdetails (order_id, dish_id, quantity, unit_price, note) VALUES ($order_id, $dish_id, $quantity, $unit_price, '$note_safe')";
    return mysqli_query($con, $sql);
}

// --- XỬ LÝ ACTION ---

$action = $_POST['action'] ?? '';
$con = connectDB();

$cart_id = getCartIdByCustomer($con, $customer_id);

if ($action == 'update') {
    $dish_id = intval($_POST['dish_id']);
    $quantity = max(1, intval($_POST['quantity']));
    $success = updateCartItemQuantity($con, $cart_id, $dish_id, $quantity);
    echo json_encode(['success' => $success]);

} elseif ($action == 'delete') {
    $dish_id = intval($_POST['dish_id']);
    $success = deleteCartItem($con, $cart_id, $dish_id);
    echo json_encode(['success' => $success]);

} elseif ($action == 'update_note') {
    $dish_id = intval($_POST['dish_id']);
    $note = $_POST['note'] ?? '';
    $success = updateCartItemNote($con, $cart_id, $dish_id, $note);
    echo json_encode(['success' => $success]);

} elseif ($action == 'save_all_changes') {
    $cart_data_json = $_POST['cart_data'] ?? '[]';
    $deleted_dishes_json = $_POST['deleted_dishes'] ?? '[]';
    $description = $_POST['description'] ?? '';

    $cart_data = json_decode($cart_data_json, true);
    $deleted_dishes = json_decode($deleted_dishes_json, true);

    $success = true;
    $errors = [];

    foreach ($cart_data as $item) {
        $dish_id = intval($item['dish_id']);
        $quantity = max(1, intval($item['quantity']));
        $note = $item['note'] ?? '';

        if (!updateCartItemQuantity($con, $cart_id, $dish_id, $quantity) ||
            !updateCartItemNote($con, $cart_id, $dish_id, $note)) {
            $success = false;
            $errors[] = "Lỗi cập nhật món ID $dish_id";
        }
    }

    foreach ($deleted_dishes as $dish_id) {
        if (!deleteCartItem($con, $cart_id, intval($dish_id))) {
            $success = false;
            $errors[] = "Lỗi xóa món ID $dish_id";
        }
    }

    if ($success) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => implode(", ", $errors)]);
    }

} elseif ($action == 'place_order') {
    $description = $_POST['description'] ?? '';
    $orderDetails = $_POST['order_details'] ?? [];

    $items = getCartItems($con, $cart_id);
    $cartItems = [];
    $total_amount = 0;
    foreach ($items as $item) {
        $cartItems[$item['dish_id']] = $item;
        $total_amount += $item['price'] * $item['quantity'];
    }

    $order_id = insertOrder($con, $customer_id, null, $total_amount, 'Chờ xác nhận', $description);

    if ($order_id) {
        $success = true;

        foreach ($orderDetails as $detail) {
            $dish_id = intval($detail['dish_id']);
            $quantity = intval($detail['quantity']);
            $note = $detail['note'] ?? '';

            $unit_price = isset($cartItems[$dish_id]) ? $cartItems[$dish_id]['price'] : 0;

            if (!insertOrderDetail($con, $order_id, $dish_id, $quantity, $unit_price, $note)) {
                $success = false;
                break;
            }

            if (!deleteCartItem($con, $cart_id, $dish_id)) {
                $success = false;
                break;
            }
        }

        if ($success) {
            echo json_encode(['success' => true, 'order_id' => $order_id]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Lỗi lưu chi tiết đơn hàng hoặc xóa món']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Lỗi tạo đơn hàng']);
    }

} else {
    echo json_encode(['success' => false, 'message' => 'Hành động không hợp lệ']);
}

mysqli_close($con);
