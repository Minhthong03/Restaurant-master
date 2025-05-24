<?php
include_once("ketNoi.php");

class modelCart {
    public function getCartByCustomer($customer_id) {
        $p = new clsKetNoi();
        $con = $p->MoKetNoi();
        $sql = "SELECT * FROM cart WHERE customer_id = $customer_id LIMIT 1";
        $result = mysqli_query($con, $sql);
        $p->DongKetNoi($con);
        return $result;
    }
    public function addCartItem($cart_id, $dish_id, $quantity) {
    $p = new clsKetNoi();
    $con = $p->MoKetNoi();
    $sql = "INSERT INTO cart_items (cart_id, dish_id, quantity) VALUES (?, ?, ?)";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("iii", $cart_id, $dish_id, $quantity);
    $result = $stmt->execute();
    $stmt->close();
    $p->DongKetNoi($con);
    return $result;
}

    public function getCartIdByCustomer($customer_id) {
        $p = new clsKetNoi();
        $con = $p->MoKetNoi();
        $sql = "SELECT id FROM cart WHERE customer_id = $customer_id LIMIT 1";
        $result = mysqli_query($con, $sql);
        $cart_id = 0;
        if ($row = mysqli_fetch_assoc($result)) {
            $cart_id = $row['id'];
        }
        $p->DongKetNoi($con);
        return $cart_id;
    }

    public function getCartItems($cart_id) {
        $p = new clsKetNoi();
        $con = $p->MoKetNoi();
        $sql = "SELECT ci.*, d.dish_name, d.price FROM cart_items ci
                JOIN dishes d ON ci.dish_id = d.id
                WHERE ci.cart_id = $cart_id";
        $result = mysqli_query($con, $sql);
        $p->DongKetNoi($con);
        return $result;
    }

    public function updateCartItemQuantity($cart_id, $dish_id, $quantity) {
        $p = new clsKetNoi();
        $con = $p->MoKetNoi();
        $sql = "UPDATE cart_items SET quantity = $quantity WHERE cart_id = $cart_id AND dish_id = $dish_id";
        $result = mysqli_query($con, $sql);
        $p->DongKetNoi($con);
        return $result;
    }

    public function updateCartItemNote($cart_id, $dish_id, $note) {
        $p = new clsKetNoi();
        $con = $p->MoKetNoi();
        $note = mysqli_real_escape_string($con, $note);
        $sql = "UPDATE cart_items SET note = '$note' WHERE cart_id = $cart_id AND dish_id = $dish_id";
        $result = mysqli_query($con, $sql);
        $p->DongKetNoi($con);
        return $result;
    }

    public function deleteCartItem($cart_id, $dish_id) {
        $p = new clsKetNoi();
        $con = $p->MoKetNoi();
        $sql = "DELETE FROM cart_items WHERE cart_id = $cart_id AND dish_id = $dish_id";
        $result = mysqli_query($con, $sql);
        $p->DongKetNoi($con);
        return $result;
    }

    // Nếu bạn muốn cập nhật mô tả đơn hàng trong bảng orders (cần có bảng orders)
    public function updateOrderDescription($order_id, $description) {
        $p = new clsKetNoi();
        $con = $p->MoKetNoi();
        $description = mysqli_real_escape_string($con, $description);
        $sql = "UPDATE orders SET description = '$description' WHERE id = $order_id";
        $result = mysqli_query($con, $sql);
        $p->DongKetNoi($con);
        return $result;
    }
    // Hàm tạo giỏ hàng mới cho user khi đăng ký
    public function createCartForUser($customer_id) {
        $p = new clsKetNoi();
        $con = $p->MoKetNoi();
        // Kiểm tra đã có giỏ chưa
        $checkSql = "SELECT id FROM cart WHERE customer_id = $customer_id LIMIT 1";
        $checkRes = mysqli_query($con, $checkSql);
        if (mysqli_num_rows($checkRes) > 0) {
            $p->DongKetNoi($con);
            return false; // Giỏ hàng đã tồn tại, không tạo mới
        }

        // Tạo giỏ hàng mới
        $sql = "INSERT INTO cart (customer_id) VALUES ($customer_id)";
        $result = mysqli_query($con, $sql);
        $p->DongKetNoi($con);
        return $result;
    }
}
?>
