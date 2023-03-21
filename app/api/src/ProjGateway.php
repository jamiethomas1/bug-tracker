<?php

class ProjGateway {
    private PDO $conn;

    public function __construct(Database $database)
    {
        $this->conn = $database->getConnection();
    }

    public function getAll(): array {
        $sql = "SELECT * FROM projects";
        $stmt = $this->conn->query($sql);
        $data = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $data[] = $row;
        }
        return $data;
    }

    public function create(array $data) {
        $sql = "INSERT INTO projects (name, ownerID, orgID, projID) VALUES (:name, :ownerID, :orgID, :projID)";
        $stmt = $this->conn->prepare($sql);

        try {
            $stmt->execute([
                'name' => $data["name"],
                'ownerID' => $data["ownerID"],
                'orgID' => $data["orgID"],
                'projID' => $data["projID"]
            ]);
        } catch (PDOException $e) {
            if (str_contains($e->getMessage(), "1062 Duplicate entry")) { 
                die("Duplicate organisation ID.");
            };
        }

        return $this->conn->lastInsertId();
    }

    public function get(string $id): array | false {
        $sql = "SELECT * FROM projects WHERE projID = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            'id' => $id
        ]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        return $data;
    }

    public function update(array $current, array $new): int {
        $sql = "UPDATE projects SET name = :name, ownerID = :ownerID, orgID = :orgID, projID = :projID WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(":name", $new['name'] ?? $current['name'], PDO::PARAM_STR);
        $stmt->bindValue(":ownerID", $new['ownerID'] ?? $current['ownerID'], PDO::PARAM_STR);
        $stmt->bindValue(":orgID", $new['orgID'] ?? $current['orgID'], PDO::PARAM_STR);
        $stmt->bindValue(":projID", $new['projID'] ?? $current['projID'], PDO::PARAM_STR);
        
        $stmt->bindValue(":id", $current['id'], PDO::PARAM_INT);

        $stmt->execute();
        return $stmt->rowCount();
    }

    public function delete(string $id): int {
        $sql = "DELETE FROM projects WHERE projID = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            'id' => $id
        ]);
        return $stmt->rowCount();
    }
}
