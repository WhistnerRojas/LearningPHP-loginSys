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
}