<?php
include_once("ketnoi.php");

class modelInventoryTransactions {
    public function selectAllTransactions() {
        $p = new clsKetnoi();
        $con = $p->MoKetNoi();

        $query = "
            SELECT it.*, u.username, ing.name AS ingredient_name
            FROM inventorytransactions it
            LEFT JOIN users u ON it.user_id = u.id
            LEFT JOIN ingredients ing ON it.ingredient_id = ing.id
            ORDER BY it.transaction_date DESC
        ";

        $result = mysqli_query($con, $query);
        $p->DongKetNoi($con);
        return $result;
    }

    public function selectTransactionById($id) {
        $p = new clsKetnoi();
        $con = $p->MoKetNoi();
        $id = intval($id);

        $query = "
            SELECT it.*, u.username, ing.name AS ingredient_name
            FROM inventorytransactions it
            LEFT JOIN users u ON it.user_id = u.id
            LEFT JOIN ingredients ing ON it.ingredient_id = ing.id
            WHERE it.id = $id
        ";

        $result = mysqli_query($con, $query);
        $p->DongKetNoi($con);
        return $result;
    }

    public function selectTransactionsByType($type) {
        $p = new clsKetnoi();
        $con = $p->MoKetNoi();

        $type = intval($type);

        $query = "
            SELECT it.*, u.username, ing.name AS ingredient_name
            FROM inventorytransactions it
            LEFT JOIN users u ON it.user_id = u.id
            LEFT JOIN ingredients ing ON it.ingredient_id = ing.id
            WHERE it.transaction_type = $type
            ORDER BY it.transaction_date DESC
        ";

        $result = mysqli_query($con, $query);
        $p->DongKetNoi($con);
        return $result;
    }

    public function insertTransaction($ingredient_id, $transaction_type, $quantity, $transaction_date, $total, $user_id) {
        $p = new clsKetNoi();
        $con = $p->MoKetNoi();

        $ingredient_id = intval($ingredient_id);
        $transaction_type = intval($transaction_type);
        $quantity = intval($quantity);
        $transaction_date = mysqli_real_escape_string($con, $transaction_date);
        $total = floatval($total);
        $user_id = intval($user_id);

        $query = "
            INSERT INTO inventorytransactions
            (ingredient_id, transaction_type, quantity, transaction_date, total, user_id)
            VALUES
            ($ingredient_id, $transaction_type, $quantity, '$transaction_date', $total, $user_id)
        ";

        $result = mysqli_query($con, $query);
        $p->DongKetNoi($con);
        return $result;
    }

    public function updateTransaction($id, $ingredient_id, $transaction_type, $quantity, $transaction_date, $total, $user_id) {
        $p = new clsKetNoi();
        $con = $p->MoKetNoi();

        $id = intval($id);
        $ingredient_id = intval($ingredient_id);
        $transaction_type = intval($transaction_type);
        $quantity = intval($quantity);
        $transaction_date = mysqli_real_escape_string($con, $transaction_date);
        $total = floatval($total);
        $user_id = intval($user_id);

        $query = "
            UPDATE inventorytransactions SET
            ingredient_id = $ingredient_id,
            transaction_type = $transaction_type,
            quantity = $quantity,
            transaction_date = '$transaction_date',
            total = $total,
            user_id = $user_id
            WHERE id = $id
        ";

        $result = mysqli_query($con, $query);
        $p->DongKetNoi($con);
        return $result;
    }
}
?>
