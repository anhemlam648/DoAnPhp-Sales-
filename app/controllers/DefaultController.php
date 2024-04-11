<?php
class DefaultController{
    private $productModel;
    private $db;

    public function __construct()
    {
        $this->db = (new Database())->getConnection();
        $this->productModel = new ProductModel($this->db);
    }
    public function Index() {

        if(!Auth::isAdmin() && !Auth::isLoggedIn()){
            header('Location: /chieu2/account/login');
        }
        
        $products = $this->productModel->readAll();

        if(Auth::isAdmin()) {
            include_once 'app/views/share/index.php';
        } else {
            // Nếu không phải admin, hiển thị trang chung cho người dùng
            include_once 'app/views/share/index.php';
        }
    }
}