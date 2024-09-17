<?php

require($_SERVER['DOCUMENT_ROOT'].'/vendor/autoload.php');

require_once("Db.class.php");

class User{
    private $conn;
    private $user;
    

    public function __construct($username)
    {
        $this->username = $username;
        $this->conn = Database::db_connect();
        $sql = "select * from auth where username = '$this->username' or email = '$this->username'";
        $result = $this->conn->query($sql);
        if ($result->num_rows == 1) {
            $this->user = $result->fetch_assoc();
        }
       
    }

    public function getUsername(){
        return $this->user['username'];
    }

    public function getEmail(){
        return $this->user['email'];
    }

    public function getPassword(){
        return $this->user['password'];
    }

    public function isActive(){
        return $this->user['active'];
    }

    public static function hash_generate(){
        $bytes = openssl_random_pseudo_bytes(32, $cstrong);
        return bin2hex($bytes);

    }
}