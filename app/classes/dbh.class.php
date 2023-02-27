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

    protected function connect($attr = []){
        $dsn = "mysql:host=$this->host;port=3306;dbname=$this->dbname;charset=utf8";

        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ];

        $attributes = array_merge($options, $attr);

        try {
            $pdo = new PDO($dsn, $this->user, $this->psw, $attributes);
        } catch (PDOException $Exception) {
            echo "PDO Error: " . $Exception->getMessage();
        }
        
        return $pdo;
    }

    public function delete(Delete $del, $id) {
        switch ($del) {
            case Delete::RESPONSE:
                $sql = ["DELETE FROM responses WHERE responseID = :id"];
                break;
            case Delete::TICKET:
                $sql = ["DELETE FROM responses WHERE ticketID = :id",
                        "DELETE FROM tickets WHERE ticketID = :id"];
                break;
            case Delete::PROJECT:
                $sql = ["DELETE FROM responses WHERE projectID = :id",
                        "DELETE FROM tickets WHERE projectID = :id",
                        "DELETE FROM projects WHERE projectID = :id"];
                break;
            case Delete::ORGANISATION:
                $sql = ["DELETE FROM responses WHERE orgID = :id",
                        "DELETE FROM tickets WHERE orgID = :id",
                        "DELETE FROM projects WHERE orgID = :id",
                        "DELETE FROM orgs WHERE orgID = :id"];
                break;
            default:
                die("Invalid Delete enum");
        }
        foreach ($sql as $query) {
            $stmt = $this->connect()->prepare($query);
            try {
                $stmt->execute([
                    'id' => $id
                ]);
            } catch (PDOException $e) {
                die("PDO Error: " . $e->getMessage());
            }
        }
    }
}