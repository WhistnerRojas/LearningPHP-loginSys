<?php
require_once __DIR__ . '/../../classes/database/Connect.php';

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
        $sql = "SELECT * FROM users WHERE email= :email";
        $stmt = $this->connect()->prepare($sql);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        if($stmt->rowCount() > 0){
            $row = $stmt->fetch();
            if($row['password'] == $pass){
                $this->status($row['unique_id'], 'Online');
                session_start();

                $_SESSION['unique_id']= $row['unique_id'];
                $_SESSION['fname'] = $row['fname'];
                $_SESSION['lname'] = $row['lname'];
                $_SESSION['img'] = $row['img'];
                $_SESSION['user_status'] = 'Online';

                return true;
            }
        }else{
            return false;
        }
    }

    public function logOut(){
        if($this->status($_SESSION['unique_id'], 'Offline')){
            session_unset();
            session_destroy();
            header('Location: http://localhost/signinup/');
        }
    }

    public function newUser($login, $name, $email){
        $sql = "INSERT INTO users(user_login, user_nicename, user_email) VALUES (?,?,?)";
        $stmt = $this->connect()->prepare($sql);
        $stmt->execute([$login, $name, $email]);
    }

    private function status($uniqueId, $userStatus){

        $status = "UPDATE users SET user_status = :userStatus WHERE unique_id = :uniqueId";
        $stmt = $this->connect()->prepare($status);
        $stmt->bindParam(':userStatus', $userStatus, PDO::PARAM_STR);
        $stmt->bindParam(':uniqueId', $uniqueId, PDO::PARAM_INT);
        if($stmt->execute()){
            return true;
        }
        return false;
    }
}