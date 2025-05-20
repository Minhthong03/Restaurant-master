<style>
    .order-container, 
    .order-container * {
        color: #000 !important;
    }
    .list-group-item.category-item.active {
        background-color: #cce5ff;
        color: #000 !important;
        font-weight: bold;
    }
    #dishesList li, 
    #dishesList li * {
        color: #000 !important;
    }
    table.table, table.table th, table.table td {
        color: #000 !important;
    }
    button.btn-primary, button.btn-danger {
        color: #000 !important;}
    #dishesList {
  display: flex;
  flex-wrap: wrap;
  gap: 10px;
  padding-left: 0;
  list-style: none;
  justify-content: flex-start;
}
#dishesList li {
  flex: 1 1 calc(50% - 10px);
  border: 1px solid #ccc;
  padding: 10px;
  box-sizing: border-box;
  min-height: 180px;
  display: flex;
  flex-direction: column;
  justify-content: space-between;
  text-align: center;
  word-wrap: break-word;
  overflow: hidden;
}
#dishesList li h6 {
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
  text-overflow: ellipsis;
  height: 3em;
  margin-bottom: 10px;
}
#dishesList li button {
  margin-top: auto;
  padding: 5px 10px;
}
    
</style>

<?php
include_once("Controller/controlOrder.php");
include_once("Controller/controlOrderDetail.php");
include_once("Controller/controlTable.php");
include_once("Controller/controlDishes.php");
include_once("Controller/controlCategories.php");
$orderId = isset($_GET['viewOrderDetail']) ? intval($_GET['viewOrderDetail']) : 0;
$addMode = isset($_GET['addOrder']) ? true : false;

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

echo '<a href="nhanvientieptan.php?action=AOrderNV#pricing" class="btn btn-sm btn-danger" style="text-decoration: none;">Quay lại</a>';

}

