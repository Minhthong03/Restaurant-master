<?php
// Bật báo lỗi để debug (bỏ khi chạy thực tế)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json; charset=utf-8');

// Lấy câu hỏi từ frontend gửi lên
$user_question = $_POST['question'] ?? '';

if (!$user_question) {
    echo json_encode(['reply' => 'Bạn vui lòng nhập câu hỏi nhé!']);
    exit;
}

// API Key OpenAI của bạn (giữ bí mật)

// Kết nối database
include_once("model/ketnoi.php");
$ketnoi = new clsketnoi();
$con = $ketnoi->moketnoi();

// Lấy danh sách món ăn với giá từ database
$sql = "SELECT dish_name, price FROM dishes WHERE status = 'active'";
$result = mysqli_query($con, $sql);

$menu_text = "Danh sách món ăn hiện tại:\n";
while ($row = mysqli_fetch_assoc($result)) {
    $menu_text .= "- " . $row['dish_name'] . ": " . number_format($row['price']) . " đồng\n";
}

// Tạo system prompt với thông tin nhà hàng và menu động
$system_content = <<<EOD
Bạn là trợ lý ảo của nhà hàng "restaurant".
Thông tin nhà hàng:
- Mở cửa từ 6:00 AM đến 10:00 PM.
- Địa chỉ: 14 Nguyễn Văn Bảo, phường 4, Gò Vấp, Hồ Chí Minh.
- Hotline: 0921115678
- Email: restaurant@gmail.com


Thông tin menu hiện tại:
$menu_text

Nếu khách hỏi về món ăn mà nhà hàng không có hoặc câu hỏi không hiểu, hãy trả lời:
"Tôi là trợ lý ảo cho nhà hàng, vui lòng đợi nhân viên nhà hàng quay lại trả lời sớm nhất hoặc liên hệ đến hotline: 0921115678. Xin cảm ơn!"
EOD;

// Tạo mảng messages gửi cho OpenAI API
$postData = [
    'model' => 'gpt-4o-mini',  // Hoặc model bạn muốn dùng
    'messages' => [
        ['role' => 'system', 'content' => $system_content],
        ['role' => 'user', 'content' => $user_question]
    ]
];

// Gọi API OpenAI
$ch = curl_init('https://api.openai.com/v1/chat/completions');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Authorization: Bearer $apiKey",
    "Content-Type: application/json"
]);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData));

$response = curl_exec($ch);
curl_close($ch);

// Giải mã JSON phản hồi
$data = json_decode($response, true);

$reply = $data['choices'][0]['message']['content'] ?? '';

// Kiểm tra nếu câu trả lời trống hoặc không phù hợp, trả lời dự phòng
if (empty($reply) || 
    stripos($reply, 'xin lỗi') !== false || 
    stripos($reply, 'không hiểu') !== false) {
    $reply = "Tôi là trợ lý ảo cho nhà hàng, vui lòng đợi nhân viên nhà hàng quay lại trả lời sớm nhất hoặc liên hệ đến hotline: 0921115678. Xin cảm ơn!";
}

// Trả về JSON cho frontend
echo json_encode(['reply' => trim($reply)]);
?>
