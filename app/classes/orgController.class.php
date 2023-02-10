<?php

include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/dbh.class.php');

class OrgController extends Dbh {
    public function setOrganisation($name, $ownerID){
        $sql = "INSERT INTO orgs (name, ownerID) VALUES (:name, :ownerID);";
        $stmt = $this->connect()->prepare($sql);
        try {
            $stmt->execute([
                'name' => $name,
                'ownerID' => $ownerID,
            ]);
        } catch (PDOException $e) {
            die("PDO Error: " . $e->getMessage());
        }
    }

    public function getOrganisations($ownerID) {
        $sql = "SELECT * FROM orgs WHERE ownerID = '$ownerID'";
        $stmt = $this->connect()->prepare($sql);
        try {
            $stmt->execute();
        } catch (PDOException $e) {
            die($e);
        }
        return $stmt->fetch();
    }
}