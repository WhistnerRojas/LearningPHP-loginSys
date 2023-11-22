<?php

require_once('../../classes/database/Connect.php');

class Register extends Connect{

    public $fname;
    public $lname;
    Public $email;
    private $pass;
    public $profilePic;
    public function __construct($fname, $lname, $email, $pass, $profilePic){
        $this->fname = $fname;
        $this->lname = $lname;
        $this->email = $email;
        $this->pass = $pass;
        $this->profilePic = $profilePic;
    }

    public function checkUserExists(){
        $emailCheck = "SELECT * FROM users WHERE email= :email";
        $stmt = $this->connect()->prepare($emailCheck);
        $stmt->bindParam(':email', $this->email);
        $stmt->execute();
        return $stmt->rowCount() > 0 ? true : false;
    }

    public function UploadProfilePic(){
        $picName = $this->profilePic['name'];
        $picType = $this->profilePic['type'];
        $picTmpName = $this->profilePic['tmp_name'];
        
        $picExplode = explode('.', $picName);
        $picExt = end($picExplode);

        $extensions = array(
            "jpg", "png", "jpeg"
        );

        if(in_array($picExt, $extensions)) {
            $types = ["image/jpg", "image/png", "image/jpeg"];
            if(in_array($picType, $types)) {
                $time = time();
                $new_img_name = $time.$picName;
                if(move_uploaded_file($picTmpName, "../../assets/img/".$new_img_name)){
                    if($this->saveToDB($new_img_name)){
                        return true;
                    }else{
                        return false;
                    }
                }
            }
        }
    }

    private function saveToDB($new_img_name){
        $encryptPass = md5($this->pass);
        $ran_id = rand(time(), 100000000);
        $status = 'Offline';

        $saveUser = "INSERT INTO users (unique_id, fname, lname, email, password, img, status) VALUES (?,?,?,?,?,?,?)";

        $stmt = $this->connect()->prepare($saveUser);
        return $stmt->execute([$ran_id, $this->fname, $this->lname, $this->email, $encryptPass, $new_img_name, $status]) ? true : false;

    }
}

// !error message for picture upload. payload seems to be wrong.