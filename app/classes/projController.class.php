<?php

include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/dbh.class.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/ticketController.class.php');

class ProjController extends Dbh {

    // Add a project into the projects table in the database
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

    // Returns an associative array of the projects contained within organisation $orgID
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

    // Returns a project by its 16-character alphanumeric ID
    public function getProjectByID($id) {
        $sql = "SELECT * FROM projects WHERE projID = '$id'";
        $stmt = $this->connect()->prepare($sql);
        try {
            $stmt->execute();
        } catch (PDOException $e) {
            die($e);
        }
        return $stmt->fetch();
    }
}