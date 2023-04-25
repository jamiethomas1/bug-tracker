<?php

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

include_once($_SERVER['DOCUMENT_ROOT'] . '/php-scripts/randomString.php');

class RegisterGateway {
    private PDO $conn;

    public function __construct(Database $database)
    {
        $this->conn = $database->getConnection();
    }

    public function signup(array $data) : int | false {
        $sql = "INSERT INTO users (email, name, password_hash, userID) VALUES (:email, :name, :password_hash, :userID)";
        $stmt = $this->conn->prepare($sql);

        $userID = getRandomString();

        try {
            $stmt->execute([
                'email' => htmlspecialchars($data["email"]),
                'name' => htmlspecialchars($data["username"]),
                'password_hash' => htmlspecialchars($data["password_hash"]),
                'userID' => htmlspecialchars($userID)
            ]);
        } catch (PDOException $e) {
            if (str_contains($e->getMessage(), "1062 Duplicate entry")) {
                die("Duplicate email.");
            };
        }

        return $this->conn->lastInsertId();
    }
}
