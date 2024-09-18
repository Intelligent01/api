<?
require_once("Db.class.php");

require_once("Auth.class.php");

class Oauth{
    private $conn;
    private $username;
    private $access_token;
    private $refresh_token;
    private $valid_for;

    public function __construct($username,$refresh_token = null){
        $this->conn = Database::db_connect();
        $this->refresh_token = $refresh_token;
        $this->username = $username;
        $u = new User($username);
    }

    public function newSession($valid_for = 7200){
        $this->valid_for = $valid_for;
        $this->access_token = Auth::hash_generate(32);
        $this->refresh_token = Auth::hash_generate(32);

        $query = "INSERT INTO `session` (`username`, `access_token`, `refresh_token`, `valid_for`, `reference_token`) VALUES 
                    ('$this->username', '$this->access_token', '$this->refresh_token', '$this->valid_for','auth_grant')";
        $result = $this->conn->query($query);
        if($result){
            return array(
                'access_token' => $this->access_token,
                'refresh_token' => $this->refresh_token, 
                'valid_for' => $this->valid_for,
                'type' => 'api',

            );
        } else {
            throw new Exception($result->errno);
        }

    }

    public function refresh_session(){
        if ($this->refresh_token) {
            $query = "select * from session where refresh_token = $this->refresh_token";
            $result = $this->conn->query($query);
            if ($result) {
                $row = $result->fetch_assoc();
                if($row['valid']){
                    
                }else{
                    throw new Exception("invalid account");
                    
                }
            }else {
                throw new Exception("invalid refresh token");
            }
        }

    }

}