<?php

// Is this file necessary or can I just reference the userController class?
// Also how can I get rid of the ugly HTML errors?
class OrgGateway {
    private PDO $conn;

    public function __construct(Database $database)
    {
        $this->conn = $database->getConnection();
    }

    public function getAll(): array {
        $sql = "SELECT * FROM orgs";
        $stmt = $this->conn->query($sql);
        $data = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $data[] = $row;
        }
        return $data;
    }

    public function create(array $data) {
        $sql = "INSERT INTO orgs (name, ownerID, orgID) VALUES (:name, :ownerID, :orgID)";
        $stmt = $this->conn->prepare($sql);

        try {
            $stmt->execute([
                'name' => $data["name"],
                'ownerID' => $data["ownerID"],
                'orgID' => $data["orgID"]
            ]);
        } catch (PDOException $e) {
            if (str_contains($e->getMessage(), "1062 Duplicate entry")) { 
                die("Duplicate organisation ID.");
            };
        }

        return $this->conn->lastInsertId();
    }

    public function get(string $id): array | false {
        $sql = "SELECT * FROM orgs WHERE orgID = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            'id' => $id
        ]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        return $data;
    }

    public function update(array $current, array $new): int {
        $sql = "UPDATE orgs SET name = :name, ownerID = :ownerID, orgID = :orgID WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(":name", $new['name'] ?? $current['name'], PDO::PARAM_STR);
        $stmt->bindValue(":ownerID", $new['ownerID'] ?? $current['ownerID'], PDO::PARAM_STR);
        $stmt->bindValue(":orgID", $new['orgID'] ?? $current['orgID'], PDO::PARAM_STR);
        
        $stmt->bindValue(":id", $current['id'], PDO::PARAM_INT);

        $stmt->execute();
        return $stmt->rowCount();
    }

    public function delete(string $id): int {
        $sql = "DELETE FROM orgs WHERE orgID = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            'id' => $id
        ]);
        return $stmt->rowCount();
    }
}