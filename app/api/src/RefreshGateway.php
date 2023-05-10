<?php

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

include_once("/app/php-scripts/randomString.php");

class RefreshGateway {
    private PDO $conn;

    public function __construct(Database $database)
    {
        $this->conn = $database->getConnection();
    }

    public function authenticate(array $data) : string | false {
       // Need to check if $data is empty, and if it contains both email and password properties

        $refresh_key = getenv("REFRESH_TOKEN_KEY");
        $refresh_encoded = $data;
        $refresh = JWT::decode($refresh_encoded, new Key($refresh_key, 'HS256'));

        $user = $this->getUserById($data['userID']);

        // Temporary. When the API is connected up to React frontend, the password will be hashed before
        // sending it in the API request. Then the server will simply check hash against hash.
        if ($user && $this->getRefreshToken($data['secret'])) {
            return $this->generateToken($user);
        } else {
            return false;
        }
    }

    private function getUserById($id) {
        $sql = "SELECT * FROM users WHERE userID = '$id'";
        $stmt = $this->conn->prepare($sql);
        try {
            $stmt->execute();
        } catch (PDOException $e) {
            die($e);
        }
        return $stmt->fetch();
    }

    private function getRefreshToken($token) {
        $sql = "SELECT * FROM refresh WHERE refreshToken = '$token'";
        $stmt = $this->conn->prepare($sql);
        try {
            $stmt->execute();
        } catch (PDOException $e) {
            die($e);
        }
        return $stmt->fetch();
    }

    private function generateToken($user) {
        // Access token
        $key = getenv("JWT_SIGNATURE_KEY");
        $payload = [
            'iat' => time(),
            'exp' => time() + 1800, // Expires 30 minutes after issue
            'sub' => $user["userID"]
        ];
        $jwt = JWT::encode($payload, $key, 'HS256');
    }
}
