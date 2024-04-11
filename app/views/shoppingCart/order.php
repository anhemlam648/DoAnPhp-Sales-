<?php
include_once 'app/views/share/header.php';
?>

<?php if (!empty($_SESSION['cart'])) : ?>
    <div class="container">
        <h1>Đơn hàng của bạn</h1>
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th class="col-md-1">Id</th>
                    <th class="col-md-3">Tên sản phẩm</th>
                    <th class="col-md-2">Giá</th>
                    <th class="col-md-2">Số lượng</th>
                    <th class="col-md-2">Tổng</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $exchangeRate = 0.000040;
                $totalPrice = 0;
                // Tạo một mảng để lưu các sản phẩm trong đơn hàng
                $orderItems = array();
                foreach ($_SESSION['cart'] as $productId => $cartItem) :
                    $subtotal = $cartItem['product']->price * $cartItem['quantity'];
                    $totalPrice += $subtotal;
                    // Thêm thông tin sản phẩm vào mảng
                    $orderItems[] = array(
                        'id' => $cartItem['product']->id,
                        'name' => $cartItem['product']->name,
                        'price' => $cartItem['product']->price,
                        'quantity' => $cartItem['quantity']
                    );
                ?>
                    <tr id="product_<?= $productId ?>">
                        <td><?= $cartItem['product']->id ?></td>
                        <td><?= $cartItem['product']->name ?></td>
                        <td><?= number_format($cartItem['product']->price, 0, ',', '.') ?> đ</td>
                        <td><?= $cartItem['quantity'] ?></td>
                        <td><?= number_format($subtotal, 0, ',', '.') ?> đ</td>
                    </tr>
                <?php endforeach;
                // Lưu thông tin đơn hàng vào session
                $_SESSION['order'] = array(
                    'totalPrice' => $totalPrice,
                    'totalPriceUSD' => $totalPrice * $exchangeRate,
                    'items' => $orderItems
                );
                ?>

                <tr>
                    <td colspan="4" class="text-right"><strong>Tổng cộng:</strong></td>
                    <td><?= number_format($totalPrice, 0, ',', '.') ?> đ</td>
                </tr>
            </tbody>
        </table>

        <a href="/chieu2/Shopping/checkOutInfo" class="btn btn-primary">Thanh toán</a>
    </div>
<?php else : ?>
    <div class="container">
        <p>Hiện chưa có đơn hàng nào.</p>
    </div>
<?php endif; ?>

<?php
include_once 'app/views/share/footer.php';
?>
