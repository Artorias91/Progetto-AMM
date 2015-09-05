<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ArticoloFactory
 *
 * @author amm
 */

include_once 'Articolo.php';

class ArticoloFactory {
    //put your code here
    
    private static $singleton;
    
    private function __construct() {}
    
    /**
     * Restiuisce un singleton per creare articoli
     * @return \DipartimentoFactory
     */
    public static function instance(){
        if(!isset(self::$singleton)){
            self::$singleton = new ArticoloFactory();
        }
        
        return self::$singleton;
    }    
 
/**
     * Restituisce la lista degli articoli effettuati da uno specifico cliente
     * @param Cliente $cliente
     * @return array una lista di articoli
     */    
    public function &getListaArticoliPerIdOrdine($ordine_id) {

        $articoli = array();
        $query = 
        "select 
            articoli.id,
            articoli.size,
            articoli.qty,
            articoli.prezzo,
            articoli.pizza_id
        from 
            articoli
        where 
            articoli.ordine_id = ?";
        
        $mysqli = Db::getInstance()->connectDb();
        
        if (!isset($mysqli)) {
            error_log("[getListaOrdiniPerCliente] impossibile inizializzare il database");
            $mysqli->close();
            return null;
        }
  
        $stmt = $mysqli->stmt_init();
        $stmt->prepare($query);
        if (!$stmt) {
            error_log("[getListaOrdiniPerCliente] impossibile" .
                    " inizializzare il prepared statement");
            $mysqli->close();
            return null;
        }

        if (!$stmt->bind_param('i', $ordine_id)) {
            error_log("[getListaOrdiniPerCliente] impossibile" .
                    " effettuare il binding in input");
            $mysqli->close();
            return null;
        }

        $articoli =  self::caricaArticoliDaStmt($stmt);

        $mysqli->close();
        
        return $articoli;
    }

  
    /**
     * Carica una lista di articoli eseguendo un prepared statement
     * @param mysqli_stmt $stmt
     * @return null
     */
    public function &caricaArticoliDaStmt(mysqli_stmt $stmt) {
        $articoli = array();
        if (!$stmt->execute()) {
            error_log("[caricaArticoliDaStmt] impossibile" .
                    " eseguire lo statement");
            return null;
        }

        $row = array();
        $bind = $stmt->bind_result(
                $row['id'], 
                $row['size'], 
                $row['qty'], 
                $row['prezzo'],
                $row['pizza_id']
        );
        if (!$bind) {
            error_log("[caricaArticoliDaStmt] impossibile" .
                    " effettuare il binding in output");
            return null;
        }

        while ($stmt->fetch()) {
            $articoli[] = self::creaArticoloDaArray($row);
        }

        $stmt->close();

        return $articoli;
    }

    /**
     * Crea e restitutisce un ordine 
     * a partire da una riga di DB
     * @param type $row
     * @return Articolo
     */
    public function creaArticoloDaArray($row){
        $articolo = new Articolo;
        
//        var_dump($row);

        
        $articolo->setId($row['id']);
        $articolo->setSize($row['size']);
        $articolo->setQty($row['qty']);
        $articolo->setPrezzo($row['prezzo']);
        $articolo->setPizzaId($row['pizza_id']);
        
        return $articolo;
    }    
    
}

?>
