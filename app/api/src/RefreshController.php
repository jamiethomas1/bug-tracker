<?php

class RefreshController {
    public function __construct(private RefreshGateway $gateway)
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

                $jwt = $this->gateway->authenticate($data['refresh_token']);
                if ($jwt !== false) {
                    http_response_code(200);
                    echo json_encode([
                        "message" => "Session restored",
                        "token" => $jwt
                    ]);
                } else {
                    http_response_code(401);
                    echo json_encode([
                        "message" => "Please log in again"
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
            if (empty($data["refresh_token"])) {
                $errors[] = "refresh_token is required";
            }
        }

        return $errors;
    }
}
