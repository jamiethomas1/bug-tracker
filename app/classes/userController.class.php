<?php

include $_SERVER['DOCUMENT_ROOT'] . '/classes/dbh.class.php';

class UserController extends Dbh {
    public function setUser($un, $pw){
        $sql = "INSERT INTO users (username, password) VALUES (:un, :pw);";
        $stmt = $this->connect()->prepare($sql);
        $stmt->execute([
            'un' => $un,
            'pw' => $pw
        ]);
    }

    public function checkUnique($un){
        $sql = "SELECT * FROM users WHERE username = '$un'";
        $stmt = $this->connect()->prepare($sql);
        $stmt->execute();
        if ($stmt->fetch() != '') {
            return false;
        }
        return true;
    }
}