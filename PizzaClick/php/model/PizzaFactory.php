<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of PizzaFactory
 *
 * @author amm
 */
include_once 'Db.php';

include_once 'Pizza.php';

class PizzaFactory {

    private static $singleton;
    
    private function __constructor() {}    
    /**
     * Restiuisce un singleton per creare pizze
     * @return \PizzaFactory
     */
    public static function instance() {
        if (!isset(self::$singleton)) {
            self::$singleton = new PizzaFactory();
        }
        return self::$singleton;
    }    
    
    /**
     * Restituisce la lista delle pizze senza immagine (colonna)
     * @param boolean $flag true si chiede una lista di pizze provviste
     * di un'immagine, altrimenti senza
     * @return array una lista di pizze con o senza immagine (non entrambi)
     */
    public function &getListaPizze($flag) {

        $pizze = array();
        
        $query = "select * from pizze where image_url ";
        
        //cerchiamo quelle NON provviste di immagine
        if(! $flag) {
            $query .=  '<=> NULL';
        } else { //altrimenti
            $query .=  'IS NOT NULL';
        }
        
        $mysqli = Db::getInstance()->connectDb();        
        if (!isset($mysqli)) {
            error_log("[getListaPizze] impossibile inizializzare il database");
            $mysqli->close();
            return null;
        }
        $result = $mysqli->query($query);
        if ($mysqli->errno > 0) {
            error_log("[getListaPizze] impossibile eseguire la query");
            $mysqli->close();
            return null;
        }
        
        while ($row = $result->fetch_array()) {
            $pizze[] = self::creaPizzaDaArray($row, $flag);
        }

        $mysqli->close();
        
        return $pizze;
    }
    

    /**
     * Cerca una pizza per id 
     * @param $id
     * @return \Pizza un oggetto Pizza nel caso sia stato trovato,
     */    
    public function cercaPizzaPerId($id) {
        $intval = filter_var($id, FILTER_VALIDATE_INT, FILTER_NULL_ON_FAILURE);
        if (!isset($intval)) {
            return null;
        }
        $query = "select * from pizze where id = ?";
        
        $mysqli = Db::getInstance()->connectDb();
        
        if (!isset($mysqli)) {
            error_log("[cercaPizzaPerId] impossibile inizializzare il database");
            $mysqli->close();
            return null;
        }
  
        $stmt = $mysqli->stmt_init();
        $stmt->prepare($query);
        if (!$stmt) {
            error_log("[cercaPizzaPerId] impossibile" .
                    " inizializzare il prepared statement");
            $mysqli->close();
            return null;
        }

        if (!$stmt->bind_param('i', $intval)) {
            error_log("[cercaPizzaPerId] impossibile" .
                    " effettuare il binding in input");
            $mysqli->close();
            return null;
        }

        $pizza =  self::caricaPizzaDaStmt($stmt);

        $mysqli->close();
        
        return $pizza;
    }
    
    /**
     * Carica una pizza eseguendo un prepared statement
     * @param mysqli_stmt $stmt
     * @return null
     */
    public function caricaPizzaDaStmt(mysqli_stmt $stmt) {

        if (!$stmt->execute()) {
            error_log("[caricaPizzaDaStmt] impossibile" .
                    " eseguire lo statement");
            return null;
        }

        $row = array();
        $bind = $stmt->bind_result(
                $row['id'], 
                $row['nome'], 
                $row['ingredienti_extra'], 
                $row['prezzo'], 
                $row['image_url'] 
        );
        if (!$bind) {
            error_log("[caricaPizzaDaStmt] impossibile" .
                    " effettuare il binding in output");
            return null;
        }

        if (!$stmt->fetch()) {
            return null;
        }

        $stmt->close();

        return self::creaPizzaDaArray($row, $row['id'] == NULL ? false : true);
    }

    
    
    /**
     * Crea una pizza da una riga del db
     * @param type $row
     * @param boolean $flag true con immagine, altrimenti senza
     * @return \Pizza
     */
    public function creaPizzaDaArray($row, $flag) {
        $pizza = new Pizza;

        $pizza->setId($row['id']);
        $pizza->setNome($row['nome']);
        $pizza->setIngredientiExtra($row['ingredienti_extra']);
        $pizza->setPrezzo($row['prezzo']);

        if($flag)  
          $pizza->setUrlImage($row['image_url']);                        
        
        return $pizza;
    }    
    
}

?>
