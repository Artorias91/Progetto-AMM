<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Classe per creare oggetti di tipo Pagamento
 *
 * @author amm
 */

include_once 'Pagamento.php';
include_once 'IndirizzoFactory.php';


class PagamentoFactory {
    //put your code here
    
    private static $singleton;
    
    private function __construct() {}
    
    /**
     * Restiuisce un singleton per creare Pagamenti
     * @return \DipartimentoFactory
     */
    public static function instance(){
        if(!isset(self::$singleton)){
            self::$singleton = new PagamentoFactory();
        }
        
        return self::$singleton;
    }    

    /**
     * Restituisce la lista dei metodi di pagamento (carte di credito) 
     * di uno specifico cliente
     * @param Cliente $cliente
     * @return array una lista di pagamenti (metodi/carte)
     */    
    public function &getListaPagamentiPerCliente(Cliente $cliente) {

        $pagamenti = array();
        $query = 
        "select 
            pagamenti.id, 
            pagamenti.saldo, 
            pagamenti.num_carta, 
            pagamenti.cod_carta, 
            pagamenti.scadenza_carta, 
            pagamenti.titolare_carta, 
            pagamenti.tipo_carta
        from 
            clienti_pagamenti join pagamenti
        on 
            clienti_pagamenti.pagamenti_id = pagamenti.id
        where 
            clienti_pagamenti.clienti_id = ?";
        
        $mysqli = Db::getInstance()->connectDb();
        
        if (!isset($mysqli)) {
            error_log("[caricaPagamentoPerId] impossibile inizializzare il database");
            $mysqli->close();
            return null;
        }
  
        $stmt = $mysqli->stmt_init();
        $stmt->prepare($query);
        if (!$stmt) {
            error_log("[getListaPagamentiPerCliente] impossibile" .
                    " inizializzare il prepared statement");
            $mysqli->close();
            return null;
        }

        if (!$stmt->bind_param('i', $cliente->getId())) {
            error_log("[getListaPagamentiPerCliente] impossibile" .
                    " effettuare il binding in input");
            $mysqli->close();
            return null;
        }

        $pagamenti =  self::caricaPagamentiDaStmt($stmt);

        $mysqli->close();
        
        return $pagamenti;
    }

    
    /**
     * Cerca un metodo di pagamento per id 
     * @param $id
     * @return \Pizza un oggetto Pizza nel caso sia stato trovato,
     */    
    public function cercaPagamentoPerId($id) {
        $intval = filter_var($id, FILTER_VALIDATE_INT, FILTER_NULL_ON_FAILURE);
        if (!isset($intval)) {
            return null;
        }
        $query = "select * from pagamenti where id = ?";
        
        $mysqli = Db::getInstance()->connectDb();
        
        if (!isset($mysqli)) {
            error_log("[cercaPagamentoPerId] impossibile inizializzare il database");
            $mysqli->close();
            return null;
        }
  
        $stmt = $mysqli->stmt_init();
        $stmt->prepare($query);
        if (!$stmt) {
            error_log("[cercaPagamentoPerId] impossibile" .
                    " inizializzare il prepared statement");
            $mysqli->close();
            return null;
        }

        if (!$stmt->bind_param('i', $intval)) {
            error_log("[cercaPagamentoPerId] impossibile" .
                    " effettuare il binding in input");
            $mysqli->close();
            return null;
        }

        $pagamento =  self::caricaPagamentiDaStmt($stmt)[0];
        
//        var_dump($pagamento);

        $mysqli->close();
        
        return $pagamento;
    }
    
    
    /**
     * Carica una lista di pagamenti eseguendo un prepared statement
     * @param mysqli_stmt $stmt
     * @return null
     */
    public function &caricaPagamentiDaStmt(mysqli_stmt $stmt) {
        $pagamenti = array();
        if (!$stmt->execute()) {
            error_log("[caricaPagamentiDaStmt] impossibile" .
                    " eseguire lo statement");
            return null;
        }

        $row = array();
        $bind = $stmt->bind_result(
                $row['id'], 
                $row['saldo'], 
                $row['num_carta'], 
                $row['cod_carta'], 
                $row['scadenza_carta'], 
                $row['titolare_carta'], 
                $row['tipo_carta'] 
        );
        if (!$bind) {
            error_log("[caricaPagamentiDaStmt] impossibile" .
                    " effettuare il binding in output");
            return null;
        }

        while ($stmt->fetch()) {
            $pagamenti[] = self::creaPagamentoDaArray($row);
        }

        $stmt->close();

        return $pagamenti;
    }

    /**
     * Crea e restitutisce un metodo di pagamento 
     * a partire da una riga di DB
     * @param type $row
     * @return Pagamento
     */
    public function creaPagamentoDaArray($row){
        $pagamento = new Pagamento();

        $pagamento->setId($row['id']);
        $pagamento->setSaldo($row['saldo']);
        $pagamento->setNumeroCarta($row['num_carta']);
        $pagamento->setCodiceCarta($row['cod_carta']);
        $pagamento->setScadenzaCarta($row['scadenza_carta']);
        $pagamento->setTitolareCarta($row['titolare_carta']);
        $pagamento->setTipoCarta($row['tipo_carta']);
        
//        $pagamento->setIndirizzo(IndirizzoFactory::instance()->
//                caricaIndirizzoPerId($row['indirizzo_id']));
        
//        echo var_dump($row);

        return $pagamento;
    }    
    
}

?>
