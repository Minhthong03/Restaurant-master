<head>
    <meta charset="UTF-8">
    <title>Menu</title>
    <link rel="stylesheet" href="css/normalize.css">
    <link rel="stylesheet" href="css/main.css" media="screen" type="text/css">
    <link href='http://fonts.googleapis.com/css?family=Pacifico' rel='stylesheet' type='text/css'>
    <link href='http://fonts.googleapis.com/css?family=Playball' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" href="css/style-portfolio.css">
    <link rel="stylesheet" href="css/picto-foundry-food.css" />
    <link rel="stylesheet" href="css/jquery-ui.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="css/font-awesome.min.css" rel="stylesheet">
    <link rel="icon" href="favicon-1.ico" type="image/x-icon">
</head>

<?php
    include_once("Controller/controlDishes.php");
    

    $p = new controlDishes();
    if (isset($_REQUEST["category_id"])) {
        $kq = $p->getDishesByCategory($_REQUEST["category_id"]);
    }
    elseif (isset($_GET['category']) && $_GET['category'] !== 'all') {
    
    $result = $p->getAllDishes();
}
    else {
        $kq = $p->getAllDishes();
    }

    if (!$kq) {
        echo "<p class='text-center'>Không có dữ liệu món ăn nào!</p>";
    } else {
        echo "<ul id='portfolio'>";
        $dem = 0;
        while ($r = mysqli_fetch_assoc($kq)) {
            echo "<li class='item'>";
            echo "<a href='?action=xemctsp&id=".$r['id']."'>";  // Tạo liên kết tới trang monan.php với tham số ?th=ID
            echo "<img src='images/Dishes/" . $r["image"] . "' alt='" . $r["dish_name"] . "' class='img-fluid' />";
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
