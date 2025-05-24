
<?php
include_once("Controller/controlOrder.php");
$p = new controlOrder();

// Nhận tham số ngày bắt đầu và kết thúc
$from = isset($_GET['from']) ? $_GET['from'] : '';
$to = isset($_GET['to']) ? $_GET['to'] : '';

$kq = $p->getAllOrders();

echo '<style>
    .revenue-container { margin-top: 40px; }
    .table td, .table th { color: #000; vertical-align: middle; }
</style>';

echo '<div class="revenue-container container">';
echo '<h2 class="mb-4 text-center text-dark">Thống kê doanh thu</h2>';

// Form lọc theo khoảng thời gian
echo '
<form method="GET" class="row mb-4 justify-content-center">
    <input type="hidden" name="action" value="AThongKe">
    <div class="col-md-3">
        <label>Từ ngày:</label>
        <input type="date" name="from" class="form-control" value="' . htmlspecialchars($from) . '">
    </div>
    <div class="col-md-3">
        <label>Đến ngày:</label>
        <input type="date" name="to" class="form-control" value="' . htmlspecialchars($to) . '">
    </div>
    <div class="col-auto align-self-end">
        <button type="submit" class="btn btn-success">📊Thống kê</button>
    </div>
</form>';

$total = 0;
$hasData = false;

if ($kq && mysqli_num_rows($kq) > 0) {
    echo '<table class="table table-bordered table-hover text-center bg-light">';
    echo '<thead class="bg-light text-dark">
            <tr>
                <th>Mã đơn</th>
                <th>Ngày đặt</th>
                <th>Khách hàng</th>
                <th>Trạng thái</th>
                <th>Tổng tiền</th>
            </tr>
          </thead><tbody>';

    while ($r = mysqli_fetch_assoc($kq)) {
        $orderDate = substr($r['order_date'], 0, 10);
        if (($from && $orderDate < $from) || ($to && $orderDate > $to)) {
            continue;
        }
        $hasData = true;
        $total += $r['total_amount'];

        echo '<tr>';
        echo '<td>' . $r['id'] . '</td>';
        echo '<td>' . $r['order_date'] . '</td>';
        echo '<td>' . htmlspecialchars($r['username']) . '</td>';
        echo '<td>' . htmlspecialchars($r['status']) . '</td>';
        echo '<td>' . number_format($r['total_amount'], 0, ',', '.') . ' đ</td>';
        echo '</tr>';
    }

    if (!$hasData) {
        echo '<tr><td colspan="5" class="text-danger">Không có đơn hàng trong khoảng thời gian đã chọn.</td></tr>';
    }

    echo '</tbody></table>';
echo '<div class="text-end fw-bold fs-3">Tổng doanh thu: <span style="color: red; font-size: 2rem;">' . number_format($total, 0, ',', '.') . ' đ</span></div>';
} else {
    echo '<div class="alert alert-warning text-center">❌ Không có dữ liệu đơn hàng.</div>';
}

echo '</div>';
?>
