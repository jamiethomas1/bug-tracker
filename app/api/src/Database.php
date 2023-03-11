<?php

include_once("../classes/dbh.class.php");

class Database extends Dbh {
    public function getConnection() {

        $options = [
            PDO::ATTR_EMULATE_PREPARES => false,
            PDO::ATTR_STRINGIFY_FETCHES => false,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ];

        return $this->connect($options);
    }
}