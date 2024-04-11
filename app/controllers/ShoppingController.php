<?php

class ShoppingController
{
    private $productModel;
    private $db;
    private $accountModel;
    public function __construct()
    {
        $this->db = (new Database())->getConnection();
        $this->productModel = new ProductModel($this->db);
        $this->accountModel = new AccountModel($this->db);
    }

    private function saveOrderToDatabase($name, $email, $address, $totalPrice, $accountId)
    {
        // Thực hiện lưu hóa đơn vào CSDL ở đây và trả về Id của hóa đơn mới tạo
        // Ví dụ sử dụng PDO:
        $stmt = $this->db->prepare("INSERT INTO `Order` (Name, Email, Address, TotalPrice, AccountId) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$name, $email, $address, $totalPrice, $accountId]);
        $orderId = $this->db->lastInsertId();
        
        return $orderId;
    }

    private function saveOrderDetailToDatabase($orderId, $productId, $quantity)
    {
        // Thực hiện lưu chi tiết hóa đơn vào CSDL ở đây, với $orderId, $productId, $quantity
        // Ví dụ sử dụng PDO:
        $stmt = $this->db->prepare("INSERT INTO `OrderDetail` (OrderId, ProductId, Quantity) VALUES (?, ?, ?)");
        $stmt->execute([$orderId, $productId, $quantity]);
    }

    public function listShoppingCart()
    {

        $stmt = $this->productModel->readAll();
        // Lấy thông tin giỏ hàng từ session hoặc database
        $accountId = $_SESSION['cart']['accountId'] ?? '';
        $totalPrice = $_SESSION['cart']['totalPrice'] ?? '';

        include_once 'app/views/shoppingCart/index.php';
    }

    public function checkOutInfo()
    {
        $stmt = $this->productModel->readAll();
        include_once 'app/views/checkOut/index.php';
    }
    

    function getUserIdByUsername($username) {
        $account = $this->accountModel->getAccountByUsername($username);
        if ($account) {
            return $account->id; // Trả về id của tài khoản nếu tồn tại
        } else {
            return null; // Trả về null nếu không tìm thấy tài khoản
        }
    }

    public function checkout()
    {
        // Kiểm tra xem có sản phẩm trong giỏ hàng không
        if (empty($_SESSION['cart'])) {
            // Redirect hoặc hiển thị thông báo nếu không có sản phẩm trong giỏ hàng
            return;
        }

        // Lấy thông tin thanh toán từ form
        $name = $_POST['name'] ?? '';
        $email = $_POST['email'] ?? '';
        $address = $_POST['address'] ?? '';


         // Lấy id của tài khoản từ session hoặc truy cập CSDL để lấy
        if (isset($_SESSION['username'])) {
            $username = $_SESSION['username'];
            $accountId = $this->getUserIdByUsername($username);
        }

        // Tính tổng giá tiền của đơn hàng
        $totalPrice = 0;
        foreach ($_SESSION['cart'] as $cartItem) {
            $totalPrice += $cartItem['product']->price * $cartItem['quantity'];
        }

        // Kiểm tra xem thông tin thanh toán có hợp lệ không
        if (empty($name) || empty($email) || empty($address)) {
            // Redirect hoặc hiển thị thông báo lỗi nếu thông tin không hợp lệ
            return;
        }

        // Thực hiện lưu đơn hàng vào CSDL
        $orderId = $this->saveOrderToDatabase($name, $email, $address, $totalPrice, $accountId);

        // Lưu chi tiết đơn hàng vào CSDL
        foreach ($_SESSION['cart'] as $productId => $cartItem) {
            $this->saveOrderDetailToDatabase($orderId, $productId, $cartItem['quantity']);
        }

        // Xóa thông tin giỏ hàng sau khi đã thanh toán
        unset($_SESSION['cart']);

        // Redirect hoặc hiển thị thông báo thành công
        include_once 'app/views/checkOut/success.php';
    }

    // Hành động thêm sản phẩm vào giỏ hàng
    public function addAction($productId)
    {
        // Giả sử đã có hàm getProductById để lấy thông tin sản phẩm từ cơ sở dữ liệu
        $product = $this->productModel->getProductById($productId);

        if (!$product) {
            // Redirect hoặc hiển thị thông báo lỗi nếu sản phẩm không tồn tại
            echo 'Error: Product not found';
            return;
        }

        // Kiểm tra xem giỏ hàng đã được khởi tạo chưa
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        // Kiểm tra xem sản phẩm đã có trong giỏ hàng chưa
        if (isset($_SESSION['cart'][$productId])) {
                // Nếu sản phẩm đã có trong giỏ hàng, tăng số lượng lên
                $_SESSION['cart'][$productId]['quantity']++;
        } else {
            // Nếu chưa, thêm sản phẩm vào giỏ hàng với số lượng là 1
            $_SESSION['cart'][$productId] = [
                'product' => $product,
                'quantity' => 1
            ];
        }
        exit; // Dừng thực thi để đảm bảo không có mã HTML nào được xuất ra sau lệnh include
    }

    public function addSuccess()
    {
        include_once 'app/views/shoppingCart/success.php';
    }

    public function thongke()
    {
        include_once 'app/views/statistical/index.php';
    }

    // Hành động tăng số lượng sản phẩm trong giỏ hàng
    public function increaseQuantityAction($productId)
    {
        // Kiểm tra xem sản phẩm có tồn tại trong giỏ hàng không
        if (isset($_SESSION['cart'][$productId])) {
            // Tăng số lượng sản phẩm lên 1
            $_SESSION['cart'][$productId]['quantity']++;
        }

        // Trở về trang giỏ hàng
    }

    // Hành động giảm số lượng sản phẩm trong giỏ hàng
    public function decreaseQuantityAction($productId)
    {
        // Kiểm tra xem sản phẩm có tồn tại trong giỏ hàng không
        if (isset($_SESSION['cart'][$productId])) {
            // Giảm số lượng sản phẩm đi 1
            $_SESSION['cart'][$productId]['quantity']--;

            // Kiểm tra xem số lượng sản phẩm có nhỏ hơn 1 không
            if ($_SESSION['cart'][$productId]['quantity'] < 1) {
                // Nếu nhỏ hơn 1, loại bỏ sản phẩm khỏi giỏ hàng
                unset($_SESSION['cart'][$productId]);
            }
        }

        // Trở về trang giỏ hàng
    }

    // Hành động cập nhật số lượng sản phẩm trong giỏ hàng
    public function updateQuantityAction($productId)
    {
        if (isset($_SESSION['cart'][$productId])) {
            // Lấy số lượng mới từ yêu cầu AJAX
            $newQuantity = $_POST['quantity'];
            // Cập nhật số lượng sản phẩm trong giỏ hàng
            $_SESSION['cart'][$productId]['quantity'] = $newQuantity;
            // Trả về kết quả là số lượng mới
            echo $newQuantity;
        } else {
            // Trả về lỗi nếu sản phẩm không tồn tại trong giỏ hàng
            echo 'Error: Product not found in cart.';
        }
    }

    public function updateCartAction()
    {
        // Đọc dữ liệu JSON được gửi từ phía máy khách
        $postData = file_get_contents('php://input');
        $products = json_decode($postData, true);

        // Kiểm tra dữ liệu được gửi
        if (!is_array($products) || empty($products)) {
            // Trả về lỗi nếu không có dữ liệu hoặc dữ liệu không hợp lệ
            http_response_code(400); // Bad Request
            echo json_encode(['message' => 'Invalid data']);
            return;
        }

        // Lặp qua các sản phẩm và cập nhật số lượng
        foreach ($products as $product) {
            $productId = $product['productId'];
            $newQuantity = $product['quantity'];
            // Cập nhật số lượng cho sản phẩm trong giỏ hàng
            $_SESSION['cart'][$productId]['quantity'] = $newQuantity;
        }

        // Trả về thông báo thành công
        echo json_encode(['message' => 'Cart updated successfully']);
    }

    public function removeAction($productId)
    {
        // Kiểm tra xem sản phẩm có tồn tại trong giỏ hàng không
        if (isset($_SESSION['cart'][$productId])) {
            // Xóa sản phẩm khỏi giỏ hàng
            unset($_SESSION['cart'][$productId]);
            // Trả về kết quả là 'success' để thông báo cho client biết rằng sản phẩm đã được xóa thành công
            echo 'success';
        } else {
            // Nếu sản phẩm không tồn tại trong giỏ hàng, trả về kết quả là 'error' để client biết rằng có lỗi xảy ra
            echo 'error';
        }
    }


    
}
