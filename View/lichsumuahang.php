<!DOCTYPE html>
<html>
<head>
    <title>Lịch sử mua hàng</title>
    <style>
        /* CSS bảng chi tiết đơn hàng */
    .order-details-table {
        color: black !important;
        border-collapse: collapse !important;
        width: 100% !important;
        font-family: Arial, sans-serif;
        margin-top: 20px;
    }

    .order-details-table th,
    .order-details-table td {
        border: 1px solid #ddd !important;
        padding: 10px !important;
        text-align: left !important;
        color: black !important;
    }

    .order-details-table th {
        background-color: #f5f5f5;
        font-weight: bold;
    }
        select#status {
            color: black;
            font-weight: bold;
        }
        .order-history-table, .order-details-table {
            color: black !important;
            border-collapse: collapse !important;
            width: 100% !important;
            font-family: Arial, sans-serif;
            margin-top: 20px;
        }
        .order-history-table th, .order-history-table td,
        .order-details-table th, .order-details-table td {
            border: 1px solid #ddd !important;
            padding: 10px !important;
            text-align: left !important;
            color: black !important;
        }
        .order-history-table th, .order-details-table th {
            background-color: #f5f5f5;
            font-weight: bold;
        }
        .status-label {
            padding: 5px 10px;
            border-radius: 4px;
            font-weight: bold;
            display: inline-block;
        }
        .status-Chờ\ xác\ nhận { background-color: #FFA500; color: black; }
        .status-Đang\ xử\ lý { background-color: #17a2b8; color: white; }
        .status-Đã\ giao { background-color: #28a745; color: white; }
        .status-Đã\ hủy { background-color: #dc3545; color: white; }
        .btn-cancel, .btn-view, .btn-back {
            padding: 5px 10px;
            color: white;
            border: none;
            cursor: pointer;
            border-radius: 3px;
            margin-right: 5px;
        }
        .btn-cancel {
            background-color: #dc3545;
        }
        .btn-cancel:hover {
            background-color: #b02a37;
        }
        .btn-view {
            background-color: #007bff;
        }
        .btn-view:hover {
            background-color: #0056b3;
        }
        .btn-back {
            background-color: #6c757d;
            margin-top: 20px;
        }
        .btn-back:hover {
            background-color: #5a6268;
        }
        #order-details-container {
            display: none;
        }
        #order-details-container h3 {
    color: black !important;
}
    </style>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
<h2>Lịch sử mua hàng</h2>

<div id="order-list-container">
    <label for="status">Lọc theo trạng thái:</label>
    <select name="status" id="status">
        <option value="Tất cả">Tất cả</option>
        <option value="Chờ xác nhận">Chờ xác nhận</option>
        <option value="Đang xử lý">Đang xử lý</option>
        <option value="Đã giao">Đã giao</option>
        <option value="Đã hủy">Đã hủy</option>
    </select>

    <table class="order-history-table" id="order-table">
        <thead>
            <tr>
                <th>Mã đơn hàng</th>
                <th>Ngày đặt</th>
                <th>Tổng tiền</th>
                <th>Trạng thái</th>
                <th>Mô tả</th>
                <th>Thao tác</th> <!-- đổi tên cột -->
            </tr>
        </thead>
        <tbody>
            <!-- Load động qua ajax -->
        </tbody>
    </table>
</div>

<div id="order-details-container">
    <h3>Chi tiết đơn hàng <span id="order-id-detail"></span></h3>
    <p><strong>Ghi chú đơn hàng:</strong> <span id="order-note-detail"></span></p>

    <table class="order-details-table" id="order-details-table">
        <thead>
            <tr>
                <th>Tên món</th>
                <th>Số lượng</th>
                <th>Đơn giá</th>
                <th>Thành tiền</th>
                <th>Ghi chú</th>
            </tr>
        </thead>
        <tbody>
            <!-- Load chi tiết đơn -->
        </tbody>
    </table>
    <button class="btn-back btn-danger " id="btn-back">Quay lại</button>
</div>

<script>
$(document).ready(function(){
    // Hàm load danh sách đơn hàng
    function loadOrders(status) {
        $.ajax({
            url: 'ajax/ajaxOrder.php',
            method: 'POST',
            data: { status: status, action: 'get_orders' },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    var tbody = $('#order-table tbody');
                    tbody.empty();

                    if (response.orders.length === 0) {
                        tbody.append('<tr><td colspan="6" style="text-align:center;">Không có đơn hàng nào phù hợp.</td></tr>');
                        return;
                    }

                    $.each(response.orders, function(i, order) {
                        var statusClass = 'status-' + order.status.replace(/ /g, '\\ ');
                        var cancelButton = '';
                        if (order.status === 'Chờ xác nhận') {
                            cancelButton = '<button class="btn-cancel" data-order-id="' + order.id + '">Hủy</button>';
                        }
                        var viewButton = '<button class="btn-view" data-order-id="' + order.id + '">Xem</button>';

                        var row = '<tr>' +
                            '<td>' + order.id + '</td>' +
                            '<td>' + (new Date(order.order_date)).toLocaleDateString('vi-VN') + '</td>' +
                            '<td>' + Number(order.total_amount).toLocaleString('vi-VN') + ' đ</td>' +
                            '<td><span class="status-label ' + statusClass + '">' + order.status + '</span></td>' +
                            '<td>' + (order.description ? order.description.replace(/\n/g, "<br>") : '') + '</td>' +
                            '<td>' + cancelButton + viewButton + '</td>' +
                            '</tr>';
                        tbody.append(row);
                    });
                } else {
                    alert('Lỗi lấy dữ liệu: ' + response.message);
                }
            },
            error: function() {
                alert('Lỗi server hoặc mạng.');
            }
        });
    }

    // Hàm load chi tiết đơn hàng
    function loadOrderDetails(orderId) {
        $.ajax({
            url: 'ajax/ajaxOrder.php',
            method: 'POST',
            data: { order_id: orderId, action: 'get_order_details' },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    $('#order-list-container').hide();
                    $('#order-details-container').show();

                    $('#order-id-detail').text('#' + orderId);
                    $('#order-note-detail').html(response.order.description ? response.order.description.replace(/\n/g, "<br>") : '(Không có)');

                    var tbody = $('#order-details-table tbody');
                    tbody.empty();

                    if (response.details.length === 0) {
                        tbody.append('<tr><td colspan="5" style="text-align:center;">Không có chi tiết đơn hàng.</td></tr>');
                        return;
                    }

                    $.each(response.details, function(i, item) {
                        var row = '<tr>' +
                            '<td>' + item.dish_name + '</td>' +
                            '<td>' + item.quantity + '</td>' +
                            '<td>' + Number(item.unit_price).toLocaleString('vi-VN') + ' đ</td>' +
                            '<td>' + (item.quantity * item.unit_price).toLocaleString('vi-VN') + ' đ</td>' +
                            '<td>' + (item.note ? item.note : '') + '</td>' +
                            '</tr>';
                        tbody.append(row);
                    });
                } else {
                    alert('Lỗi lấy chi tiết đơn: ' + response.message);
                }
            },
            error: function() {
                alert('Lỗi server hoặc mạng khi lấy chi tiết đơn.');
            }
        });
    }

    // Load danh sách đơn khi trang load
    loadOrders('Tất cả');

    // Lọc trạng thái
    $('#status').change(function(){
        var status = $(this).val();
        loadOrders(status);
    });

    // Hủy đơn
    $('#order-table').on('click', '.btn-cancel', function(){
        if(!confirm('Bạn có chắc chắn muốn hủy đơn hàng này?')) return;

        var orderId = $(this).data('order-id');
        var statusSelected = $('#status').val();

        $.ajax({
            url: 'ajax/ajaxOrder.php',
            method: 'POST',
            data: { order_id: orderId, action: 'cancel_order' },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    alert('Hủy đơn hàng thành công!');
                    loadOrders(statusSelected);
                } else {
                    alert('Lỗi: ' + response.message);
                }
            },
            error: function() {
                alert('Lỗi server hoặc mạng khi hủy đơn.');
            }
        });
    });

    // Xem chi tiết đơn
    $('#order-table').on('click', '.btn-view', function(){
        var orderId = $(this).data('order-id');
        loadOrderDetails(orderId);
    });

    // Quay lại danh sách đơn
    // Sử dụng event delegation gán sự kiện, phòng trường hợp nút được tạo động
$(document).on('click', '#btn-back', function() {
    console.log('Nút Quay lại được nhấn');
    $('#order-details-container').hide();
    $('#order-list-container').show();

    var status = $('#status').val();
    loadOrders(status);
});
});
</script>

</body>
</html>
