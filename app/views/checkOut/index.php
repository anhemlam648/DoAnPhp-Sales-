<?php include_once 'app/views/share/header.php'; ?>

<div class="container">
    <h1 class="text-center">Thông tin thanh toán</h1>
    <div class="row justify-content-center">
        <div class="col-md-6">
            <form id="checkoutForm" action="/chieu2/Shopping/checkOut" method="post">
                <div class="form-group">
                    <label for="name">Họ và tên:</label>
                    <input class="form-control" id="name" name="name">
                    <div id="nameError" class="invalid-feedback"></div>
                </div>
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input class="form-control" id="email" name="email">
                    <div id="emailError" class="invalid-feedback"></div>
                </div>
                <div class="form-group">
                    <label for="address">Địa chỉ:</label>
                    <input class="form-control" id="address" name="address">
                    <div id="addressError" class="invalid-feedback"></div>
                </div>
                <div id="paypal-button-container" class="text-center mb-3"></div>
                <!-- Lấy giá trị tổng tiền từ session -->
                <input type="hidden" id="totalPrice" name="totalPrice" value="<?php echo $_SESSION['order']['totalPriceUSD']; ?>">
                <input type="hidden" id="items" name="items" value='<?php echo htmlspecialchars(json_encode($_SESSION['order']['items'])); ?>'>
            </form>
        </div>
    </div>
</div>

<script src="https://www.paypal.com/sdk/js?client-id=ARkoc6hB5AcYB56RLHQ3yujDiDHzjgJBJXW-ss1Uub9sRPwqO0amqU9QVm1Qwi1fX_cCf9GgPennkwG_"></script>
<script>
    paypal.Buttons({
    createOrder: function(data, actions) {
        // Kiểm tra validation trước khi tạo đơn đặt hàng
        if (!validateForm()) {
            return false; // Dừng việc tạo đơn đặt hàng nếu có lỗi validation
        }

        var itemsValue = document.getElementById('items').value;
        var totalPriceVND = parseFloat(document.getElementById('totalPrice').value);
        var items = JSON.parse(itemsValue);
        var exchangeRate = 0.000040; // Tỷ giá hối đoái từ VND sang USD

        var purchaseUnits = [];
        items.forEach(function(item, index) {
            var totalPriceUSD = (item.price * item.quantity * exchangeRate).toFixed(2);
            var reference_id = 'PU' + (index + 1);
            purchaseUnits.push({
                reference_id: reference_id,
                amount: {
                    currency_code: 'USD',
                    value: totalPriceUSD,
                    breakdown: {
                        item_total: {
                            currency_code: 'USD',
                            value: totalPriceUSD
                        }
                    }
                },
                items: [{
                    name: item.name,
                    unit_amount: {
                        currency_code: 'USD',
                        value: (item.price * exchangeRate).toFixed(2)
                    },
                    quantity: item.quantity
                }]
            });
        });

        return actions.order.create({
            purchase_units: purchaseUnits
        });
    },
    onApprove: function(data, actions) {
        return actions.order.capture().then(function(details) {
            document.getElementById('checkoutForm').submit();
        });
    }
}).render('#paypal-button-container');
</script>
<!-- // Testpaypal -->
// <!-- <script src="https://www.paypal.com/sdk/js?client-id=ARkoc6hB5AcYB56RLHQ3yujDiDHzjgJBJXW-ss1Uub9sRPwqO0amqU9QVm1Qwi1fX_cCf9GgPennkwG_"></>
// <script>
//   paypal.Buttons({
//     createOrder: function(data, actions) {
//       return actions.order.create({
//         purchase_units: [{
//           amount: {
//             value: document.getElementById('totalPrice').value // Giá trị tổng tiền từ biến tổng giá trị
//           }
//         }]
//       });
//     },
//     onApprove: function(data, actions) {
//       return actions.order.capture().then(function(details) {
//         // Gửi dữ liệu đơn hàng đến máy chủ của bạn để xác nhận thanh toán
//         document.getElementById('checkoutForm').submit(); // Đưa ra submit form khi thanh toán thành công
//       });
//     }
//   }).render('#paypal-button-container');
// </script> -->
<script>
// Hàm kiểm tra validation của form
function validateForm() {
    var name = document.getElementById('name').value.trim();
    var email = document.getElementById('email').value.trim();
    var address = document.getElementById('address').value.trim();

    var nameField = document.getElementById('name');
    var emailField = document.getElementById('email');
    var addressField = document.getElementById('address');

    var nameError = document.getElementById('nameError');
    var emailError = document.getElementById('emailError');
    var addressError = document.getElementById('addressError');

    nameError.textContent = '';
    emailError.textContent = '';
    addressError.textContent = '';

    var isValid = true;

    if (name === '') {
        nameError.textContent = 'Vui lòng nhập họ và tên';
        nameField.classList.add('is-invalid');
        isValid = false;
    } else {
        nameField.classList.remove('is-invalid');
    }

    if (email === '') {
        emailError.textContent = 'Vui lòng nhập địa chỉ email';
        emailField.classList.add('is-invalid');
        isValid = false;
    } else if (!isValidEmail(email)) {
        emailError.textContent = 'Email không hợp lệ';
        emailField.classList.add('is-invalid');
        isValid = false;
    } else {
        emailField.classList.remove('is-invalid');
    }

    if (address === '') {
        addressError.textContent = 'Vui lòng nhập địa chỉ';
        addressField.classList.add('is-invalid');
        isValid = false;
    } else {
        addressField.classList.remove('is-invalid');
    }

    return isValid;
}

// Hàm kiểm tra hợp lệ của email
function isValidEmail(email) {
    var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
}

</script>

<?php include_once 'app/views/share/footer.php'; ?>
