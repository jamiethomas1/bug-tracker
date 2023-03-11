<?php

// Is this file necessary or can I just reference the userController class?
// Also how can I get rid of the ugly HTML errors?
class UserGateway {
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

    public function get(string $id): array | false {
        $sql = "SELECT * FROM users WHERE userID = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            'id' => $id
        ]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        return $data;
    }

    public function update(array $current, array $new): int {
        $sql = "UPDATE users SET email = :email, name = :name, password_hash = :password_hash, userID = :userID WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(":email", $new['email'] ?? $current['email'], PDO::PARAM_STR);
        $stmt->bindValue(":name", $new['name'] ?? $current['name'], PDO::PARAM_STR);
        $stmt->bindValue(":password_hash", $new['password_hash'] ?? $current['password_hash'], PDO::PARAM_STR);
        $stmt->bindValue(":userID", $new['userID'] ?? $current['userID'], PDO::PARAM_STR);
        
        $stmt->bindValue(":id", $current['id'], PDO::PARAM_INT);

        $stmt->execute();
        return $stmt->rowCount();
    }

    public function delete(string $id): int {
        $sql = "DELETE FROM users WHERE userID = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            'id' => $id
        ]);
        return $stmt->rowCount();
    }
}