elseif ($addMode) {

    $controlCategories = new controlCategories();
    $categories = $controlCategories->getAllCategories();

    $controlDishes = new controlDishes();

    // Lấy category đầu tiên làm mặc định
    $firstCategoryId = 0;
    if ($categories && mysqli_num_rows($categories) > 0) {
        $firstCat = mysqli_fetch_assoc($categories);
        $firstCategoryId = $firstCat['id'];
        mysqli_data_seek($categories, 0); // reset pointer
    }

    // Lấy category được chọn nếu có, mặc định loại đầu tiên
    $selectedCategoryId = isset($_GET['category_id']) ? intval($_GET['category_id']) : $firstCategoryId;

    $dishes = $controlDishes->getDishesByCategory($selectedCategoryId);

    $tableId = isset($_GET['table_id']) ? intval($_GET['table_id']) : 0;

    echo '<div class="order-container container mt-4">';
    echo '<h4 class="text-center">Thêm đơn hàng</h4>';

    echo '<div class="row">';
    // Cột trái: categories
    echo '<div class="col-md-3 border-end" style="max-height: 500px; overflow-y: auto;">';
    echo '<h5>Loại món</h5><ul class="list-group category-list" style="cursor:pointer;">';
    while ($cat = mysqli_fetch_assoc($categories)) {
        $active = ($cat['id'] == $selectedCategoryId) ? 'active' : '';
        echo '<li class="list-group-item category-item ' . $active . '" data-id="' . $cat['id'] . '">' . htmlspecialchars($cat['category_name']) . '</li>';
    }
    echo '</ul></div>';

    // Cột giữa: danh sách món
    echo '<div class="col-md-6" style="max-height: 500px; overflow-y: auto;">';
    echo '<h5>Món theo loại</h5>';
    if (!$dishes || mysqli_num_rows($dishes) == 0) {
        echo '<p>Không có món ăn trong loại này.</p>';
    } else {
        echo '<ul id="dishesList" style="list-style:none; padding-left:0; display:flex; flex-wrap:wrap; gap:10px;">';
        while ($dish = mysqli_fetch_assoc($dishes)) {
            echo '<li style="width:48%; border:1px solid #ccc; padding:10px; box-sizing:border-box;">';
            echo '<h6>' . htmlspecialchars($dish['dish_name']) . '</h6>';
            echo '<p>Giá: ' . number_format($dish['price'], 0, ',', '.') . ' đ</p>';
            echo '<button type="button" class="btn btn-sm btn-primary addDishBtn" data-id="' . $dish['id'] . '" data-name="' . htmlspecialchars($dish['dish_name']) . '" data-price="' . $dish['price'] . '">Thêm món</button>';
            echo '</li>';
        }
        echo '</ul>';
    }
    echo '</div>';

    // Cột phải: hóa đơn
    echo '<div class="col-md-3">';
    echo '<h5>Hóa đơn</h5>';
    
    echo '<div id="customerInfo" class="mb-3" style="display:none;"></div>';
    echo '<form id="orderForm" method="POST" action="processOrder.php">';
    echo '<input type="hidden" name="table_id" value="' . $tableId . '">';
    echo '<div class="mb-3 d-flex align-items-center">';
    echo '  <label for="customerEmail" class="me-2">Email khách hàng:</label>';
    echo '  <input type="email" class="form-control me-2" id="customerEmail" name="customer_email" placeholder="Nhập email khách" style="max-width: 300px;">'; // bỏ required
    echo '  <button type="button" id="btnFindCustomer" class="btn btn-primary me-2">Tìm</button>';
    echo '  <button type="button" id="btnCancelCustomer" class="btn btn-secondary btn-danger" style="display:none;">Hủy</button>';
    echo '</div>';

    echo '<div class="mb-3">';
    echo '  <label for="orderDescription">Mô tả đơn hàng:</label>';
    echo '  <textarea class="form-control" id="orderDescription" name="order_description" rows="4" placeholder="Nhập mô tả đơn hàng..."></textarea>'; // bỏ required
    echo '</div>';

    echo '<table class="table table-bordered">';
    echo '<thead><tr><th>Món</th><th>Số lượng</th><th>Thành tiền</th><th>Mô tả</th><th></th></tr></thead>';
    echo '<tbody id="orderDetailsBody"></tbody>';
    echo '</table>';

    echo '<div class="text-end mb-3"><strong>Tổng: <span id="totalAmount">0 đ</span></strong></div>';
    echo '<button type="submit" name="save_action" value="process" class="btn btn-primary w-100 mb-2">Nhận đơn</button>';
    echo '<button type="submit" name="save_action" value="save" class="btn btn-warning w-100">Đã thanh toán</button>';
    echo '<a href="nhanvientieptan.php?action=AOrderNV" class="btn btn-secondary btn-danger w-100">Hủy</a>';

    echo '</form>';
    echo '</div>';

    echo '</div>'; // row
    echo '</div>'; // container
    ?>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="js/order-script.js"></script>
    <?php
}



else {
    $p = new controlOrder();
    $order_id = isset($_GET["order_id"]) ? trim($_GET["order_id"]) : '';
    $kq = $p->getAllOrders();
    echo '<h2 class="mb-4 text-center text-dark">Quản lý đơn hàng</h2>';
    $b = new controlTable();
    $tableResult = $b->getAllTables();
    while ($h = mysqli_fetch_assoc($tableResult)) {
    echo '<a href="nhanvientieptan.php?action=AOrderNV&addOrder=true&table_id=' . $h["id"] . '#pricing" class="btn btn-success" style="text-decoration: none;">' . htmlspecialchars($h["table_number"]) . '</a>';
}

    echo '<div class="order-container container">';
    echo '<form method="GET" class="row mb-4 justify-content-center">';
    echo '<input type="hidden" name="action" value="AOrderNV">';
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
                <th>Bàn</th>
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
            echo '<td>' . htmlspecialchars($r["table_number"]) . '</td>';
            echo '<td><a href="nhanvientieptan.php?action=AOrderNV&viewOrderDetail=' . $r["id"] . '#pricing" class="btn btn-sm btn-info">Xem</a></td>';
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