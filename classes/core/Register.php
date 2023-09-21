<?php

require_once('../../classes/database/Connect.php');

class Register{

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

    public function newUserProfilePic(){
        $pic = $this->profilePic;
        
    }
}