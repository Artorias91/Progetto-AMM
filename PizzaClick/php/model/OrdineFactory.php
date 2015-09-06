<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of OrdineFactory
 *
 * @author amm
 */

include_once 'Ordine.php';
include_once 'ArticoloSession.php';
include_once 'ArticoloFactory.php';

class OrdineFactory {
    
    private static $singleton;
    
    private function __construct() {}
    
    /**
     * Restiuisce un singleton per creare ordini
     * @return \DipartimentoFactory
     */
    public static function instance(){
        if(!isset(self::$singleton)){
            self::$singleton = new OrdineFactory();
        }
        
        return self::$singleton;
    }        

    /**
     * Salva un ordine sul DB
     * @param $elenco array di articoli che fanno parte dell'ordine
     * @param $cliente id cliente che ha effettuato l'ordine
     * @param Pagamento $pay il metodo di ordine scelto dal cliente
     * @param float $prezzo_ordine il prezzo dell'ordine
     * @return boolean true se il salvataggio va a buon fine, false altrimenti
     */
    public function salvaOrdine($elenco, $cliente_id, /*Pagamento $pay, */$prezzo_ordine) {
        $mysqli = Db::getInstance()->connectDb();
        if (!isset($mysqli)) {
            error_log("[salvaOrdine] impossibile inizializzare il database");
            $mysqli->close();
            return false;
        }
        $stmt = $mysqli->stmt_init();
        $stmt2 = $mysqli->stmt_init();

        $insert_ordine = "insert into ordini (id, data_creazione, subtotale, cliente_id) 
            values (default, NOW(), ?, ?)";
        
        $insert_articoli = 'insert into articoli values
            (default, ?, ?, ?, ?, ?)';        
        
        //stmt tab ordini
        $stmt->prepare($insert_ordine);
        if (!$stmt) {
            error_log("[salvaOrdine] impossibile" .
                    " inizializzare il prepared statement");
            $mysqli->close();
            return false;
        }

        //stmt tab articoli        
        $stmt2->prepare($insert_articoli);
        if (!$stmt) {
            error_log("[salvaOrdine] impossibile" .
                    " inizializzare il prepared statement");
            
            echo 'impossibile inizializzare il prepared statement';
            
            return 0;
        }        
                
        //stmt tab ordini
        if (!$stmt->bind_param('di', 
                floatval($prezzo_ordine), 
                intval($cliente_id)
                )) {
            error_log("[salvaOrdine] impossibile" .
                    " effettuare il binding in input stmt");
            $mysqli->close();
            return false;
        }
        
        //var stmt tab articoli
        $articolo_size = '';
        $articolo_qty = 0;
        $articolo_prezzo = 0.0;
        $articolo_pizza_id = 0;
        $ordine_id = 0;
        
        //stmt tab articoli        
        if (!$stmt2->bind_param('sidii',
                $articolo_size,
                $articolo_qty,
                $articolo_prezzo,
                $articolo_pizza_id,
                $ordine_id
                )) {
            error_log("[salvaOrdine] impossibile" .
                    " effettuare il binding in input");

            echo 'impossibile" .
                    " effettuare il binding in input';

            return 0;
        }               

        // inizio la transazione
        $mysqli->autocommit(false);        
        
        //stmt tab ordini
        if (!$stmt->execute()) {
            error_log("[salvaOrdine] impossibile" .
                    " eseguire lo statement");
            $mysqli->rollback();
            $mysqli->close();
            return false;
        }

        //stmt tab articoli        
        foreach ($elenco as $articolo) {
            
            $articolo_size = $articolo->getSize();
            $articolo_qty = $articolo->getQty();
            $articolo_prezzo = $articolo->getPrezzoArticolo();
            $articolo_pizza_id = $articolo->getPizza()->getId();
            $ordine_id = $stmt->insert_id;
            
            if (!$stmt2->execute()) {
                error_log("[salvaOrdine] impossibile" .
                        " eseguire lo statement");

                echo 'impossibile eseguire lo statement';

                return 0;
            }
        }

        // tutto ok, posso rendere persistente il salvataggio
        $mysqli->commit();
        $mysqli->autocommit(true);
        $mysqli->close();

        return true;
    }
    
    
    /**
     * Restituisce la lista degli ordini effettuati da uno specifico cliente
     * @param Cliente $cliente
     * @return array una lista di ordini
     */    
    public function &getListaOrdiniPerCliente(Cliente $cliente) {

        $ordini = array();
        $query = 
        "select 
            ordini.id, 
            ordini.data_conclusione, 
            ordini.data_creazione,
            ordini.subtotale
        from 
            ordini join clienti
        on 
            ordini.cliente_id = clienti.id
        where 
            ordini.cliente_id = ?";
        
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

        if (!$stmt->bind_param('i', $cliente->getId())) {
            error_log("[getListaOrdiniPerCliente] impossibile" .
                    " effettuare il binding in input");
            $mysqli->close();
            return null;
        }

        $ordini =  self::caricaOrdiniDaStmt($stmt);

        $mysqli->close();
        
        return $ordini;
    }

  
    /**
     * Carica una lista di ordini eseguendo un prepared statement
     * @param mysqli_stmt $stmt
     * @return null
     */
    public function &caricaOrdiniDaStmt(mysqli_stmt $stmt) {
        $ordini = array();
        if (!$stmt->execute()) {
            error_log("[caricaOrdiniDaStmt] impossibile" .
                    " eseguire lo statement");
            return null;
        }

        $row = array();
        $bind = $stmt->bind_result(
                $row['id'], 
                $row['data_conclusione'], 
                $row['data_creazione'], 
                $row['subtotale']
        );
        if (!$bind) {
            error_log("[caricaOrdiniDaStmt] impossibile" .
                    " effettuare il binding in output");
            return null;
        }

        while ($stmt->fetch()) {
            $ordini[] = self::creaOrdineDaArray($row);
        }

        $stmt->close();

        return $ordini;
    }

