<?php

include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/dbh.class.php');

class ProjController extends Dbh {
    public function setProject($name, $ownerID, $orgID){
        $sql = "INSERT INTO projects (name, ownerID, orgID) VALUES (:name, :ownerID, :orgID);";
        $stmt = $this->connect()->prepare($sql);
        try {
            $stmt->execute([
                'name' => $name,
                'ownerID' => $ownerID,
                'orgID' => $orgID
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