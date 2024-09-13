<?php

class Database{
    
    public static $db;

        /*
           Database connection
        */
    public static function db_connect(){
        $json_config = file_get_contents($_SERVER['DOCUMENT_ROOT']."/../env.json");
        $config = json_decode($json_config,true);
        $DB_SERVER = $config['db_host'];
        $DB_USER = $config['db_username'];
        $DB_PASSWORD = $config['db_password'];
        $DB = $config['db_database'];

        if (Database::$db != NULL) {
            return Database::$db;
        } else {
            Database::$db = mysqli_connect($DB_SERVER,$DB_USER,$DB_PASSWORD,$DB);
            if (!Database::$db) {
                die("Connection failed: ".mysqli_connect_error());
            } else {
                return Database::$db;
            }
        }
    }
}