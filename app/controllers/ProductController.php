<?php
class ProductController
{

    private $productModel;  
    private $db;

    public function __construct()
    {
        $this->db = (new Database())->getConnection();
        $this->productModel = new ProductModel($this->db);
    }

    public function add()
    {
        include_once 'app/views/product/add.php';
    }

    public function save()
    {
        //lưu sản phẩm vào CSDL, kèm upload hình ảnh lên thư mục uploads/ của server
        //cập nhật tên đường dẫn hình ảnh vào cột image của bảng Product
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $name = $_POST['name'] ?? '';
            $description = $_POST['description'] ?? '';
            $price = $_POST['price'] ?? '';

            if (isset($_POST['id'])) {
                //update
                $id = $_POST['id'];
            }

            $uploadResult = false;
            //kiểm tra để lưu hình ảnh
            if (!empty($_FILES["image"]['size'])) {
                //luu hinh
                $uploadResult = $this->uploadImage($_FILES["image"]);
            }

            //lưu sản phẩm
            if (!isset($id))
            // thêm sản phẩm 
                $result = $this->productModel->createProduct($name, $description, $price, $uploadResult);
            else
            // update sản phẩm 
                $result = $this->productModel->updateProduct($id, $name, $description, $price, $uploadResult);

            if (is_array($result)) {
                // Có lỗi, hiển thị lại form với thông báo lỗi
                $errors = $result;
                include 'app/views/product/add.php';
            } else {
                // Không có lỗi, chuyển hướng ve trang chu hoac trang danh sach
                header('Location: /chieu2/');
            }
        }
    }

    //hàm upload hình ảnh lên thư mục uploads của server
    public function uploadImage($file)
    {
        $targetDirectory = "uploads/";
        $targetFile = $targetDirectory . basename($file["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

        // Kiểm tra xem file có phải là hình ảnh thực sự hay không
        $check = getimagesize($file["tmp_name"]);
        if ($check !== false) {
            $uploadOk = 1;
        } else {
            $uploadOk = 0;
        }

        // Kiểm tra kích thước file
        if ($file["size"] > 500000) { // Ví dụ: giới hạn 500KB
            $uploadOk = 0;
        }

        // Kiểm tra định dạng file
        if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
            $uploadOk = 0;
        }

        // Kiểm tra nếu $uploadOk bằng 0
        if ($uploadOk == 0) {
            return false;
        } else {
            if (move_uploaded_file($file["tmp_name"], $targetFile)) {
                //đường dẫn của file hình
                return $targetFile;
            } else {
                //không upload được hình
                return false;
            }
        }
    }


    public function edit($id)
    {

        $product = $this->productModel->getProductById($id);

        if (empty($product)) {
            include_once 'app/views/share/not-found.php';
        } else {
            include_once 'app/views/product/edit.php';
        }
    }

    public function delete($id)
    {
        // Kiểm tra nếu không phải là admin
        if (!Auth::isAdmin()) {
            // Nếu không phải admin, chuyển hướng người dùng đến trang báo lỗi hoặc trang không có quyền truy cập
            include_once 'app/views/errors/access_denied.php'; 
            exit; // Dừng việc thực thi mã
        }

        // Gọi phương thức deleteProduct từ ProductModel để xóa sản phẩm
        $result = $this->productModel->deleteProduct($id);

        // Kiểm tra kết quả xóa
        if ($result) {
            // Kiểm tra xem sản phẩm đã tồn tại trong giỏ hàng không
            if (isset($_SESSION['cart'][$id])) {
                // Nếu sản phẩm tồn tại trong giỏ hàng, xóa nó khỏi giỏ hàng
                unset($_SESSION['cart'][$id]);
            }
            // Nếu xóa thành công, chuyển hướng người dùng về trang danh sách sản phẩm
            header('Location: /chieu2/');
        } else {
            // Nếu xóa không thành công, hiển thị thông báo lỗi
            echo "Error deleting product!";
        }
    }


}
