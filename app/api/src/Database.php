<?php

include_once("../classes/dbh.class.php");

class Database extends Dbh {
    public function getConnection() {
        return $this->connect();
    }
}