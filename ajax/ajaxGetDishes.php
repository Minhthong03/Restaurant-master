<?php
include_once(__DIR__ . '/../Controller/controlDishes.php');


if (!isset($_GET['category_id'])) {
    echo "<p>Thiếu tham số category_id</p>";
    exit;
}

$categoryId = intval($_GET['category_id']);
if ($categoryId <= 0) {
    echo "<p>Tham số category_id không hợp lệ</p>";
    exit;
}

$ctrlDishes = new controlDishes();
$dishes = $ctrlDishes->getDishesByCategory($categoryId);

if (!$dishes || mysqli_num_rows($dishes) == 0) {
    echo "<p>Không có món ăn trong loại này.</p>";
    exit;
}

echo '<ul style="list-style:none; padding-left:0; display:flex; flex-wrap:wrap; gap:10px;">';

while ($dish = mysqli_fetch_assoc($dishes)) {
    echo '<li style="width:48%; border:1px solid #ccc; padding:10px; box-sizing:border-box;">';
    echo '<h6>' . htmlspecialchars($dish['dish_name']) . '</h6>';
    echo '<p>Giá: ' . number_format($dish['price'], 0, ',', '.') . ' đ</p>';
    echo '<button type="button" class="btn btn-sm btn-primary addDishBtn" '.
         'data-id="'.$dish['id'].'" '.
         'data-name="'.htmlspecialchars($dish['dish_name']).'" '.
         'data-price="'.$dish['price'].'">Thêm món</button>';
    echo '</li>';
}

echo '</ul>';
?>
