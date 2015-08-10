<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Indirizzo
 *
 * @author amm
 */
class Indirizzo {
    //put your code here

    /**
     * L'identificatore dell'Indirizzo
     * @var int
     */
    private $id;    
    
    /**
     * Via e numero civico residenza
     * @var string
     */    
    private $indirizzo;
    
    /**
     * Nome e cognome destinatario residenza
     * @var string
     */    
    private $destinatario;
    
    /**
     * Citta relativa all'indirizzo della residenza
     * @var string
     */
    private $citta;
    
    /**
     * Provincia relativa all'indirizzo della residenza
     * @var string
     */
    private $provincia;
    
    /**
     * Cap relativo all'indirizzo della residenza
     * Max di cinque cifre
     * @var string 
     */
    private $cap;
    
    /** 
     * recapito telefonico dell'utente
     * @var string
     */
    private $telefono;
    
    
    public function __construct() {}

    
    /**
     * Restituisce l'identificatore dell'Indirizzo
     * @return int
     */
    public function getId() {
        return $this->id;
    }
    
    /**
     * Imposta un nuovo identificatore per l'Indirizzo
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
     * Restituisce via e num. civico della residenza
     * @return string
     */
    public function getNomeIndirizzo() {
        return $this->indirizzo;
    }

    /**
     * Imposta via e num. civico della residenza
     * @param string $nome il nuovo indirizzo
     * @return boolean true se il valore e' ammissibile ed e' stato aggiornato
     * correttamente, false altrimenti
     */
    public function setNomeIndirizzo($nome) {
        $this->indirizzo = $nome;
        return true;
    }    
    
       
    /**
     * Restituisce il nome completo del destinatario
     * @return string
     */
    public function getDestinatario() {
        return $this->destinatario;
    }

    /**
     * Imposta il nome completo del destinatario
     * @param string $nome il nuovo indirizzo
     * @return boolean true se il valore e' ammissibile ed e' stato aggiornato
     * correttamente, false altrimenti
     */
    public function setDestinatario($nome) {
        $this->destinatario = $nome;
        return true;
    }

    /**
     * Imposta la citta relativa all'indirizzo della residenza
     * @param string $citta la nuova citta'
     * @return boolean true se il valore e' stato aggiornato correttamente,
     * false altrimenti
     */
    public function setCitta($citta) {
        $this->citta = $citta;
        return true;
    }

    /**
     * Restituisce la citta' relativa all'indirizzo della residenza
     * @return string
     */
    public function getCitta() {
        return $this->citta;
    }

    /**
     * Imposta la provincia relativa all'indirizzo della residenza
     * @param string $provincia la nuova provincia
     * @return boolean true se il valore e' stato aggiornato correttamente,
     * false altrimenti
     */
    public function setProvincia($provincia) {
        $this->provincia = $provincia;
        return true;
    }

    /**
     * Restituisce la provincia relativa all'indirizzo della residenza
     * @return string
     */
    public function getProvincia() {
        return $this->provincia;
    }

    /**
     * Restituisce il cap relativo all'indirizzo della residenza
     * @return int
     */
    public function getCap() {
        return $this->cap;
    }

    /**
     * Imposta il cap relativo all'indirizzo della residenza
     * @param string il nuovo $cap
     * @return boolean true se il nuovo valore e' ammissibile ed e' stato 
     * impostato, false altrimenti
     */
    public function setCap($cap) {
        if (!filter_var($cap, FILTER_VALIDATE_REGEXP, array('options' => array('regexp' => '/[0-9]{5}/')))) {
            return false;
        }
        $this->cap = $cap;
        return true;
    }

    
    /**
     * Restituisce il numero di telefono del cliente
     * @return string
     */
    public function getTelefono() {
        return $this->telefono;
    }        

    /**
     * Imposta un nuovo numero di telefono per l'utente
     * @param string $num il nuovo numero di telefono dell'utente
     * @return boolean true or false
     */    
    public function setTelefono($num) {
        if (!filter_var($num, FILTER_VALIDATE_REGEXP, array('options' => array('regexp' => '/^\d{9}(\d{1})?$/')))) {
            return false;
        }
        $this->telefono = $num; 
        return true;
    }          
    
}

?>
