<?php
require_once 'app/models/OrderModel.php'; // Import file OrderModel.php

class OrderController {
    private $db;
    private $orderModel;

    public function __construct() {
        $this->db = (new Database())->getConnection();
        $this->orderModel = new OrderModel($this->db);
    }
   
    public function orderList() {
        // Lấy idAccount của người dùng từ session
        $accountId = $_SESSION['accountId'] ?? null;
        if (!$accountId) {
            // Xử lý khi idAccount không tồn tại (người dùng chưa đăng nhập)
            // Ví dụ: chuyển hướng đến trang đăng nhập
            header('Location: /chieu2/account/login');
            return;
        }
        
        $stmt = $this->orderModel->readAll($accountId);
        $orders = $stmt->fetchAll(PDO::FETCH_OBJ); // Lấy tất cả các hàng kết quả và chuyển đổi thành đối tượng
        include_once 'app/views/shoppingCart/order.php';
    }

    public function orderListDetail() {
        // Lấy idAccount của người dùng từ session
        $accountId = $_SESSION['accountId'] ?? null;
        if (!$accountId) {
            // Xử lý khi idAccount không tồn tại (người dùng chưa đăng nhập)
            // Ví dụ: chuyển hướng đến trang đăng nhập
            header('Location: /chieu2/account/login');
            return;
        }
        
        $stmt = $this->orderModel->readAll($accountId);
        $orders = $stmt->fetchAll(PDO::FETCH_OBJ); // Lấy tất cả các hàng kết quả và chuyển đổi thành đối tượng
        include_once 'app/views/checkOut/order_list_detail.php';
    }
}

