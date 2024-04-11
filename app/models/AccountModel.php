<?php
class AccountModel{
    private $conn;
    private $table_name = "account";

    public function __construct($db) {
        $this->conn = $db;
    }

    function getAllUsers() {
        $query = "SELECT * FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $users = $stmt->fetchAll(PDO::FETCH_OBJ);
        return $users;
    }

    function getAccountByUsername($email){
        $query = "SELECT * FROM " . $this->table_name . " where email = :email";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":email", $email);

        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_OBJ);
        return $result;
    }

    function save($username, $name, $password, $role="user"){

        $query = "INSERT INTO " . $this->table_name . " (email, name, password, role) VALUES (:username, :name, :password, :role)";
        
        $stmt = $this->conn->prepare($query);

        // Làm sạch dữ liệu
        $name = htmlspecialchars(strip_tags($name));
        $username = htmlspecialchars(strip_tags($username));

        // Gán dữ liệu vào câu lệnh
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password', $password);
        $stmt->bindParam(':role', $role);

        // Thực thi câu lệnh
        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    function updateUserRole($userId, $role){
        $query = "UPDATE " . $this->table_name . " SET role = :role WHERE id = :userId";
        
        $stmt = $this->conn->prepare($query);
    
        $stmt->bindParam(':userId', $userId);
        $stmt->bindParam(':role', $role);
    
        if ($stmt->execute()) {
            return true;
        }
    
        return false;
    }

    public function updateAvatar($userId, $avatar) {
        try {
            $query = "UPDATE account SET avatar = :avatar WHERE id = :userId";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":userId", $userId);
            $stmt->bindParam(":avatar", $avatar);
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            // Xử lý lỗi nếu có
            return false;
        }
    }    
    
}