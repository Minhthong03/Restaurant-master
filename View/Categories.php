<style>
    .category-btn {
        background-color:rgb(10, 10, 10);
        color: white;
        font-weight: bold;
        border-radius: 10px;
        transition: color 0.3s ease;
        text-decoration: none;
        display: block;
        text-align: center;
        padding: 12px;
    }

    .category-btn:hover {
        color: red;
        text-decoration: none;
    }

    .category-wrapper {
        padding: 30px 0; /* khoảng cách trên dưới */
    }
</style>

<?php
include_once("Controller/controlCategories.php");

$p = new controlCategories();
$kq = $p->getAllCategories();

if (!$kq) {
    echo "<p class='text-danger text-center mt-3'>❌ Không có dữ liệu danh mục</p>";
} else {
    echo "<div class='container'>";
    echo "<div class='row justify-content-center'>";
    echo "
<div class='col-6 col-sm-4 col-md-3 col-lg-2 mb-3'>
    <a href='?category=all' class='category-btn'>Tất cả</a>
</div>
";

    while ($r = mysqli_fetch_assoc($kq)) {
        echo "
        <div class='col-6 col-sm-4 col-md-3 col-lg-2 mb-3'>
            <a href='?category_id=" . $r['id'] . "' class='category-btn'>
                " . htmlspecialchars($r['category_name']) . "
            </a>
        </div>
        ";
    }

    echo "</div></div></div>";
}
?>
