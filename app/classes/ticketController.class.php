<?php

include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/dbh.class.php');

class TicketController extends Dbh {
    public function createTicket($name, $ownerID, $projID, $ticketID, $ticketBody){
        $sql = "INSERT INTO tickets (name, ownerID, projID, ticketID, body) VALUES (:name, :ownerID, :projID, :ticketID, :ticketBody);";
        $stmt = $this->connect()->prepare($sql);
        try {
            $stmt->execute([
                'name' => $name,
                'ownerID' => $ownerID,
                'projID' => $projID,
                'ticketID' => $ticketID,
                'ticketBody' => $ticketBody
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