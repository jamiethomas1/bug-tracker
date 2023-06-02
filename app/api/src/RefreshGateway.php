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
            $refresh = (array) JWT::decode($refresh_encoded, new Key($refresh_key, 'HS512'));
        } catch (Exception $e) {
            return false;
        }

        $user = $this->getUserById($userID);

        // $requestedToken contains the entire row of the refresh table relevant to the given token
        $requestedToken = $this->getRefreshToken($refresh['secret']);

        // Checking if token exists in db, if user exists, and if user and token match
        if ($requestedToken && $user && $user['userID'] == $requestedToken['userID']) {
            // Check if refresh token needs to be expired
            if ($requestedToken['tokenStatus'] == "active" && strtotime($requestedToken['dt']) < (time() - 15778463)) {
                    $this->setTokenStatus($requestedToken['refreshToken'], "expired");
                    return false;
                } else if ($requestedToken['tokenStatus'] == "expired") { // Check if token is expired
                    return false;
                }
            // All being well, generate token
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

    private function setTokenStatus($token, $status) {
        $sql = "UPDATE refresh SET tokenStatus = :status WHERE refreshToken = :token";
        $stmt = $this->conn->prepare($sql);
        try {
            $stmt->execute([
                'token' => $token,
                'status' => $status
            ]);
        } catch (PDOException $e) {
            die($e);
        }
    }

    private function generateToken() {
        // Access token
        $key = getenv("JWT_SIGNATURE_KEY");
        $payload = [
            'iat' => time(),
            'exp' => time() + 1800, // Expires 30 minutes after issue
        ];
        $jwt = JWT::encode($payload, $key, 'HS512');
        return $jwt;
    }
}
