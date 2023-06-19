<?php

class ResponseGateway {
    private PDO $conn;

    public function __construct(Database $database)
    {
        $this->conn = $database->getConnection();
    }

    public function getAll(): array {
        $sql = "SELECT * FROM responses";
        $stmt = $this->conn->query($sql);
        $data = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $data[] = $row;
        }
        return $data;
    }

    public function create(array $data) {
      $sql = "INSERT INTO responses (name, body, responseNum, userID, projID, ticketID, responseID) 
        VALUES (:name, :body, :responseNum, :userID, :projID, :ticketID, :responseID)";
        $stmt = $this->conn->prepare($sql);

        try {
            $stmt->execute([
                'name' => $data["name"],
                'body' => $data["body"],
                'responseNum' => (int) $data["responseNum"],
                'userID' => $data["userID"],
                'projID' => $data["projID"],
                'ticketID' => $data["ticketID"],
                'responseID' => $data["responseID"]
            ]);
        } catch (PDOException $e) {
            if (str_contains($e->getMessage(), "1062 Duplicate entry")) { 
                die("Duplicate response ID.");
            };
        }

        return $this->conn->lastInsertId();
    }

    public function get(string $id): array | false {
        $sql = "SELECT * FROM responses WHERE responseID = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            'id' => $id
        ]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        return $data;
    }

    public function update(array $current, array $new): int {
      $sql = "UPDATE responses 
        SET name = :name, body = :body, responseNum = :responseNum, userID = :userID, 
        projID = :projID, ticketID = :ticketID, responseID = :responseID 
        WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(":name", $new['name'] ?? $current['name'], PDO::PARAM_STR);
        $stmt->bindValue(":body", $new['body'] ?? $current['body'], PDO::PARAM_STR);
        $stmt->bindValue(":responseNum", $new['responseNum'] ?? $current['responseNum'], PDO::PARAM_INT);
        $stmt->bindValue(":userID", $new['userID'] ?? $current['userID'], PDO::PARAM_STR);
        $stmt->bindValue(":projID", $new['projID'] ?? $current['projID'], PDO::PARAM_STR);
        $stmt->bindValue(":ticketID", $new['ticketID'] ?? $current['ticketID'], PDO::PARAM_STR);
        $stmt->bindValue(":responseID", $new['responseID'] ?? $current['responseID'], PDO::PARAM_STR);
        
        $stmt->bindValue(":id", $current['id'], PDO::PARAM_INT);

        $stmt->execute();
        return $stmt->rowCount();
    }

    public function delete(string $id): int {
        $sql = "DELETE FROM responses WHERE responseID = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            'id' => $id
        ]);
        return $stmt->rowCount();
    }
}
