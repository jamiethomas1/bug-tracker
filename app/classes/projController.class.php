<?php

include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/dbh.class.php');

class ProjController extends Dbh {
    public function setProject($name, $ownerID, $orgID, $projID){
        $sql = "INSERT INTO projects (name, ownerID, orgID, projID) VALUES (:name, :ownerID, :orgID, :projID);";
        $stmt = $this->connect()->prepare($sql);
        try {
            $stmt->execute([
                'name' => $name,
                'ownerID' => $ownerID,
                'orgID' => $orgID,
                'projID' => $projID
            ]);
        } catch (PDOException $e) {
            die("PDO Error: " . $e->getMessage());
        }
    }

    public function getProjects($orgID) {
        $sql = "SELECT * FROM projects WHERE orgID = '$orgID'";
        $stmt = $this->connect()->prepare($sql);
        try {
            $stmt->execute();
        } catch (PDOException $e) {
            die($e);
        }
        return $stmt->fetchAll();
    }
}