<?php
include_once("Controller/controlOrder.php");
include_once("Controller/controlOrderDetail.php");

$orderId = isset($_GET['viewOrderDetail']) ? intval($_GET['viewOrderDetail']) : 0;

echo '<style>
    .table td, .table th { color: #000; vertical-align: middle; }
    .order-container { margin-top: 40px; }
</style>';
if ($orderId > 0) {
    $detailCtrl = new controlOrderDetail();
    $details = $detailCtrl->getOrderDetailsByOrderId($orderId);

    // Lấy thêm thông tin đơn hàng (mô tả)
    $orderCtrl = new controlOrder();
    $order = $orderCtrl->getOrderById($orderId);
    $orderDescription = '';
    if ($order && $rowOrder = mysqli_fetch_assoc($order)) {
        $orderDescription = $rowOrder['description']; // cột ghi chú đơn hàng
    }

    echo '<div class="order-container container">';
    echo '<h4 class="text-center">Chi tiết đơn hàng #' . $orderId . '</h4>';

    // Hiển thị ghi chú đơn hàng ở trên bảng
    echo '<div class="mb-3"><strong>Ghi chú đơn hàng:</strong> ' . htmlspecialchars($orderDescription) . '</div>';

    if (!$details) {
        echo '<div class="alert alert-danger text-center">Không có món ăn nào trong đơn hàng này.</div>';
    } else {
        echo '<table class="table table-striped text-center">';
        echo '<thead><tr>
                <th>Tên món</th>
                <th>Số lượng</th>
                <th>Đơn giá</th>
                <th>Thành tiền</th>
                <th>Ghi chú</th>
              </tr></thead><tbody>';
        while ($item = mysqli_fetch_assoc($details)) {
            echo '<tr>';
            echo '<td>' . htmlspecialchars($item["dish_name"]) . '</td>';
            echo '<td>' . $item["quantity"] . '</td>';
            echo '<td>' . number_format($item["unit_price"], 0, ',', '.') . ' đ</td>';
            echo '<td>' . number_format($item["unit_price"] * $item["quantity"], 0, ',', '.') . ' đ</td>';
            echo '<td>' . htmlspecialchars($item["note"]) . '</td>';
            echo '</tr>';
        }
        echo '</tbody></table>';
    }

    echo '<a href="admin.php?action=AOrder" class="btn btn-sm btn-danger" style="text-decoration: none;">Quay lại</a>';
}
 else {
    $p = new controlOrder();
    $order_id = isset($_GET["order_id"]) ? trim($_GET["order_id"]) : '';
    $kq = $p->getAllOrders();
    echo '<h2 class="mb-4 text-center text-dark">Quản lý đơn hàng</h2>';

    echo '<div class="order-container container">';
    echo '<form method="GET" class="row mb-4 justify-content-center">';
    echo '<input type="hidden" name="action" value="AOrder">';
    echo '<div class="col-md-3">';
    echo '<input type="number" name="order_id" class="form-control" placeholder="Nhập mã đơn hàng" value="' . htmlspecialchars($order_id) . '">';
    echo '</div>';
    echo '<div class="col-auto">';
    echo '<button type="submit" class="btn btn-primary">🔍 Tìm đơn hàng</button>';
    echo '</div></form>';

    if (!$kq || mysqli_num_rows($kq) == 0) {
        echo '<div class="alert alert-warning text-center">❌ Không có dữ liệu đơn hàng.</div>';
    } else {
        echo '<table class="table table-bordered table-hover text-center bg-light">';
        echo '<thead><tr class="bg-light text-dark">
                <th>Mã đơn</th>
                <th>Tên khách</th>
                <th>Ngày đặt</th>
                <th>Tổng tiền</th>
                <th>Trạng thái</th>
                <th>Chi tiết</th>
              </tr></thead><tbody>';

        $found = false;
        while ($r = mysqli_fetch_assoc($kq)) {
            if ($order_id !== '' && $r["id"] != $order_id) continue;

            $found = true;
            echo '<tr>';
            echo '<td>' . $r["id"] . '</td>';
            echo '<td>' . (!empty($r["username"]) ? htmlspecialchars($r["username"]) : 'Ẩn danh') . '</td>';
            echo '<td>' . $r["order_date"] . '</td>';
            echo '<td>' . number_format($r["total_amount"], 0, ',', '.') . ' đ</td>';
            echo '<td>' . htmlspecialchars($r["status"]) . '</td>';
            echo '<td><a href="admin.php?action=AOrder&viewOrderDetail=' . $r["id"] . '" class="btn btn-sm btn-info">Xem</a></td>';
            echo '</tr>';
        }

        if (!$found && $order_id !== '') {
            echo '<tr><td colspan="6" class="text-danger">Không tìm thấy mã đơn <strong>' . htmlspecialchars($order_id) . '</strong></td></tr>';
        }

        echo '</tbody></table>';
    }

    echo '</div>';
}
?>