<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Pagamento
 *
 * @author amm
 */

include_once 'Indirizzo.php';

class Pagamento {
    //put your code here
    
    /**
     * L'identificatore del Pagamento
     * @var int
     */
    private $id;    
    
    /**
     * Il numero della carta di credito
     * 16 cifre
     * @var string
     */    
    private $num_carta;
    
    /**
     * La data di scadenza della carta di credito
     * @var DateTime
     */        
    private $scadenza_carta;

    /**
     * Il codice di sicurezza della carta di credito
     * 3 cifre
     * @var string
     */    
    private $cod_carta;

    
    /**
     * Il nome del titolare a cui è associata la carta di credito
     * @var string
     */        
    private $titolare_carta;

    /**
     * Saldo della carta.
     * @var float 
     */    
    private $saldo;    
    
    /**
     * Indirizzo associato al metodo di pagamento.
     * @var Indirizzo 
     */    
    private $indirizzo;    
    
    /**
     * il tipo della carta di credito (per esempio MasterCard).
     * @var string 
     */    
    private $tipo_carta;    
    
    
    public function __construct() {}
    
    /**
     * Restituisce l'identificatore del Pagamento
     * @return int
     */
    public function getId() {
        return $this->id;
    }
    
    /**
     * Imposta un nuovo identificatore per il Pagamento
     * @param int $id
     */
    public function setId($id){
        $this->id = $id;
    }                
 
    /**
     * Restituisce il numero della carta di credito
     * @return string
     */    
    public function getNumeroCarta() {
        return $this->num_carta;
    }
    
    /**
     * Imposta un nuovo numero per la carta di credito
     * @param string $numero
     * @return boolean true successo, false altrimenti     
     */    
    public function setNumeroCarta($numero) {
        if (!filter_var($numero, FILTER_VALIDATE_REGEXP, array('options' => array('regexp' => '/^\d{16}$/')))) {
            return false;
        }
        $this->num_carta = $numero;
        
        return true;
    }

    
    /**
     * Restituisce il codice di sicurezza della carta di credito
     * @return string
     */        
    public function getCodiceCarta() {
        return $this->cod_carta;
    }
    
    /**
     * Imposta un nuovo codice di sicurezza per la carta di credito
     * @param string $codice
     * @return boolean true successo, false altrimenti     
     */      
    public function setCodiceCarta($codice) {
        if (!filter_var($codice, FILTER_VALIDATE_REGEXP, array('options' => array('regexp' => '/^\d{3}$/')))) {
            return false;
        }
        $this->cod_carta = $codice;
        
        return true;
    }    


    /**
     * Restituisce la data di scadenza della carta di credito
     * @return string formato yyyy-mm-dd
     */            
    public function getScadenzaCarta() {
        return $this->scadenza_carta->format('Y-m-d');
    }    
    
    /**
     * Restituisce il mese della data di scadenza della carta di credito
     * @return string formato - mm
     */            
    public function getMeseScadenza() {
        return $this->scadenza_carta->format('m');
    }        
    
    /**
     * Restituisce l'anno della data di scadenza della carta di credito
     * @return string le ultime due cifre - yy
     */            
    public function getAnnoScadenza() {
        return $this->scadenza_carta->format('y');
    }            
    
    /**
     * Imposta una nuova data di scadenza per la carta di credito
     * @param string $scadenza
     * @return boolean true successo, false altrimenti     
     */      
    public function setScadenzaCarta($scadenza) {
        if (!filter_var($scadenza, FILTER_VALIDATE_REGEXP, array('options' => 
            array('regexp' => '/[0-9]{2}-(0[1-9]|1[0-2])/')))) {
            return false;
        }
        
        $this->scadenza_carta = DateTime::createFromFormat('Y-m-d', 
                date('Y-m-d', strtotime($scadenza)));

        return true;
    }         
    
    /**
     * Restituisce il nome del titolare della carta di credito
     * @return string
     */            
    public function getTitolareCarta() {
        return $this->titolare_carta;
    }

    /**
     * Imposta un nuovo titolare della carta di credito
     * @param string $titolare
     * @return boolean true successo, false altrimenti     
     */      
    public function setTitolareCarta($titolare) {
        $this->titolare_carta = $titolare;
        return true;
    }    
    
    /**
     * Restituisce il saldo attuale
     * @return float
     */    
    public function getSaldo() {
        return $this->saldo;
    }    
    
     /**
     * Imposta un nuovo saldo
     * @param float $saldo il nuovo saldo
     * @return boolean true or false
     */
    public function setSaldo($saldo) {
        $floatVal = filter_var($saldo, FILTER_VALIDATE_FLOAT, FILTER_NULL_ON_FAILURE);
        
        if(isset($floatVal)) {
            $this->saldo = $floatVal;
            return true;            
        }
        return false;
    }    
    
    /**
     * Restituisce l'indirizzo associato al metodo di pagamento
     * @return Indirizzo
     */    
    public function getIndirizzo() {
        return $this->indirizzo;
    }    
    
     /**
     * Imposta un nuovo indirizzo associato al metodo di pagamento
     * @param Indirizzo $indirizzo il nuovo indirizzo
     * @return boolean true or false
     */
    public function setIndirizzo(Indirizzo $indirizzo) {
        $this->indirizzo = $indirizzo;
        return true;
    }
    
    /**
     * Restituisce il tipo di carta di credito
     * @return string
     */    
    public function getTipoCarta() {
        return $this->tipo_carta;
    }    
    
     /**
     * Imposta un nuovo tipo di carta di credito
     * @param string il nuovo tipo
     * @return boolean true or false
     */
    public function setTipoCarta($tipo) {
        $this->tipo_carta = $tipo;
        return true;
    }
    
}

?>
