<?php


include_once("Controller/controlInventoryTransactions.php");
include_once("Controller/controlIngredients.php");

$transactionCtrl = new controlInventoryTransactions();
$ingredientCtrl = new controlIngredients();

$currentUserId = $_SESSION["UserID"] ?? 0;
$transaction_type = 2; // 2 = xuất kho
$addMode = isset($_GET['addTransaction']) ? true : false;
$viewId = isset($_GET['viewTransaction']) ? intval($_GET['viewTransaction']) : 0;

$ingredients = $ingredientCtrl->getAllIngredients();

echo '<div class="container mt-5">';

// --- XỬ LÝ POST THÊM PHIẾU XUẤT ---
if (isset($_POST['btnInsert'])) {
    $ingredient_id = intval($_POST['ingredient_id']);
    $transaction_type_post = intval($_POST['transaction_type']);
    $quantity = intval($_POST['quantity']);
    $transaction_date = $_POST['transaction_date'];
    $total = floatval($_POST['total']);
    $user_id = intval($_POST['user_id']);

    $insertResult = $transactionCtrl->insertTransaction($ingredient_id, $transaction_type_post, $quantity, $transaction_date, $total, $user_id);

    if ($insertResult) {
        echo "<script>alert('Thêm phiếu thành công!'); window.location.href = 'nhanvienkho.php?action=AExportInventory';</script>";
        exit;
    } else {
        echo "<script>alert('Thêm phiếu thất bại!');</script>";
    }
}

