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

    public function authenticate(string $userID, string $refresh_encoded) : string | false {

        $refresh_key = getenv("REFRESH_TOKEN_KEY");
        try {
            $refresh = (array) JWT::decode($refresh_encoded, new Key($refresh_key, 'HS256'));
        } catch (Exception $e) {
            return false;
        }

        $user = $this->getUserById($userID);

        // Temporary. When the API is connected up to React frontend, the password will be hashed before
        // sending it in the API request. Then the server will simply check hash against hash.
        $requestedToken = $this->getRefreshToken($refresh['secret']);
        if ($requestedToken && $user && $user['userID'] == $requestedToken['userID']) {
            if ($requestedToken['tokenStatus'] == "active" && strtotime($requestedToken['dt'] > (time() - 15778463))) {
                    // Set to expired
                    return false;
                } else if ($requestedToken['tokenStatus'] == "expired") {
                    return false;
                }
            return $this->generateToken();
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

    private function generateToken() {
        // Access token
        $key = getenv("JWT_SIGNATURE_KEY");
        $payload = [
            'iat' => time(),
            'exp' => time() + 1800, // Expires 30 minutes after issue
        ];
        $jwt = JWT::encode($payload, $key, 'HS256');
        return $jwt;
    }
}
