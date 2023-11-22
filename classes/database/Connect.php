<?php

class Connect{
    private $dbName = "chatapp";
    private $dbHost = "localhost";
    private $dbUserName = "root";
    private $dbPass = "";

    protected function connect(){
        $dsn = 'mysql:host='.$this->dbHost.';dbname='.$this->dbName;
        $pdo = new PDO($dsn, $this->dbUserName, $this->dbPass);
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

        return $pdo;
    }

    protected function mysql_conn(){
        $conn = mysqli_connect($this->dbHost, $this->dbUserName, $this->dbPass, $this->dbName);
        return $conn;
        // $conn ? "" : die();
    }
}