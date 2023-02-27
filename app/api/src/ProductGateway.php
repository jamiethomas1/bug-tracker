<?php

// Is this file necessary or can I just reference the userController class?
// Also how can I get rid of the ugly HTML errors?
class ProductGateway {
    private PDO $conn;

    public function __construct(Database $database)
    {
        $this->conn = $database->getConnection();
    }

    public function getAll(): array {
        $sql = "SELECT * FROM users";
        $stmt = $this->conn->query($sql);
        $data = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $data[] = $row;
        }
        return $data;
    }

    public function create(array $data) {
        $sql = "INSERT INTO users (email, name, password_hash, userID) VALUES (:email, :name, :password_hash, :userID)";
        $stmt = $this->conn->prepare($sql);

        try {
            $stmt->execute([
                'email' => $data["email"],
                'name' => $data["name"],
                'password_hash' => $data["password_hash"],
                'userID' => $data["userID"]
            ]);
        } catch (PDOException $e) {
            if (str_contains($e->getMessage(), "1062 Duplicate entry")) { 
                die("Duplicate email.");
            };
        }

        return $this->conn->lastInsertId();
    }
}