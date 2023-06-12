<?php

class CheckUserGateway {
    private PDO $conn;

    public function __construct(Database $database)
    {
        $this->conn = $database->getConnection();
    }

    public function checkUser(array $data) : bool {
        $user = $this->getUserByID($data);
        if (!$user) {
            $this->createUser($data);
        }
        return true;
    }

    private function getUserByID(array $data) : array | false {
        $sql = "SELECT * FROM users WHERE userID = :id";
        $stmt = $this->conn->prepare($sql);
        try {
            $stmt->execute([
                'id' => $data["id"]
            ]);
        } catch (PDOException $e) {
            die($e);
        }
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        return $data;
    }

    private function createUser(array $data) {
        $sql = "INSERT INTO users (email, name, image, userID) VALUES (:email, :name, :image, :userID)";
        $stmt = $this->conn->prepare($sql);

        try {
            $stmt->execute([
                'email' => $data["email"],
                'name' => $data["name"],
                'image' => $data["image"],
                'userID' => $data["id"]
            ]);
        } catch (PDOException $e) {
            if (str_contains($e->getMessage(), "1062 Duplicate entry")) { 
                die("Duplicate email.");
            };
        }
    }

}
