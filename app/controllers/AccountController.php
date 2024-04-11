<?php
class AccountController{

    private $db;
    private $accountModel;

    function __construct(){
        $this->db = (new Database())->getConnection();
        $this->accountModel = new AccountModel($this->db);
    }

    function login(){
        include_once 'app/views/account/login.php';
    }

    function register(){
        include_once 'app/views/account/register.php';
    }

    function save(){
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $username = $_POST['username'] ?? '';
            $fullName = $_POST['fullname'] ?? '';
            $password = $_POST['password'] ?? '';
            $confirmPassword = $_POST['confirmpassword'] ?? '';

            $errors =[];
            if(empty($username)){
                $errors['username'] = "Vui long nhap userName!";
            }
            if(empty($fullName)){
                $errors['fullname'] = "Vui long nhap fullName!";
            }
            if(empty($password)){
                $errors['password'] = "Vui long nhap password!";
            }
            if($password != $confirmPassword){
                $errors['confirmPass'] = "Mat khau va xac nhan chua dung";
            }
            //kiểm tra username đã được đăng ký chưa?
            $account = $this->accountModel->getAccountByUsername($username);

            if($account){
                $errors['account'] = "Tai khoan nay da co nguoi dang ky!";
            }
            
            if(count($errors) > 0){
                include_once 'app/views/account/register.php';
            }else{
                $password = password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);
                
                $result = $this->accountModel->save($username, $fullName, $password);
                
                if($result){
                    header('Location: /chieu2/account/login');
                }
            }
        }       
       
    }

    function getUserIdByUsername($username) {
        $account = $this->accountModel->getAccountByUsername($username);
        if ($account) {
            return $account->id; // Trả về id của tài khoản nếu tồn tại
        } else {
            return null; // Trả về null nếu không tìm thấy tài khoản
        }
    }
    
    function checkLogin(){

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $username = $_POST['username'] ?? '';
            $password = $_POST['password'] ?? '';

            $errors =[];
            if(empty($username)){
                $errors['username'] = "Vui long nhap userName!";
            }
            if(empty($password)){
                $errors['password'] = "Vui long nhap password!";
            }
            if(count($errors) > 0){
                include_once 'app/views/account/login.php';
            }
            
            $account = $this->accountModel->getAccountByUsername($username);
            
            if($account && password_verify($password, $account->password)){
                //dang nhap thanh cong
                //luu trang thai dang nhap
                $_SESSION['username'] = $account->email;
                $_SESSION['role'] = $account->role;
                $_SESSION['name'] = $account->name;
                $_SESSION['accountId'] = $account->id;
                $_SESSION['avatar'] = $account->avatar;

                header('Location: /chieu2/');
            }else{
                $errors['account'] = "Dang nhap that bai!";
                include_once 'app/views/account/login.php';
            }
        }
    }

    function logout(){
        
        unset($_SESSION['username']);
        unset($_SESSION['role']);
        unset($_SESSION['avatar']);
        header('Location: /chieu2/');
    }

    function profile(){
        include_once 'app/views/account/profile.php';
    }

    function userList() {
        // Kiểm tra quyền của người dùng
        if ($_SESSION['role'] == 'admin') {
            // Nếu là admin, lấy danh sách người dùng từ model
            $users = $this->accountModel->getAllUsers();
            // Load view để hiển thị danh sách người dùng
            include_once 'app/views/account/user_list.php';
        } else {
            // Nếu không phải admin, chuyển hướng hoặc hiển thị thông báo lỗi
            include_once 'app/views/errors/access_denied.php'; 
        }
    }

    function changeUserRole(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $userId = $_POST['userId'] ?? '';
            $role = $_POST['role'] ?? '';
    
            // Kiểm tra xem người dùng có quyền admin hoặc user không
            if (in_array($role, ['admin', 'user'])) {
                // Gọi phương thức trong AccountModel để cập nhật quyền của người dùng
                $result = $this->accountModel->updateUserRole($userId, $role);
                
                if($result){
                    // Nếu cập nhật thành công, làm gì đó (ví dụ: hiển thị thông báo)
                    // echo json_encode(['status' => 'success', 'message' => 'Quyền của người dùng đã được cập nhật thành công!']);
                    include_once 'app/views/account/success.php';
                } else {
                    // Nếu có lỗi xảy ra, trả về thông báo lỗi
                    echo json_encode(['status' => 'error', 'message' => 'Có lỗi xảy ra khi cập nhật quyền của người dùng!']);
                }
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Quyền không hợp lệ!']);
            }
        }
    }
    

    function uploadImage(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['avatar'])) {
            $file = $_FILES['avatar'];
            
            if ($file['error'] === UPLOAD_ERR_OK) {
                $tmpFilePath = $file['tmp_name'];
                $uploadDir = 'uploads/';
                $fileName = $file['name'];
                $newFileName = uniqid() . '_' . $fileName;
                $uploadFilePath = $uploadDir . $newFileName;
    
                if (move_uploaded_file($tmpFilePath, $uploadFilePath)) {
                    // Lưu đường dẫn tệp vào cơ sở dữ liệu
                    $userId = $_SESSION['accountId']; // Lấy ID của người dùng từ session
                    $result = $this->accountModel->updateAvatar($userId, $uploadFilePath);
    
                    if ($result) {
                        // Lưu đường dẫn tệp vào session để hiển thị trên trang profile
                        $_SESSION['avatar'] = $uploadFilePath;
                        
                        // Trả về đường dẫn của hình ảnh để hiển thị trên trang profile
                        // echo $uploadFilePath;
                        include_once 'app/views/account/successImg.php';
                    } else {
                        echo "Có lỗi khi lưu đường dẫn ảnh vào cơ sở dữ liệu!";
                    }
                } else {
                    include_once 'app/views/account/errorImg.php';
                }
            } else {
                include_once 'app/views/account/errorImg.php';
            }
        }
    }    
}