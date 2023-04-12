<?php

class LoginController {
    public function __construct(private LoginGateway $gateway)
    {
        
    }

    public function processRequest(string $method, ?string $id): void {
        if ($id) {
            $this->processResourceRequest($method, $id);
        } else {
            $this->processCollectionRequest($method);
        }
    }

    private function processResourceRequest(string $method, string $id): void {
        http_response_code(405);
        header("Allow:");
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

                $token = $this->gateway->authenticate($data);
                if ($token !== false) {
                    http_response_code(200);
                    echo json_encode([
                        "message" => "Login successful",
                        "token" => $token
                    ]);
                } else {
                    http_response_code(401);
                    echo json_encode([
                        "message" => "Login failed"
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
            if (empty($data["email"])) {
                $errors[] = "email is required";
            }
            if (empty($data["password"])) {
                $errors[] = "password is required";
            }
        }

        return $errors;
    }
}
