<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);;
header('Content-Type: application/json; charset=utf-8');
include_once("model/ketnoi.php");

$ketnoi = new clsketnoi();
$con = $ketnoi->moKetNoi();

$user_question = $_POST['question'] ?? '';

if (!$user_question) {
    echo json_encode(['reply' => 'Bạn vui lòng nhập câu hỏi nhé!']);
    exit;
}

$keywords = explode(' ', $user_question);
$keywords = array_filter($keywords, fn($k) => strlen($k) > 1);
$keywords_sql = array_map(fn($k) => $con->real_escape_string($k), $keywords);
$like_clauses = array_map(fn($k) => "keywords LIKE '%$k%'", $keywords_sql);
$where_clause = implode(' OR ', $like_clauses);

$sql_faq = "SELECT answer FROM cauhoi_chatbox WHERE $where_clause LIMIT 1";
$result = mysqli_query($con, $sql_faq);

error_log($sql_faq);


if ($result && mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    echo json_encode(['reply' => $row['answer']]);
    exit;
}

$sql_dishes = "SELECT name, category FROM monan_chatbox WHERE available_today = 1";
$result = mysqli_query($con, $sql_dishes);

$dish_list = [];
while ($row = mysqli_fetch_assoc($result)) {
    $dish_list[$row['category']][] = $row['name'];
}

if (!empty($dish_list)) {
    $reply_parts = [];
    foreach ($dish_list as $category => $names) {
        $reply_parts[] = "$category: " . implode(', ', $names);
    }
    $reply = "Hôm nay chúng tôi có: " . implode('. ', $reply_parts) . ".";
} else {
    $reply = "Xin lỗi, hiện chưa có món nào phục vụ hôm nay.";
}

echo json_encode(['reply' => $reply]);
?>