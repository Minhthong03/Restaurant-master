<?php
include_once("Model/modelInventoryTransactions.php");
include_once("Model/modelIngredients.php");

class controlInventoryTransactions {
    public function getAllTransactions() {
        $p = new modelInventoryTransactions();
        $result = $p->selectAllTransactions();
        if (mysqli_num_rows($result) > 0) {
            return $result;
        } else {
            return false;
        }
    }

    public function insertTransaction($ingredient_id, $transaction_type, $quantity, $transaction_date, $total, $user_id) {
    $p = new modelInventoryTransactions();

    // Thêm phiếu
    $resultInsert = $p->insertTransaction($ingredient_id, $transaction_type, $quantity, $transaction_date, $total, $user_id);

    if ($resultInsert) {
        // Lấy stock_quantity hiện tại
        $ingredientModel = new modelIngredients();
        $ingredientCurrent = $ingredientModel->selectIngredientById($ingredient_id);
        if ($ingredientCurrent && mysqli_num_rows($ingredientCurrent) > 0) {
            $row = mysqli_fetch_assoc($ingredientCurrent);
            $currentStock = intval($row['stock_quantity']);

            // Cập nhật tồn kho: nhập kho cộng, xuất kho trừ
            if ($transaction_type == 1) { // nhập kho
                $newStock = $currentStock + $quantity;
            } elseif ($transaction_type == 2) { // xuất kho
                $newStock = $currentStock - $quantity;
                if ($newStock < 0) $newStock = 0; // không để tồn kho âm
            } else {
                $newStock = $currentStock; // nếu type khác
            }

            // Cập nhật lại tồn kho
            $ingredientModel->updateStockQuantity($ingredient_id, $newStock);
        }
        return true;
    } else {
        return false;
    }
}


    public function updateTransaction($id, $ingredient_id, $transaction_type, $quantity, $transaction_date, $total, $user_id) {
        $p = new modelInventoryTransactions();
        return $p->updateTransaction($id, $ingredient_id, $transaction_type, $quantity, $transaction_date, $total, $user_id);
    }

    public function getTransactionById($id) {
        $p = new modelInventoryTransactions();
        return $p->selectTransactionById($id);
    }
    public function getTransactionsByType($type) {
        $p = new modelInventoryTransactions();
        return $p->selectTransactionsByType($type);
    }
}
?>
