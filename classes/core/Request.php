<?php
require_once('../../classes/database/Connect.php');

class Request extends Connect{
    Public $email;
    private $pass;
    public function __construct($email, $pass){
        $this->email = $email;
        $this->pass = $pass;
    }
    public function getUsers(){
        $email = $this->email;
        $pass = md5($this->pass);
        $sql = "SELECT * FROM users WHERE email= :email AND password= :pass";
        $stmt = $this->connect()->prepare($sql);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':pass', $pass);
        $stmt->execute();
        if($stmt->rowCount() > 0){
            while($row = $stmt->fetch()){
                return $row;
            }
        }
    }
    public function newUser($login, $name, $email){
        $sql = "INSERT INTO users(user_login, user_nicename, user_email) VALUES (?,?,?)";
        $stmt = $this->connect()->prepare($sql);
        $stmt->execute([$login, $name, $email]);
    }
}