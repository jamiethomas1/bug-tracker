<?php

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

include_once("/app/php-scripts/randomString.php");

class LoginGateway {
    private PDO $conn;

    public function __construct(Database $database)
    {
        $this->conn = $database->getConnection();
    }

    public function authenticate(array $data) : string | false {
       // Need to check if $data is empty, and if it contains both email and password properties

        if (empty($data)
            || !array_key_exists("email", $data)
            || !array_key_exists("password", $data)) {
            return false;
        }

        $em = htmlspecialchars($data["email"]);
        $pw = htmlspecialchars($data["password"]);

        $user = $this->getUserByEmail($em);

        // Temporary. When the API is connected up to React frontend, the password will be hashed before
        // sending it in the API request. Then the server will simply check hash against hash.
        if ($user && password_verify($pw, $user['password_hash'])) {
            return $this->generateTokens($user);
        } else {
            return false;
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

    private function generateTokens($user) {
        // Access token
        $key = getenv("JWT_SIGNATURE_KEY");
        $payload = [
            'iat' => time(),
            'exp' => time() + 1800, // Expires 30 minutes after issue
            'sub' => $user["userID"]
        ];
        $jwt = JWT::encode($payload, $key, 'HS512');

        // Refresh token
        $refresh = getRandomString(64);
        $sql = "INSERT INTO refresh (userID, refreshToken, tokenStatus) VALUES (:userID, :refreshToken, :tokenStatus)";
        $stmt = $this->conn->prepare($sql);
        try {
            $stmt->execute([
                'userID' => $user["userID"],
                'refreshToken' => $refresh,
                'tokenStatus' => "active"
            ]);
        } catch (PDOException $e) {
            die($e);
        }

        $refresh_key = getenv("REFRESH_TOKEN_KEY");
        $refresh_payload = [
            'userID' => $user['userID'],
            'secret' => $refresh
        ];
        $refresh_encoded = JWT::encode($refresh_payload, $refresh_key, 'HS512');

        $user['access_token'] = $jwt;
        $user['refresh_token'] = $refresh_encoded;       

        return json_encode($user);
    }
}
