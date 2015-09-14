<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Ordine
 *
 * @author amm
 */


class Ordine {
    //put your code here
    private $id;
    
    private $subtotale;
    
    private $data_creazione;
    
    private $data_conclusione;
    
    private $articoli;
    
    private $cliente;
    
    
    public function __construct() {}
    
   
    /**
     * Restituisce l'identificatore dell'ordine
     * @return int
     */
    public function getId() {
        return $this->id;
    }
    
    /**
     * Imposta un nuovo identificatore per l'ordine
     * @param int $id
     */
    public function setId($id){
        $intVal = filter_var($id, FILTER_VALIDATE_INT, FILTER_NULL_ON_FAILURE);
        if(isset($intVal)){
            $this->id = $intVal;
            return true;
        }        
        return false;  
    }  
    
    /**
     * Restituisce il cliente che ha eseguito l'ordine
     * @return int
     */
    public function getCliente() {
        return $this->cliente;
    }
    
    /**
     * Imposta il cliente che ha eseguito l'ordine
     * @param int $id
     */
    public function setCliente(Cliente $cliente){    
        $this->cliente = $cliente;  
    }      
    
    public function getSubtotale() {
        return number_format((float)$this->subtotale, 2, ',', '');
    }
    
    public function setSubtotale($prezzo) {
        $floatVal = filter_var($prezzo, FILTER_VALIDATE_FLOAT, FILTER_NULL_ON_FAILURE);
        if(isset($floatVal)){
            $this->subtotale = $floatVal - 3.;
            return true;
        }        
        return false;    
    }
    
    
    public function getOraCreazione() {
        return $this->data_creazione->format('h:i');
    }     
    
    public function getDataCreazione() {
        return $this->data_creazione->format('d-M-y h:i');
    }     
    
    public function setDataCreazione($timestamp) {
        $this->data_creazione = DateTime::createFromFormat('d-M-y h:i', 
                        date('d-M-y h:i', strtotime($timestamp)));        
    }
    
    public function getDataConclusione() {
        return $this->data_conclusione;
    }


    public function setDataConclusione($timestamp) {
        !($timestamp === '0000-00-00 00:00:00') ? $this->data_conclusione = 'spedito' : $this->data_conclusione = 'in corso';        
    }    
    
    public function setArticoli($array) {
        $this->articoli = $array;
    }
    
    public function getArticoli() {
        return $this->articoli;
    }
    
}

?>
