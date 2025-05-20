<?php
include_once("../Model/ketNoi.php");

if (isset($_GET['email'])) {
    $email = $_GET['email'];
    $p = new clsKetNoi();
    $con = $p->MoKetNoi();

    $sql = "SELECT id, username, phone, address 
            FROM users 
            WHERE email = ? AND role_id = 4";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($user = $result->fetch_assoc()) {
        echo json_encode([
            'success' => true,
            'data' => $user
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Không tìm thấy khách hàng phù hợp.'
        ]);
    }

    $stmt->close();
    $p->DongKetNoi($con);
}
?>
