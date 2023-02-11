<?php

include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/dbh.class.php');

class OrgController extends Dbh {
    public function setOrganisation($name, $ownerID, $orgID){
        $sql = "INSERT INTO orgs (name, ownerID, orgID) VALUES (:name, :ownerID, :orgID);";
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

    public function getOrganisations($ownerID) {
        $sql = "SELECT * FROM orgs WHERE ownerID = '$ownerID'";
        $stmt = $this->connect()->prepare($sql);
        try {
            $stmt->execute();
        } catch (PDOException $e) {
            die($e);
        }
        return $stmt->fetchAll();
    }

    public function getOrganisationByID($id) {
        $sql = "SELECT * FROM orgs WHERE orgID = '$id'";
        $stmt = $this->connect()->prepare($sql);
        try {
            $stmt->execute();
        } catch (PDOException $e) {
            die($e);
        }
        return $stmt->fetch();
    }
}