<?php
class OrderModel{
    private $conn;
    private $table_name = 'order';

    public function __construct($db) {
        $this->conn = $db;
    }

    function readAll($accountId) {
        $query = "SELECT id, name, email, address, totalPrice, createdAt FROM `order` WHERE accountId = :accountId";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':accountId', $accountId);
        $stmt->execute();
        return $stmt;
    }    
    
}
