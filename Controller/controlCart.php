<?php
include_once('Model/modelCart.php');


class controlCart {
    public function getCartByCustomer($customer_id) {
        $p = new modelCart();
        return $p->getCartByCustomer($customer_id);
    }
    public function addCartItem($cart_id, $dish_id, $quantity) {
    $p = new modelCart();
    return $p->addCartItem($cart_id, $dish_id, $quantity);
}
    // Hàm tạo giỏ hàng cho user mới
    public function createCartForUser($customer_id) {
        $p = new modelCart();
        return $p->createCartForUser($customer_id);
    }
    public function getCartIdByCustomer($customer_id) {
        $p = new modelCart();
        return $p->getCartIdByCustomer($customer_id);
    }

    public function getCartItems($cart_id) {
        $p = new modelCart();
        return $p->getCartItems($cart_id);
    }

    public function updateCartItemQuantity($cart_id, $dish_id, $quantity) {
        $p = new modelCart();
        return $p->updateCartItemQuantity($cart_id, $dish_id, $quantity);
    }

    public function updateCartItemNote($cart_id, $dish_id, $note) {
        $p = new modelCart();
        return $p->updateCartItemNote($cart_id, $dish_id, $note);
    }

    public function deleteCartItem($cart_id, $dish_id) {
        $p = new modelCart();
        return $p->deleteCartItem($cart_id, $dish_id);
    }

    public function updateOrderDescription($order_id, $description) {
        $p = new modelCart();
        return $p->updateOrderDescription($order_id, $description);
    }
}
?>
