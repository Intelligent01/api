<?php
    error_reporting(E_ALL ^ E_DEPRECATED);
    require_once("REST.api.php");
    require_once("libs/Db.class.php");
    require_once("libs/Signup.class.php");


    class API extends REST {

        public $data = "";

        private $db = NULL;

        public function __construct(){
            parent::__construct();                // Init parent contructor
            $this->db = Database::db_Connect();                    // Initiate Database connection
        }

        /*
         * Public method for access api.
         * This method dynmically call the method based on the query string
         *
         */
        public function processApi(){
            $func = strtolower(trim(str_replace("/","",$_REQUEST['rquest'])));
            if((int)method_exists($this,$func) > 0)
                $this->$func();
            else
                $this->response('',400);                // If the method not exist with in this class, response would be "Page not found".
        }

        /*************API SPACE START*******************/

        private function about(){

            if($this->get_request_method() != "POST"){
                $error = array('status' => 'WRONG_CALL', "msg" => "The type of call cannot be accepted by our servers.");
                $error = $this->json($error);
                $this->response($error,406);
            }
            $data = array('version' => $this->_request['version'], 'desc' => 'This API is created by Blovia Technologies Pvt. Ltd., for the public usage for accessing data about vehicles.');
            $data = $this->json($data);
            $this->response($data,200);

        }

        private function verify(){
            if($this->get_request_method() == "POST" and isset($this->_request['user']) and isset($this->_request['pass'])){
                $user = $this->_request['user'];
                $password =  $this->_request['pass'];

                $flag = 0;
                if($user == "admin"){
                    if($password == "adminpass123"){
                        $flag = 1;
                    }
                }

                if($flag == 1){
                    $data = [
                        "status" => "verified"
                    ];
                    $data = $this->json($data);
                    $this->response($data,200);
                } else {
                    $data = [
                        "status" => "unauthorized"
                    ];
                    $data = $this->json($data);
                    $this->response($data,401);
                }
            } else {
                $data = [
                        "status" => "bad_request"
                    ];
                    $data = $this->json($data);
                    $this->response($data,400);
            }
        }

        private function test(){
                $data = $this->json(getallheaders());
                $this->response($data,200);
        }

        private function request_info(){
            $data = $this->json($_SERVER);
        }

        function generate_hash(){
            $bytes = random_bytes(16);
            return bin2hex($bytes);
        }




        /*************API SPACE END*********************/

        /*
            Encode array into JSON
        */
        private function json($data){
            if(is_array($data)){
                return json_encode($data, JSON_PRETTY_PRINT);
            } else {
                return "{}";
            }
        }

        
        
        private function signup(){
            if ($this->get_request_method() == 'POST' and isset($this->_request['username']) and isset($this->_request['email']) and isset($this->_request['password'])) {
                $username = $this->_request['username'];
                $email = $this->_request['email'];
                $password = $this->_request['password'];
                
                try{
                    $s = new Signup($username, $email, $password);
                    $data = [
                        "message" => "signup successfull",
                        "user id" => $s->id,
                    ];
                    $this->response($this->json($data),200);
                    
                }catch (Exception $e){
                    $data = [
                        "error" => $e->getMessage(),
                    ];
                    $this->response($this->json($data),400);
                }

            }else{
                $data=[
                    "error" => "bad request",
                ];
                $this->response($this->json($data),402);

            }
            
        }
        

        private function signup_verify(){
            $this->db = Database::db_connect();
            $sql = "update auth set active = 1 ";
            $result=$this->db->query($sql);
            if (!$result) {
                $data = [
                    "error" => "account is activate",
                ];
                $this->response($this->json($data),500);
            }else{
                $data = [
                    "message" => "activation successfully done ....",
                ];
                $this->response($this->json($data),200);
            }
        }
    


    }
        // Initiiate Library
        
        $api = new API;
        $api->processApi();
        ?>