<style>
  /* Container giỏ hàng */
  .cart-container {
    max-width: 1000px;
    margin: 20px auto;
    background: #fff;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 0 10px rgb(0 0 0 / 0.1);
  }

  /* Tiêu đề bảng */
  .cart-header {
    display: flex;
    font-weight: 700;
    padding: 10px 0;
    border-bottom: 2px solid #ddd;
    color: #333;
  }
  .cart-header > div {
    flex: 1;
    text-align: center;
  }
  .cart-header > div:first-child {
    flex: 0.5;
    text-align: left;
  }

  /* Mỗi sản phẩm trong giỏ */
  .cart-item {
    display: flex;
    align-items: center;
    padding: 15px 0;
    border-bottom: 1px solid #eee;
  }
  .cart-item > div {
    flex: 1;
    text-align: center;
    font-size: 14px;
    color: #444;
  }
  .cart-item > div:first-child {
    flex: 0.5;
    display: flex;
    align-items: center;
    gap: 15px;
    text-align: left;
  }
  .cart-item img {
    width: 80px;
    height: 80px;
    object-fit: contain;
    border-radius: 5px;
  }

  /* Giá gốc gạch ngang */
  .price-old {
    text-decoration: line-through;
    color: #999;
    font-size: 13px;
  }

  /* Giá mới nổi bật */
  .price-new {
    color: #e53935;
    font-weight: 700;
    font-size: 16px;
  }

  /* Điều khiển số lượng */
  .quantity-control {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 8px;
  }
  .quantity-control button {
    background: #f0f0f0;
    border: none;
    width: 28px;
    height: 28px;
    cursor: pointer;
    font-weight: bold;
    border-radius: 4px;
  }
  .quantity-control input {
    width: 40px;
    text-align: center;
    border: 1px solid #ccc;
    border-radius: 4px;
    font-size: 14px;
  }

  /* Thao tác xóa, tìm sản phẩm tương tự */
  .action-links {
    display: flex;
    flex-direction: column;
    gap: 6px;
  }
  .action-links a {
    color: #e53935;
    font-size: 13px;
    text-decoration: none;
    cursor: pointer;
  }
  .action-links a:hover {
    text-decoration: underline;
  }

  /* Khuyến mãi */
  .promo-banner {
    background: #ffe6e1;
    color: #e53935;
    font-size: 13px;
    padding: 6px 10px;
    border-radius: 5px;
    margin-bottom: 10px;
    display: inline-block;
  }
</style>

<div class="cart-container">
  <div class="cart-header">
    <div><input type="checkbox" checked></div>
    <div>Sản Phẩm</div>
    <div>Đơn Giá</div>
    <div>Số Lượng</div>
    <div>Số Tiền</div>
    <div>Thao Tác</div>
  </div>

  <!-- Ví dụ 1 sản phẩm -->
  <div class="cart-item">
    <div><input type="checkbox" checked></div>
    <div>
      <img src="images/fan.jpg" alt="Quạt Turbo J">
      <div>
        <div><strong>Goojodoq Official Shop.VN</strong></div>
      </div>
    </div>
    <div>
      <div class="price">500.000₫</div>
    </div>
    <div class="quantity-control">
      <button type="button">−</button>
      <input type="text" value="1" readonly>
      <button type="button">+</button>
    </div>
    <div class="price-new">349.000₫</div>
    <div class="action-links">
      <a href="#">Xóa</a>
      <a href="#">Tìm sản phẩm tương tự</a>
    </div>
  </div>

  <!-- Thêm các sản phẩm khác tương tự -->

</div>
