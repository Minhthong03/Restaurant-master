<?php

include_once("Controller/controlCart.php");
include_once("Controller/cNguoiDung.php");

$controlCart = new controlCart();
$cNguoiDung = new cNguoiDung();

$customer_id = $_SESSION['UserID'];
$cart_id = $controlCart->getCartIdByCustomer($customer_id);
$items = $controlCart->getCartItems($cart_id);

$userResult = $cNguoiDung->getOneNguoiDung($customer_id);
$user = false;
if ($userResult && mysqli_num_rows($userResult) > 0) {
    $user = mysqli_fetch_assoc($userResult);
}

$default_description = "";
if ($user) {
    $default_description = "Tên: " . $user['username'] . "\n";
    $default_description .= "SĐT: " . $user['phone'] . "\n";
    $default_description .= "Địa chỉ: " . $user['address'] . "\n";
}
?>

<style>
    table td, table th, h2, h3 {
        color: #000 !important;
    }
    .qty {
        color: #000 !important;
    }
</style>

<div class="container mt-4">

    <h2>Mô tả đơn hàng</h2>
    <textarea id="order-description" class="form-control" rows="4"><?= htmlspecialchars($default_description) ?></textarea>

    <h2 class="mt-4">Giỏ hàng của bạn</h2>
    <table class="table table-bordered text-center" id="cart-table">
        <thead class="table-dark">
            <tr>
                <th>Sản phẩm</th>
                <th>Đơn giá</th>
                <th>Số lượng</th>
                <th>Ghi chú</th>
                <th>Thành tiền</th>
                <th>Thao tác</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $tongTien = 0;
            while ($item = mysqli_fetch_assoc($items)) {
                $total = $item['price'] * $item['quantity'];
                $tongTien += $total;
                echo "<tr data-dish-id='{$item['dish_id']}'>";
                echo "<td>{$item['dish_name']}</td>";
                echo "<td class='price'>" . number_format($item['price'], 0, ',', '.') . "</td>";
                echo "<td>
                        <button class='btn btn-sm btn-outline-secondary decrease'>-</button>
                        <input type='text' class='qty' value='{$item['quantity']}' readonly style='width:40px; text-align:center; border:none;' />
                        <button class='btn btn-sm btn-outline-secondary increase'>+</button>
                      </td>";
                echo "<td><input type='text' class='note-input form-control' value='" . htmlspecialchars($item['note']) . "' style='width:150px;'></td>";
                echo "<td class='total'>" . number_format($total, 0, ',', '.') . "</td>";
                echo "<td><button class='btn btn-danger btn-sm delete'>Xóa</button></td>";
                echo "</tr>";
            }
            ?>
        </tbody>
        <tfoot>
            <tr>
                <th colspan="4">Tổng cộng</th>
                <th colspan="1" id="grand-total"><?= number_format($tongTien, 0, ',', '.') ?></th>
                <th colspan="1">
                    <button id="place-order" class="btn btn-success btn-sm">Đặt hàng</button>
                </th>
            </tr>
        </tfoot>
    </table>
    <button id="save-changes" class="btn btn-danger btn-sm">Quay lại</button>

</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function(){
    var deletedDishes = new Set();

    // Hàm kiểm tra và bật/tắt nút đặt hàng
    function updatePlaceOrderButton() {
        let visibleCount = $('#cart-table tbody tr:not(:hidden)').length;
        $('#place-order').prop('disabled', visibleCount === 0);
    }

    // Gọi khi trang tải xong
    updatePlaceOrderButton();

    // Tăng số lượng
    $(document).on('click', '.increase', function(){
        var row = $(this).closest('tr');
        if(deletedDishes.has(row.data('dish-id'))) return;
        updateQuantity(row, 1);
        updatePlaceOrderButton();
    });

    // Giảm số lượng
    $(document).on('click', '.decrease', function(){
        var row = $(this).closest('tr');
        if(deletedDishes.has(row.data('dish-id'))) return;
        updateQuantity(row, -1);
        updatePlaceOrderButton();
    });

    // Xóa món (ẩn dòng, đánh dấu đã xóa)
    $(document).on('click', '.delete', function(){
        if (!confirm("Bạn có chắc muốn xóa món này không?")) return;
        var row = $(this).closest('tr');
        deletedDishes.add(row.data('dish-id'));
        row.hide();
        recalcGrandTotal();
        updatePlaceOrderButton();
    });

    function updateQuantity(row, change) {
        var input = row.find('.qty');
        var newQty = parseInt(input.val()) + change;
        if(newQty < 1) newQty = 1;
        input.val(newQty);
        recalcRowTotal(row);
        recalcGrandTotal();
    }

    function recalcRowTotal(row) {
        var price = parseInt(row.find('.price').text().replace(/[^\d]/g,''));
        var qty = parseInt(row.find('.qty').val());
        var total = price * qty;
        row.find('.total').text(total.toLocaleString('vi-VN'));
    }

    function recalcGrandTotal() {
        var grandTotal = 0;
        $('#cart-table tbody tr').each(function(){
            var row = $(this);
            var dish_id = row.data('dish-id');
            if(deletedDishes.has(dish_id) || row.is(':hidden')) return;
            var total = parseInt(row.find('.total').text().replace(/[^\d]/g,''));
            grandTotal += total;
        });
        $('#grand-total').text(grandTotal.toLocaleString('vi-VN'));
    }

    // Nút lưu thay đổi (quay lại trang khachhang.php)
    $('#save-changes').click(function(){
        var orderDescription = $('#order-description').val();

        var cartData = [];
        $('#cart-table tbody tr').each(function(){
            var row = $(this);
            var dish_id = row.data('dish-id');
            if(row.is(':hidden')) return;
            var quantity = parseInt(row.find('.qty').val());
            var note = row.find('.note-input').val();

            cartData.push({
                dish_id: dish_id,
                quantity: quantity,
                note: note
            });
        });

        $.post('ajax/ajaxCart.php', {
            action: 'save_all_changes',
            description: orderDescription,
            cart_data: JSON.stringify(cartData),
            deleted_dishes: JSON.stringify(Array.from(deletedDishes))
        }, function(response){
            if(response.success){
                window.location.href = 'khachhang.php';
            } else {
                alert('Lỗi lưu thay đổi: ' + response.message);
            }
        }, 'json');
    });

    // Nút đặt hàng
    $('#place-order').click(function(){
        var description = $('#order-description').val();
        var orderDetails = [];
        $('#cart-table tbody tr').each(function(){
            var row = $(this);
            if(row.is(':hidden')) return;
            orderDetails.push({
                dish_id: row.data('dish-id'),
                quantity: parseInt(row.find('.qty').val()),
                note: row.find('.note-input').val()
            });
        });

        $.post('ajax/ajaxCart.php', {
            action: 'place_order',
            description: description,
            order_details: orderDetails
        }, function(response){
            if(response.success){
                alert('Đặt hàng thành công, mã đơn hàng: ' + response.order_id);
                window.location.href = 'khachhang.php';
            } else {
                alert('Lỗi: ' + response.message);
            }
        }, 'json');
    });

});
</script>
