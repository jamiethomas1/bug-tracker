<?php

class RegisterController {
    public function __construct(private RegisterGateway $gateway)
    {
        
    }

    public function processRequest(string $method): void {
        $this->processCollectionRequest($method);
    }

    private function processCollectionRequest(string $method): void {
        switch ($method) {
            case "POST":
                $data = (array) json_decode(file_get_contents("php://input"), true);

                $errors = $this->getValidationErrors($data);

                if (!empty($errors)) {
                    http_response_code(422);
                    echo json_encode(["errors" => $errors]);
                    break;
                }

                $id = $this->gateway->signup($data);
                if ($id !== false) {
                    http_response_code(200);
                    echo json_encode([
                        "message" => "Account created",
                        "id" => $id
                    ]);
                } else {
                    http_response_code(500);
                    echo json_encode([
                        "message" => "Failed to create account"
                    ]);
                }

                break;
            default:
                http_response_code(405);
                header("Allow: POST");
        }
    }

    private function getValidationErrors(array $data, bool $is_new = true): array {
        $errors = [];

        if ($is_new) {
            if (empty($data["username"])) {
                $errors[] = "username is required";
            }
            if (empty($data["email"])) {
                $errors[] = "email is required";
            }
            if (!filter_var($data["email"], FILTER_VALIDATE_EMAIL)) {
               $errors[] = "invalid email";
            }
            if (empty($data["password"])) {
                $errors[] = "password is required";
            }
            if (!preg_match("/[a-z]/i", $data['password'])) {
                $errors[] = "password must contain at least one letter";
            }
            if (!preg_match("/[0-9]/", $data['password'])) {
                $errors[] = "password must contain at least one number";
            }
        }

        return $errors;
    }
}
