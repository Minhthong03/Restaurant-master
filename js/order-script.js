$(function () {
    // ========== 1. Load danh sách món ăn theo loại ==========
    $('.category-item').click(function () {
        $('.category-item').removeClass('active');
        $(this).addClass('active');

        var categoryId = $(this).data('id');

        $.get('ajax/ajaxGetDishes.php', { category_id: categoryId }, function (data) {
            $('#dishesList').html(data);
            bindAddDishButtons(); // Gắn lại sự kiện cho nút thêm sau khi load
        });
    });

    // ========== 2. Gắn sự kiện cho nút thêm món ban đầu ==========
    bindAddDishButtons();

    function bindAddDishButtons() {
        $('.addDishBtn').off('click').on('click', function () {
            let dishId = $(this).data('id');
            let dishName = $(this).data('name');
            let dishPrice = parseFloat($(this).data('price'));
            addDishToOrder(dishId, dishName, dishPrice);
        });
    }

    // ========== 3. Thêm món vào hóa đơn ==========
    function addDishToOrder(id, name, price) {
        var row = $('#orderDetailsBody tr[data-id="' + id + '"]');
        if (row.length) {
            var qtyInput = row.find('input.qtyInput');
            qtyInput.val(parseInt(qtyInput.val()) + 1);
            updateRowTotal(row);
            updateTotal();
            return;
        }

        var newRow = `
            <tr data-id="${id}">
                <td>${name}<input type="hidden" name="dishes[]" value="${id}"></td>
                <td><input type="number" name="quantities[]" value="1" min="1" class="form-control qtyInput" style="width:70px;"></td>
                <td class="rowTotal">${price.toLocaleString('vi-VN')} đ</td>
                <td><input type="text" name="notes[]" class="form-control" placeholder="Ghi chú"></td>
                <td><button type="button" class="btn btn-sm btn-danger removeBtn">Xóa</button></td>
                <input type="hidden" name="prices[]" value="${price}">
            </tr>`;

        $('#orderDetailsBody').append(newRow);
        row = $('#orderDetailsBody tr[data-id="' + id + '"]');

        row.find('input.qtyInput').on('input', function () {
            updateRowTotal(row);
            updateTotal();
        });

        row.find('.removeBtn').click(function () {
            $(this).closest('tr').remove();
            updateTotal();
        });

        updateTotal();
    }

    // ========== 4. Cập nhật thành tiền cho mỗi dòng ==========
    function updateRowTotal(row) {
        let qty = parseInt(row.find('input.qtyInput').val());
        let price = parseFloat(row.find('input[name="prices[]"]').val());
        let total = qty * price;
        row.find('.rowTotal').text(total.toLocaleString('vi-VN') + ' đ');
    }

    // ========== 5. Cập nhật tổng tiền hóa đơn ==========
    function updateTotal() {
        let total = 0;
        $('#orderDetailsBody tr').each(function () {
            let qty = parseInt($(this).find('input.qtyInput').val());
            let price = parseFloat($(this).find('input[name="prices[]"]').val());
            total += qty * price;
        });
        $('#totalAmount').text(total.toLocaleString('vi-VN') + ' đ');
    }

      // Hàm xử lý tìm khách hàng khi nhấn nút "Tìm"
    $('#btnFindCustomer').on('click', function () {
        var email = $('#customerEmail').val().trim();
        if (email === '') {
            alert('Vui lòng nhập email khách hàng');
            return;
        }
        $.get('ajax/getUserByEmail.php', { email: email }, function (res) {
            var response = JSON.parse(res);
            if (response.success) {
                let user = response.data;
                // Hiện thông tin khách hàng trong textarea mô tả
                var desc = `Tên: ${user.username}\nSĐT: ${user.phone}\nĐịa chỉ: ${user.address}`;
                $('#orderDescription').val(desc);

                // Ẩn nút tìm, hiện nút hủy, khóa ô email
                $('#btnCancelCustomer').show();
                $('#btnFindCustomer').hide();
                $('#customerEmail').prop('disabled', true);

                // Thêm hoặc cập nhật input hidden customer_id trong form
                if ($('input[name="customer_id"]').length === 0) {
                    $('<input>').attr({
                        type: 'hidden',
                        name: 'customer_id',
                        value: user.id
                    }).appendTo('#orderForm');
                } else {
                    $('input[name="customer_id"]').val(user.id);
                }
            } else {
                alert(response.message);
                $('#orderDescription').val('');
                // Nếu tìm không được thì cũng xóa input hidden customer_id nếu có
                $('input[name="customer_id"]').remove();
            }
        });
    });

    $('#btnCancelCustomer').on('click', function () {
        // Xóa input hidden customer_id
        $('input[name="customer_id"]').remove();

        $('#orderDescription').val('');
        $('#customerEmail').val('').prop('disabled', false);
        $('#btnCancelCustomer').hide();
        $('#btnFindCustomer').show();
    });
});
