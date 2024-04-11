<?php
include_once 'app/views/share/header.php';
?>

<!-- Hiển thị các sản phẩm trong giỏ hàng -->
<div class="container">
    <h1 class="mt-4">Giỏ hàng</h1>
    <?php if (isset($_SESSION['cart']) && is_array($_SESSION['cart']) && count($_SESSION['cart']) > 0) : ?>
        <table class="table table-bordered table-striped">
            <thead class="thead-dark">
                <tr>
                    <th class="col-md-1">Id</th>
                    <th class="col-md-3">Name</th>
                    <th class="col-md-2">Price</th>
                    <th class="col-md-2">Quantity</th>
                    <th class="col-md-2">Total</th>
                    <th class="col-md-2">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($_SESSION['cart'] as $productId => $cartItem) : ?>
                    <tr id="product_<?= $productId ?>">
                        <td><?= $cartItem['product']->id ?></td>
                        <td><?= $cartItem['product']->name ?></td>
                        <td><?= number_format($cartItem['product']->price) ?> đ</td>
                        <td>
                            <input type="number" class="form-control quantity" id="quantity_<?= $productId ?>" value="<?= $cartItem['quantity'] ?>">
                        </td>
                        <td><?= number_format($cartItem['product']->price * $cartItem['quantity']) ?> đ</td>
                        <td>
                            <button class="btn btn-sm btn-danger mr-2" onclick="removeFromCart(<?= $productId ?>)">Xóa</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div class="text-right">
            <button class="btn btn-sm btn-primary mr-2" id="updateBtn" style="display:none;" onclick="updateCart()">Cập nhật giỏ hàng</button>
            <a class="btn btn-sm btn-primary mr-2" href="/chieu2/Order/orderList">Tiến hành thanh toán</a>
            <a class="btn btn-sm btn-secondary" href="/chieu2/">Trở về trang sản phẩm</a>
        </div>

    <?php else : ?>
        <h3 class="mt-4">Giỏ hàng của bạn đang trống.</h3>
        <div class="d-flex justify-content-center align-items-center mt-3">
            <a href="/chieu2/" class="btn btn-secondary">Trở về trang chủ</a>
        </div>
    <?php endif; ?>
</div>

<?php
include_once 'app/views/share/footer.php';
?>


<script>
    // Lắng nghe sự kiện khi sửa quantity
    document.querySelectorAll('.quantity').forEach(function(input) {
        input.addEventListener('input', function() {
            // Hiển thị nút cập nhật
            document.getElementById('updateBtn').style.display = 'inline-block';
        });
    });

    function updateCart() {
        var products = [];
        // Lặp qua tất cả các sản phẩm trong giỏ hàng để lấy số lượng mới
        <?php foreach ($_SESSION['cart'] as $productId => $cartItem) : ?>
            var productId = <?= $productId ?>;
            var newQuantity = document.getElementById('quantity_' + productId).value;
            // Kiểm tra nếu số lượng mới không hợp lệ
            if (newQuantity <= 0) {
                alert('Quantity must be greater than 0.');
                return;
            }
            // Lưu thông tin sản phẩm vào mảng
            products.push({
                productId: productId,
                quantity: newQuantity
            });
        <?php endforeach; ?>

        // Gửi yêu cầu AJAX để cập nhật số lượng của tất cả các sản phẩm
        var xhr = new XMLHttpRequest();
        xhr.open('POST', '/chieu2/Shopping/UpdateCartAction', true);
        xhr.setRequestHeader('Content-type', 'application/json');
        xhr.onload = function() {
            if (xhr.status == 200) {
                // Nếu cập nhật thành công, làm gì đó (ví dụ: hiển thị thông báo)
                alert('Cart updated successfully!');
                // Ẩn nút cập nhật
                document.getElementById('updateBtn').style.display = 'none';
                // Sau khi cập nhật, có thể cần load lại trang để cập nhật giao diện người dùng
                window.location.reload();
            } else {
                console.log('Error: ' + xhr.status);
            }
        };
        // Gửi dữ liệu dưới dạng JSON
        xhr.send(JSON.stringify(products));
    }

    function removeFromCart(productId) {
        // Gửi yêu cầu AJAX đến hành động xóa sản phẩm
        var xhr = new XMLHttpRequest();
        xhr.open('POST', '/chieu2/Shopping/removeAction/' + productId, true);
        xhr.onload = function() {
            if (xhr.status == 200 && xhr.responseText == 'success') {
                // Nếu sản phẩm đã được xóa thành công, làm mới trang để cập nhật giỏ hàng
                window.location.reload();
            } else {
                // Nếu có lỗi xảy ra hoặc sản phẩm không tồn tại trong giỏ hàng, hiển thị thông báo lỗi
                alert('Failed to remove product from cart.');
            }
        };
        xhr.send();
    }

</script>