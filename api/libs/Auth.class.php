<?php

require($_SERVER['DOCUMENT_ROOT'].'/vendor/autoload.php');

require_once("User.class.php");
require_once("Oauth.class.php");

require_once("Db.class.php");

class Auth{
    private $conn;
    private $username;
    private $password;
    private $isTokenAuth =false;
    private $loginTokens;

    public function __construct($username,$password = null)
    {
        $this->conn = Database::db_connect();
        if ($password == null) {
            // $this->token = $username;
            $this->isTokenAuth = true;
        }else {
            $this->username = $username;
            $this->password = $password;
        }

        if ($this->isTokenAuth) {
            throw new Exception("not implemented");
        }else{
            $user = new User($this->username);
            $hash = $user->getPassword();
            $this->username = $user->getUsername();
            if (password_verify($password,$hash)) {
                if (!$user->isActive()) {
                    throw new Exception("your account is not active please activate your account");
                }
                $this->loginTokens = Auth::addSession();
            }else {
                throw new Exception("incorrect password");  
            }
            
        }

    }

    public function getAuthTokens(){
        return $this->loginTokens;
    }


    private function addSession(){
        $oauth = new Oauth($this->username);
        $session = $oauth->newSession();
        return $session;
        
    }


    public static function hash_generate($len){
        $bytes = openssl_random_pseudo_bytes($len, $cstrong);
        return bin2hex($bytes);
    }
}