    /**
     * Crea e restitutisce un ordine 
     * a partire da una riga di DB
     * @param type $row
     * @param boolean
     * @return Ordine
     */
    public function creaOrdineDaArray($row, $flag = FALSE){
        $ordine = new Ordine();
        
//        var_dump($row);        
        
        $ordine->setId($row['id']);
        $ordine->setDataConclusione($row['data_conclusione']);        
        $ordine->setDataCreazione($row['data_creazione']);
        $ordine->setSubtotale($row['subtotale']);
        
        $ordine->setArticoli(ArticoloFactory::instance()->
                getListaArticoliPerIdOrdine($row['id']));

        if($flag) {
            $ordine->setClienteId($row['cliente_id']);
        }
        
        return $ordine;
    }           

    
    
    /**
     * Chiude un ordine aggiornando il campo timestamp 'data_conclusione'
     * @param $id ordine da aggiornare
     * @return int il numero di righe modificate
     */    
    public function chiudiOrdinePerId($id) {
        $intval = filter_var($id, FILTER_VALIDATE_INT, FILTER_NULL_ON_FAILURE);
        if (!isset($intval)) {
            return null;
        }
        $query = "update ordini set data_conclusione = NOW() where id = ?";

        $mysqli = Db::getInstance()->connectDb();
        if (!isset($mysqli)) {
            error_log("[salva] impossibile inizializzare il database");
            $mysqli->close();
            return 0;
        }

        $stmt = $mysqli->stmt_init();        
        
        
        $stmt->prepare($query);
        if (!$stmt) {
            error_log("[chiudiOrdinePerId] impossibile" .
                    " inizializzare il prepared statement");
            
            echo 'impossibile inizializzare il prepared statement';
            
            return 0;
        }

        if (!$stmt->bind_param('i', $intval)) {
            error_log("[chiudiOrdinePerId] impossibile" .
                    " effettuare il binding in input");
            
            echo 'impossibile effettuare il binding in input';
            
            return 0;
        }

        if (!$stmt->execute()) {
            error_log("[chiudiOrdinePerId] impossibile eseguire lo statement");
            
            echo 'impossibile eseguire lo statement';
            
            return 0;
        }
        $n = $stmt->affected_rows;
        
        $stmt->close();
        $mysqli->close();                
        
        return $n;
    }
        
    
    
    /**
     * Restituisce la lista degli ordini attivi presenti nel sistema
     * @return array
     */
    public function &getListaOrdiniAttivi() {
        $clienti = array();
        $query = "select * from ordini where data_conclusione = 0";
        
        $mysqli = Db::getInstance()->connectDb();
        if (!isset($mysqli)) {
            error_log("[getListaOrdini] impossibile inizializzare il database");
            $mysqli->close();
            return $clienti;
        }
        $result = $mysqli->query($query);
        if ($mysqli->errno > 0) {
            error_log("[getListaOrdini] impossibile eseguire la query");
            $mysqli->close();
            return $clienti;
        }

        while ($row = $result->fetch_array()) {
            $clienti[] = self::creaOrdineDaArray($row, TRUE);
        }

        return $clienti;
    }
    
    
}

?>
