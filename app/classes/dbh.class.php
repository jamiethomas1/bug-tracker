<?php

enum Delete {
    case RESPONSE;
    case TICKET;
    case PROJECT;
    case ORGANISATION;
}

class Dbh {
    private $host;
    private $user;
    private $psw;
    private $dbname;

    public function __construct() {
        $this->host = "sql";
        $this->user = "root";
        $this->psw = getenv("MYSQL_ROOT_PASSWORD");
        $this->dbname = getenv("MYSQL_DATABASE");
    }

    protected function connect(){
        $dsn = "mysql:host=$this->host;port=3306;dbname=$this->dbname;charset=utf8";

        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ];

        try {
            $pdo = new PDO($dsn, $this->user, $this->psw, $options);
        } catch (PDOException $Exception) {
            echo "PDO Error: " . $Exception->getMessage();
        }
        
        return $pdo;
    }
}