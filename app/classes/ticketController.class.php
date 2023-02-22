<?php

include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/dbh.class.php');

class TicketController extends Dbh {

    // Add a ticket into the tickets table in the database
    public function createTicket($name, $ownerID, $orgID, $projID, $ticketID, $ticketBody){
        $sql = "INSERT INTO tickets (name, ownerID, orgID, projID, ticketID, body) VALUES (:name, :ownerID, :orgID, :projID, :ticketID, :ticketBody);";
        $stmt = $this->connect()->prepare($sql);
        try {
            $stmt->execute([
                'name' => $name,
                'ownerID' => $ownerID,
                'orgID' => $orgID,
                'projID' => $projID,
                'ticketID' => $ticketID,
                'ticketBody' => $ticketBody
            ]);
        } catch (PDOException $e) {
            die("PDO Error: " . $e->getMessage());
        }
    }

    // Add a ticket into the tickets table in the database
    public function addResponse($ticketID, $projID, $orgID, $responseID, $userID, $name, $body){
        $sql = "INSERT INTO responses (ticketID, projID, orgID, responseNum, responseID, userID, name, body) VALUES (:ticketID, :projID, :orgID, :responseNum, :responseID, :userID, :name, :body);";
        $stmt = $this->connect()->prepare($sql);
        try {
            $stmt->execute([
                'ticketID' => $ticketID,
                'projID' => $projID,
                'orgID' => $orgID,
                'responseNum' => count($this->getResponses($ticketID)) + 1, // This is the number of the response within the specific ticket's response chain
                'responseID' => $responseID,
                'userID' => $userID,
                'name' => $name,
                'body' => $body
            ]);
        } catch (PDOException $e) {
            die("PDO Error: " . $e->getMessage());
        }
    }

    // Returns an associative array of the tickets contained within project $projID
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

    // Returns a ticket by its 16-character alphanumeric ID
    public function getTicketByID($id) {
        $sql = "SELECT * FROM tickets WHERE ticketID = '$id'";
        $stmt = $this->connect()->prepare($sql);
        try {
            $stmt->execute();
        } catch (PDOException $e) {
            die($e);
        }
        return $stmt->fetch();
    }

    // Returns an associative array of the responses associated with ticket $ticketID
    public function getResponses($ticketID) {
        $sql = "SELECT * FROM responses WHERE ticketID = '$ticketID'";
        $stmt = $this->connect()->prepare($sql);
        try {
            $stmt->execute();
        } catch (PDOException $e) {
            die($e);
        }
        return $stmt->fetchAll();
    }
}