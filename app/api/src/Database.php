<?php

include_once("../classes/dbh.class.php");

class Database extends Dbh {
    public function getConnection() {

        $options = [
            PDO::ATTR_EMULATE_PREPARES => false,
            PDO::ATTR_STRINGIFY_FETCHES => false
        ];

        return $this->connect($options);
    }
}