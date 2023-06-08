<?php

class CheckUserController {
    public function __construct(private CheckUserGateway $gateway)
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

                $success = $this->gateway->checkUser($data);
                if ($success) {
                    http_response_code(200);
                    echo json_encode([
                        "message" => "User found",
                        "id" => $data["id"]
                    ]);
                } else {
                    http_response_code(500);
                    echo json_encode([
                        "message" => "An error occurred while checking if user exists"
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
            if (empty($data["name"])) {
                $errors[] = "name is required";
            }
            if (empty($data["email"])) {
                $errors[] = "email is required";
            }
            if (!filter_var($data["email"], FILTER_VALIDATE_EMAIL)) {
               $errors[] = "invalid email";
            }
            if (empty($data["id"])) {
                $errors[] = "id is required";
            }
            if (empty($data["image"])) {
                $errors[] = "image is required";
            }
        }

        return $errors;
    }
}
