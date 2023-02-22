<?php

include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/dbh.class.php');

class TicketController extends Dbh {

    // Add a ticket into the tickets table in the database
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

    // Add a ticket into the tickets table in the database
    public function addResponse($ticketID, $responseID, $userID, $name, $body){
        $sql = "INSERT INTO responses (ticketID, responseNum, responseID, userID, name, body) VALUES (:ticketID, :responseNum, :responseID, :userID, :name, :body);";
        $stmt = $this->connect()->prepare($sql);
        try {
            $stmt->execute([
                'ticketID' => $ticketID,
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

    // Delete a ticket response
    public function deleteResponse($responseID){
        $sql = "DELETE FROM responses WHERE responseID = :responseID";
        $stmt = $this->connect()->prepare($sql);
        try {
            $stmt->execute([
                'responseID' => $responseID
            ]);
        } catch (PDOException $e) {
            die("PDO Error: " . $e->getMessage());
        }
    }

    // Delete all responses to a ticket (Private: only to be called when deleting a ticket)
    private function deleteResponses($ticketID){
        $sql = "DELETE FROM responses WHERE ticketID = :ticketID";
        $stmt = $this->connect()->prepare($sql);
        try {
            $stmt->execute([
                'ticketID' => $ticketID
            ]);
        } catch (PDOException $e) {
            die("PDO Error: " . $e->getMessage());
        }
    }

    // Delete a ticket
    public function deleteTicket($ticketID){
        $this->deleteResponses($ticketID);
        $sql = "DELETE FROM tickets WHERE ticketID = :ticketID";
        $stmt = $this->connect()->prepare($sql);
        try {
            $stmt->execute([
                'ticketID' => $ticketID
            ]);
        } catch (PDOException $e) {
            die("PDO Error: " . $e->getMessage());
        }
    }

    // Delete all tickets in a project
    // This can't be the best way to do this as it is a lot of SQL queries where one would suffice. Need to work out how to delete responses properly.
    public function deleteTickets($projID) {
        $tickets = $this->getTickets($projID);
        foreach ($tickets as $t) {
            $this->deleteTicket($t['ticketID']);
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