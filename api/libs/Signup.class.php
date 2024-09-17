<?php

require_once($_SERVER['DOCUMENT_ROOT'].'/vendor/autoload.php');

require_once("Db.class.php");


class Signup{
    private $db;

    private $username;
    private $email;
    private $password;
    private $token;

    public function __construct($username,$email,$password,)
    {
        $this->db = Database::db_connect();

        $this->username = $username;
        $this->email = $email;
        $this->password = $password;
        $pass_hash = password_hash($this->password,PASSWORD_BCRYPT,['cost' => 8]);
        $this->token = bin2hex(random_bytes(16));

        $sql = "insert into auth (username,email,password,token) values ('$this->username','$this->email','$pass_hash', '$this->token')";
        $result = $this->db->query($sql);
        if(!$result){
            
              throw new Exception("unable to signup");
        }else{
            $this->id = $this->db->insert_id;
        } 

    }

    public function send_verification_mail(){
        $json_config = file_get_contents($_SERVER['DOCUMENT_ROOT']."/../env.json");
        $config = json_decode($json_config,true);
    
    
        // Configure API key authorization: api-key
        $config = SendinBlue\Client\Configuration::getDefaultConfiguration()->setApiKey('api-key', $config['email_api_key']);
    
        // Uncomment below line to configure authorization using: partner-key
        // $config = SendinBlue\Client\Configuration::getDefaultConfiguration()->setApiKey('partner-key', $config['email_api_key']);
    
        $apiInstance = new SendinBlue\Client\Api\TransactionalEmailsApi(
            // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
            // This is optional, `GuzzleHttp\Client` will be used as default.
            new GuzzleHttp\Client(),
            $config
        );
        $sendSmtpEmail = new \SendinBlue\Client\Model\SendSmtpEmail(); // \SendinBlue\Client\Model\SendSmtpEmail | Values to send a transactional email
        $sendSmtpEmail['to'] = array(array('email'=>"$this->email", 'name'=>"$this->username"));
        $sendSmtpEmail['templateId'] = 2;
        $sendSmtpEmail['params'] = array('name'=> $this->username , 'token'=> $this->token);
        $sendSmtpEmail['headers'] = array('X-Mailin-custom'=>'custom_header_1:custom_value_1|custom_header_2:custom_value_2');
    
        try {
            $result = $apiInstance->sendTransacEmail($sendSmtpEmail);
            print_r($result);
        } catch (Exception $e) {
            echo 'Exception when calling TransactionalEmailsApi->sendTransacEmail: ', $e->getMessage(), PHP_EOL;
        }
    }

}