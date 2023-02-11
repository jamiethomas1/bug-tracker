<?php

include $_SERVER['DOCUMENT_ROOT'] . '/classes/dbh.class.php';

class UserController extends Dbh {
    public function setUser($un, $em, $pw, $userID){
        $sql = "INSERT INTO users (name, email, password_hash, userID) VALUES (:un, :em, :pw, :userID);";
        $stmt = $this->connect()->prepare($sql);
        try {
            $stmt->execute([
                'un' => $un,
                'em' => $em,
                'pw' => $pw,
                'userID' => $userID
            ]);
        } catch (PDOException $e) {
            if (str_contains($e->getMessage(), "1062 Duplicate entry")) { 
                die("Duplicate email.");
            };
        }
    }

    // These getUserBy methods can be refactored with optional parameters so it's only defined once
    // and can be called by getUser(email: $em) for example
    public function getUserByEmail($em) {
        $sql = "SELECT * FROM users WHERE email = '$em'";
        $stmt = $this->connect()->prepare($sql);
        try {
            $stmt->execute();
        } catch (PDOException $e) {
            die($e);
        }
        return $stmt->fetch();
    }

    public function getUserByID($id) {
        $sql = "SELECT * FROM users WHERE userID = '$id'";
        $stmt = $this->connect()->prepare($sql);
        try {
            $stmt->execute();
        } catch (PDOException $e) {
            die($e);
        }
        return $stmt->fetch();
    }
}