// --- XEM CHI TIẾT PHIẾU (CHỈ XEM) ---
if ($viewId > 0) {
    $transactionResult = $transactionCtrl->getTransactionById($viewId);
    if ($transactionResult && mysqli_num_rows($transactionResult) > 0) {
        $t = mysqli_fetch_assoc($transactionResult);
        echo '<h4 class="mb-4">Chi tiết phiếu Xuất kho #' . $t['id'] . '</h4>';
        echo '<table class="table table-bordered">';
        echo '<tr><th>Nguyên liệu</th><td>' . htmlspecialchars($t['ingredient_name']) . '</td></tr>';
        echo '<tr><th>Loại giao dịch</th><td>Xuất kho</td></tr>';
        echo '<tr><th>Số lượng</th><td>' . $t['quantity'] . '</td></tr>';
        echo '<tr><th>Ngày giao dịch</th><td>' . $t['transaction_date'] . '</td></tr>';
        echo '<tr><th>Tổng tiền</th><td>' . number_format($t['total'], 2) . '</td></tr>';
        echo '<tr><th>Người lập phiếu</th><td>' . htmlspecialchars($t['username']) . '</td></tr>';
        echo '</table>';
        echo '<a href="nhanvienkho.php?action=AExportInventory" class="btn btn-secondary">Quay lại</a>';
    } else {
        echo '<div class="alert alert-warning">Không tìm thấy phiếu.</div>';
    }
}
// --- THÊM PHIẾU MỚI ---
elseif ($addMode) {
    ?>

    <h4 class="mb-4">Thêm phiếu Xuất kho mới</h4>

    <form method="POST" action="nhanvienkho.php?action=AExportInventory&addTransaction=true" id="exportForm">

        <div class="mb-3">
            <label>Nguyên liệu:</label>
            <select id="ingredientSelect" name="ingredient_id" class="form-control" required>
                <option value="">-- Chọn nguyên liệu --</option>
                <?php
                if ($ingredients) {
                    mysqli_data_seek($ingredients, 0);
                    while ($ing = mysqli_fetch_assoc($ingredients)) {
                        echo '<option value="' . $ing['id'] . '">' . htmlspecialchars($ing['name']) . '</option>';
                    }
                }
                ?>
            </select>
        </div>

        <div class="mb-3">
            <label>Số lượng:</label>
            <input type="number" id="quantityInput" name="quantity" min="1" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Ngày giao dịch:</label>
            <input type="datetime-local" name="transaction_date" class="form-control" required value="<?php echo date('Y-m-d\TH:i'); ?>">
        </div>

        <div class="mb-3">
            <label>Tổng tiền:</label>
            <input type="number" id="totalInput" name="total" step="0.01" class="form-control" required readonly>
        </div>

        <!-- user_id ẩn lấy từ session -->
        <input type="hidden" name="user_id" value="<?php echo $currentUserId; ?>">

        <input type="hidden" name="transaction_type" value="2">

        <button type="submit" class="btn btn-success" name="btnInsert">Thêm phiếu</button>
        <a href="nhanvienkho.php?action=AExportInventory" class="btn btn-danger">Hủy</a>

    </form>

    <script>
        window.unitPrices = {
            <?php
            if ($ingredients) {
                mysqli_data_seek($ingredients, 0);
                $arr = [];
                while ($ing = mysqli_fetch_assoc($ingredients)) {
                    $arr[] = "'" . $ing['id'] . "': " . $ing['unit_price'];
                }
                echo implode(", ", $arr);
            }
            ?>
        };
    </script>
    <script src="js/inventory.js"></script>

    <?php
}
// --- HIỂN THỊ DANH SÁCH PHIẾU XUẤT ---
else {
    $searchName = isset($_GET['searchName']) ? trim($_GET['searchName']) : '';
    $searchDate = isset($_GET['searchDate']) ? trim($_GET['searchDate']) : '';

    $transactions = $transactionCtrl->getTransactionsByType(2);

    echo '<h2 class="mb-4">Danh sách phiếu Xuất kho</h2>';
    echo '<a href="nhanvienkho.php?action=AExportInventory&addTransaction=true" class="btn btn-success mb-3">Thêm phiếu</a>';

    // Form tìm kiếm
    echo '<form method="GET" class="row mb-4">';
    echo '<input type="hidden" name="action" value="AExportInventory">';
    echo '<div class="col-md-4"><input type="text" name="searchName" placeholder="Tên nguyên liệu" class="form-control" value="' . htmlspecialchars($searchName) . '"></div>';
    echo '<div class="col-md-4"><input type="date" name="searchDate" class="form-control" value="' . htmlspecialchars($searchDate) . '"></div>';
    echo '<div class="col-md-auto"><button type="submit" class="btn btn-primary">Tìm kiếm</button></div>';
    echo '<div class="col-md-auto"><a href="nhanvienkho.php?action=AExportInventory" class="btn btn-danger">Hủy</a></div>';
    echo '</form>';

    $filtered = [];
    if ($transactions) {
        while ($row = mysqli_fetch_assoc($transactions)) {
            $matchName = true;
            $matchDate = true;

            if ($searchName != '') {
                $matchName = stripos($row['ingredient_name'], $searchName) !== false;
            }
            if ($searchDate != '') {
                $dateOnly = substr($row['transaction_date'], 0, 10);
                $matchDate = ($dateOnly === $searchDate);
            }

            if ($matchName && $matchDate) {
                $filtered[] = $row;
            }
        }
    }

    if (count($filtered) == 0) {
        echo '<div class="alert alert-warning">Không có phiếu nào.</div>';
    } else {
        echo '<table class="table table-bordered table-hover text-center bg-light">';
        echo '<thead><tr class="bg-light text-dark">
                <th>ID</th>
                <th>Nguyên liệu</th>
                <th>Số lượng</th>
                <th>Ngày giao dịch</th>
                <th>Tổng tiền</th>
                <th>Người lập phiếu</th>
                <th>Thao tác</th>
              </tr></thead><tbody>';

        foreach ($filtered as $tran) {
            echo '<tr>';
            echo '<td>' . $tran['id'] . '</td>';
            echo '<td>' . htmlspecialchars($tran['ingredient_name']) . '</td>';
            echo '<td>' . $tran['quantity'] . '</td>';
            echo '<td>' . $tran['transaction_date'] . '</td>';
            echo '<td>' . number_format($tran['total'], 2) . '</td>';
            echo '<td>' . htmlspecialchars($tran['username']) . '</td>';
            echo '<td><a href="nhanvienkho.php?action=AExportInventory&viewTransaction=' . $tran['id'] . '" class="btn btn-info btn-sm">Xem</a></td>';
            echo '</tr>';
        }
        echo '</tbody></table>';
    }
}

echo '</div>';
?>
