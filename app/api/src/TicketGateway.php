<?php

class TicketGateway {
    private PDO $conn;

    public function __construct(Database $database)
    {
        $this->conn = $database->getConnection();
    }

    public function getAll(): array {
        $sql = "SELECT * FROM tickets";
        $stmt = $this->conn->query($sql);
        $data = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $data[] = $row;
        }
        return $data;
    }

    public function create(array $data) {
      $sql = "INSERT INTO tickets (name, body, ownerID, orgID, projID, ticketID) 
        VALUES (:name, :body, :ownerID, :orgID, :projID, :ticketID)";
        $stmt = $this->conn->prepare($sql);

        try {
            $stmt->execute([
                'name' => $data["name"],
                'body' => $data["body"],
                'ownerID' => $data["ownerID"],
                'orgID' => $data["orgID"],
                'projID' => $data["projID"],
                'ticketID' => $data["ticketID"]
            ]);
        } catch (PDOException $e) {
            if (str_contains($e->getMessage(), "1062 Duplicate entry")) { 
                die("Duplicate organisation ID.");
            };
        }

        return $this->conn->lastInsertId();
    }

    public function get(string $id): array | false {
        $sql = "SELECT * FROM tickets WHERE ticketID = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            'id' => $id
        ]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        return $data;
    }

    public function update(array $current, array $new): int {
      $sql = "UPDATE tickets 
        SET name = :name, body = :body, ownerID = :ownerID, orgID = :orgID, projID = :projID, ticketID = :ticketID 
        WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(":name", $new['name'] ?? $current['name'], PDO::PARAM_STR);
        $stmt->bindValue(":body", $new['body'] ?? $current['body'], PDO::PARAM_STR);
        $stmt->bindValue(":ownerID", $new['ownerID'] ?? $current['ownerID'], PDO::PARAM_STR);
        $stmt->bindValue(":orgID", $new['orgID'] ?? $current['orgID'], PDO::PARAM_STR);
        $stmt->bindValue(":projID", $new['projID'] ?? $current['projID'], PDO::PARAM_STR);
        $stmt->bindValue(":ticketID", $new['ticketID'] ?? $current['ticketID'], PDO::PARAM_STR);
        
        $stmt->bindValue(":id", $current['id'], PDO::PARAM_INT);

        $stmt->execute();
        return $stmt->rowCount();
    }

    public function delete(string $id): int {
        $sql = "DELETE FROM tickets WHERE ticketID = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            'id' => $id
        ]);
        return $stmt->rowCount();
    }
}
