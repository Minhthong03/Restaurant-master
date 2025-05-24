<?php
include_once("Controller/controlDishes.php");

$p = new controlDishes();
$searchName = $_GET['query'] ?? '';

if ($searchName === '') {
    echo "<p>Vui lòng nhập từ khóa tìm kiếm.</p>";
    exit;
}

$kq = $p->getDishesByName($searchName);

if (!$kq) {
    echo "<p>Không tìm thấy món ăn nào phù hợp với từ khóa '$searchName'.</p>";
} else {
    echo "<ul id='portfolio'>";
    $dem = 0;
    while ($r = mysqli_fetch_assoc($kq)) {
        echo "<li class='item'>";
        echo "<a href='?action=xemctsp&id=".$r['id']."'>";
        echo "<img src='images/Dishes/" . htmlspecialchars($r["image"]) . "' alt='" . htmlspecialchars($r["dish_name"]) . "' class='img-fluid' />";
        echo "</a>";
        echo "<h2 class='white'>" . number_format($r['price'], 0, ",", ".") . " VND</h2>";
        echo "</li>";
        $dem++;
        if ($dem % 4 == 0) {
            echo "</ul><ul id='portfolio'>";
        }
    }
    echo "</ul>";
}
?>
