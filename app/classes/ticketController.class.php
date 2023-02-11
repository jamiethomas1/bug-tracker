<?php

include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/dbh.class.php');

class TicketController extends Dbh {
    public function createTicket($name, $ownerID, $projID){
        $sql = "INSERT INTO tickets (name, ownerID, projID) VALUES (:name, :ownerID, :projID);";
        $stmt = $this->connect()->prepare($sql);
        try {
            $stmt->execute([
                'name' => $name,
                'ownerID' => $ownerID,
                'projID' => $projID
            ]);
        } catch (PDOException $e) {
            die("PDO Error: " . $e->getMessage());
        }
    }

    public function getTickets($projID) {
        $sql = "SELECT * FROM tickets WHERE projID = '$projID'";
        $stmt = $this->connect()->prepare($sql);
        try {
            $stmt->execute();
        } catch (PDOException $e) {
            die($e);
        }
        return $stmt->fetchAll();
    }
}