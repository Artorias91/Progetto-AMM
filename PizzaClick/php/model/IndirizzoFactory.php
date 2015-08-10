<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of IndirizzoFactory
 *
 * @author amm
 */

include_once 'Indirizzo.php';

class IndirizzoFactory {
    
    //put your code here    
    private static $singleton;
    
    private function __constructor() {}
    
    /**
     * Restiuisce un singleton per creare Indirizzi
     * @return \CorsoDiLaureaFactory
     */
    public static function instance(){
        if(!isset(self::$singleton)){
            self::$singleton = new IndirizzoFactory();
        }        
        return self::$singleton;
    }


    /**
     * Carica un indirizzo tramite id
     * (non fornito da input utente)
     * @param int $id
     * @return \Indirizzo altrimenti null
     */
//    public function caricaIndirizzoPerId($id) {
//        $intval = filter_var($id, FILTER_VALIDATE_INT, FILTER_NULL_ON_FAILURE);
//        if (!isset($intval)) {
//            return null;
//        }
//        $query = "select * from indirizzi where id = $id";
//        
//        $mysqli = Db::getInstance()->connectDb();
//        
//        if (!isset($mysqli)) {
//            error_log("[caricaIndirizzoPerId] impossibile inizializzare il database");
//            $mysqli->close();
//            return null;
//        }
//        $result = $mysqli->query($query);
//        if ($mysqli->errno > 0) {
//            error_log("[caricaIndirizzoPerId] impossibile eseguire la query");
//            $mysqli->close();
//            return null;
//        }
//        
//        $row = $result->fetch_array();
//        $indirizzo = self::creaIndirizzoDaArray($row);
//
//        $mysqli->close();
//        
//        return $indirizzo;
//    }
    
    /**
     * Cerca un indirizzo per id 
     * @param $id
     * @return Indirizzo un oggetto Indirizzo nel caso sia stato trovato,
     */    
    public function cercaIndirizzoPerId($id) {
        $intval = filter_var($id, FILTER_VALIDATE_INT, FILTER_NULL_ON_FAILURE);
        if (!isset($intval)) {
            return null;
        }
        $query = "select * from indirizzi where id = ?";
        
        $mysqli = Db::getInstance()->connectDb();
        
        if (!isset($mysqli)) {
            error_log("[cercaIndirizzoPerId] impossibile inizializzare il database");
            $mysqli->close();
            return null;
        }
  
        $stmt = $mysqli->stmt_init();
        $stmt->prepare($query);
        if (!$stmt) {
            error_log("[cercaIndirizzoPerId] impossibile" .
                    " inizializzare il prepared statement");
            $mysqli->close();
            return null;
        }

        if (!$stmt->bind_param('i', $intval)) {
            error_log("[cercaIndirizzoPerId] impossibile" .
                    " effettuare il binding in input");
            $mysqli->close();
            return null;
        }

        $indirizzo =  self::caricaIndirizzoDaStmt($stmt);

        $mysqli->close();
        
        return $indirizzo;
    }

    /**
     * Carica un indirizzo eseguendo un prepared statement
     * @param mysqli_stmt $stmt
     * @return null
     */
    public function caricaIndirizzoDaStmt(mysqli_stmt $stmt) {

        if (!$stmt->execute()) {
            error_log("[caricaIndirizzoDaStmt] impossibile" .
                    " eseguire lo statement");
            return null;
        }

        $row = array();
        $bind = $stmt->bind_result(
                $row['id'], 
                $row['destinatario'], 
                $row['via_num'], 
                $row['citta'], 
                $row['provincia'], 
                $row['cap'], 
                $row['telefono'] 
        );
        if (!$bind) {
            error_log("[caricaIndirizzoDaStmt] impossibile" .
                    " effettuare il binding in output");
            return null;
        }

        if (!$stmt->fetch()) {
            return null;
        }

        $stmt->close();

        return self::creaIndirizzoDaArray($row);
    }

    
    /**
     * Crea e restitutisce un indirizzo a partire da una riga di DB
     * @param type $row
     * @return \Indirizzo
     */
    public function creaIndirizzoDaArray($row){
        $indirizzo = new Indirizzo();
        $indirizzo->setId($row['id']);
        $indirizzo->setDestinatario($row['destinatario']);
        $indirizzo->setNomeIndirizzo($row['via_num']);
        $indirizzo->setCitta($row['citta']);
        $indirizzo->setProvincia($row['provincia']);
        $indirizzo->setCap($row['cap']);
        $indirizzo->setTelefono($row['telefono']);
        
//        echo var_dump($indirizzo);
        return $indirizzo;
    }
        
    
}

?>
