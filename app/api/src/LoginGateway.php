<?php

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class LoginGateway {
    private PDO $conn;

    public function __construct(Database $database)
    {
        $this->conn = $database->getConnection();
    }

    public function oldauthenticate(array $data) {
        $sql = "INSERT INTO orgs (name, ownerID, orgID) VALUES (:name, :ownerID, :orgID)";
        $stmt = $this->conn->prepare($sql);

        try {
            $stmt->execute([
                'name' => $data["name"],
                'ownerID' => $data["ownerID"],
                'orgID' => $data["orgID"]
            ]);
        } catch (PDOException $e) {
            if (str_contains($e->getMessage(), "1062 Duplicate entry")) { 
                die("Duplicate organisation ID.");
            };
        }

        return $this->conn->lastInsertId();
    }

    public function authenticate(array $data) {
       // Need to check if $data is empty, and if it contains both email and password properties

        if (empty($data)
            || !array_key_exists("email", $data)
            || !array_key_exists("password", $data)) {
            return false;
        }

        $em = htmlspecialchars($data["email"]);
        $pw = htmlspecialchars($data["password"]);

        $user = $this->getUserByEmail($em);

        if ($user) {
            if (password_verify($pw, $user['password_hash'])) {
                return $this->generateToken($user);
            }
        }
    }

    private function getUserByEmail($em) {
        $sql = "SELECT * FROM users WHERE email = '$em'";
        $stmt = $this->conn->prepare($sql);
        try {
            $stmt->execute();
        } catch (PDOException $e) {
            die($e);
        }
        return $stmt->fetch();
    }

    private function generateToken($user) {
        $key = getenv("JWT_SIGNATURE_KEY");
        $payload = [
            'iat' => time(),
            'sub' => $user["userID"]
        ];
        $jwt = JWT::encode($payload, $key, 'HS256');
        return $jwt;
    }
}
