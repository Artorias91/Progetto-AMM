<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Articolo
 *
 * @author amm
 */
class Articolo {
    //put your code here
    
    const Ridotta = 'ridotta';
    
    const Normale = 'normale';
    
    const Grande = 'grande';    
    
    private $id;
    
    private $pizza_id;
        
    private $qty;
    
    private $size;
    
    private $prezzo;


    public function __construct() {}

    
    /**
     * Restituisce l'identificatore dell'articolo
     * @return int
     */
    public function getId() {
        return $this->id;
    }
    
    /**
     * Imposta un nuovo identificatore per l'articolo
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
     * Restituisce l'identificatore della pizza
     * @return int
     */
    public function getPizzaId() {
        return $this->pizza_id;
    }
    
    /**
     * Imposta un nuovo identificatore per la pizza
     * @param int $id
     */
    public function setPizzaId($id){
        $intVal = filter_var($id, FILTER_VALIDATE_INT, FILTER_NULL_ON_FAILURE);
        if(isset($intVal)){
            $this->pizza_id = $intVal;
            return true;
        }        
        return false;  
    }      

    /**
     * Restituisce il prezzo dell'articolo
     * @return float
     */
    public function getPrezzo() {
        return number_format((float)$this->prezzo, 2, ',', '');
    }
    
    /**
     * Imposta il prezzo dell'articolo
     * @param float $prezzo
     */
    public function setPrezzo($prezzo){
        $floatVal = filter_var($prezzo, FILTER_VALIDATE_FLOAT, FILTER_NULL_ON_FAILURE);
        if(isset($floatVal)){
            $this->prezzo = $floatVal;
            return true;
        }        
        return false;  
    }          
    
    /**
     * Restituisce la dimensione delle pizze che fanno parte dell'articolo
     * @return string
     */
    public function getSize() {
        return $this->size;
    }
    
    /**
     * Imposta la dimensione delle pizze che fanno parte dell'articolo
     * @param string $size dimensione
     * @return boolean true se il valore e' ammissibile ed e' stato aggiornato
     * correttamente, false altrimenti
     */
    public function setSize($size) {
        if(strtolower($size) == self::Normale || 
                strtolower($size) == self::Ridotta || 
                strtolower($size) == self::Grande) {
            $this->size = strtolower($size);            
            return true;            
        }
        return false;
    }        
    
    /**
     * Restituisce la quantità di pizze che fanno parte dell'articolo
     * @return int
     */
    public function getQty() {
        return $this->qty;
    }
    
    /**
     * Imposta la quantità di pizze che fanno parte dell'articolo
     * @param int $qty quantità
     * @return boolean true se il valore e' ammissibile ed e' stato aggiornato
     * correttamente, false altrimenti
     */
    public function setQty($qty){
        $intVal = filter_var($qty, FILTER_VALIDATE_INT, FILTER_NULL_ON_FAILURE);
        if(isset($intVal)){
            $this->qty = $intVal;
            return true;
        }        
        return false;  
    }          
    
}

?>
