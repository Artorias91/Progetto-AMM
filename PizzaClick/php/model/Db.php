<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Db
 *
 * @author amm
 */

//include_once '../../Settings.php';
include_once basename(__DIR__) . '/../Settings.php';

class Db {
    //put your code here
    
    private function __construct() {}
    
    private static $singleton;
    /**
     *  Restituisce un singleton per la connessione al Db
     * @return \Db
     */
    public static function getInstance(){
        if(!isset(self::$singleton)){
            self::$singleton = new Db();
        }        
        return self::$singleton;
    }
    
    /**
     * Restituisce una connessione funzionante al db
     * @return \mysqli una connessione funzionante al db dell'applicazione,
     * null in caso di errore
     */
    public function connectDb(){
        $mysqli = new mysqli();
        $mysqli->connect(Settings::$db_host, Settings::$db_user,
        Settings::$db_password, Settings::$db_name);

///PROVA
//$result = $mysqli->query("show tables");
//while($table = $result->fetch_array()) {
//    echo($table[0] . "<BR>");
//}
        if($mysqli->errno != 0){
            return null;
        }else{
            return $mysqli;
        }
    }
}
?>