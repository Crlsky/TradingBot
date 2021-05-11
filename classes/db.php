<?php
require_once(dirname(__DIR__).'/config/db.php');
    
class DB {
    private $conn;

    public function __construct() {
        global $servername, $username, $dbname, $password;
        $this->conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    public function Get($sql) {
        try{
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);

            return $stmt->fetchAll();
        } catch(PDOException $e) {
            return $sql . "<br>" . $e->getMessage();
        }
    }

    public function Insert($sql) {
        try {
            $this->conn->exec($sql);
        } catch(PDOException $e) {
            return $sql . "<br>" . $e->getMessage();
        }
    }
}
?>