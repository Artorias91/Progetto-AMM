<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Articolo
 * 
 * Classe di supporto per la sessione
 * 
 *
 * @author amm
 */

include_once 'Pizza.php';


class ArticoloSession {
    
    /**
     * Incremento/Decremento del costo base della pizza in relazione alla dimensione
     */
    const SizeCost = 0.5;
    
    const Ridotta = 'ridotta';
    
    const Normale = 'normale';
    
    const Grande = 'grande';


    static $count;
    /**
     * Id articolo.
     * @var int
     */            
    private $id;
    
    /**
     * Tipo di pizza selezionata (dal form nella sidebar oppure da quello della gallery)
     * @var \Pizza|\PizzaConImg
     */        
    private $pizza;
    
    /**
     * Dimensione della pizza (ridotta|normale|grande).
     * @var string
     */        
    private $size;
    
    /**
     * Quantità di pizze
     * @var int
     */        
    private $qty;

    
    /**
     * Prezzo singola pizza in base alla dimensione
     * @var float
     */        
    private $prezzo_pizza;
    
    /**
     * Il prezzo dell'articolo
     * @var float
     */        
    private $totale;
    
    
    public function __construct($pizza, $size, $qty) {
        if(!isset(self::$count))
            self::$count = 1;
        
        self::setId(self::$count);
        
        self::setPizza($pizza);
        self::setSize($size);
        self::setQty($qty);
        
        self::calcolaPrezzoArticolo();
        
        self::$count++;
    }

    
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
    private function setId($id){
        $intVal = filter_var($id, FILTER_VALIDATE_INT, FILTER_NULL_ON_FAILURE);
        if(isset($intVal)){
            $this->id = $intVal;
            return true;
        }        
        return false;  
    }  

    /**
     * Restituisce la pizza che identifica l'articolo
     * @return Pizza
     */
    public function getPizza() {
        return $this->pizza;
    }

    /**
     * Imposta la pizza che identifica l'articolo
     * @param \Pizza $pizza la nuova pizza
     * @return boolean true se il valore e' ammissibile ed e' stato aggiornato
     * correttamente, false altrimenti
     */
    private function setPizza(Pizza $pizza) {
        $this->pizza = $pizza;
//        $this->prezzo_pizza = $pizza->getPrezzo();
        return true;
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
    private function setSize($size) {
        if(strtolower($size) == self::Normale || 
                strtolower($size) == self::Ridotta || 
                strtolower($size) == self::Grande) {
            $this->size = strtolower($size);            

            switch (strtolower($size)) {
                case self::Ridotta :
                    $this->prezzo_pizza = $this->pizza->getPrezzo() - self::SizeCost;
                    break;
                case self::Grande :
                    $this->prezzo_pizza = $this->pizza->getPrezzo() + self::SizeCost;                    
                    break;
                default :
                    $this->prezzo_pizza = $this->pizza->getPrezzo();
            }                        
            return true;            
        }
        return false;
    }
    

    /**
     * Cambia la dimensione delle pizze che fanno parte dell'articolo
     * @param string $newSize
     * @return boolean true se il valore e' ammissibile ed e' stato aggiornato
     * correttamente, false altrimenti
     */
    public function changeSize($newSize) {
        if(self::setSize($newSize)) {
            self::calcolaPrezzoArticolo();
            return true;
        }
        return false;
    }

    /**
     * Restituisce la quantità di pizze che fanno parte dell'articolo
     * @return string
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
    private function setQty($qty) {
        $intVal = filter_var($qty, FILTER_VALIDATE_INT, FILTER_NULL_ON_FAILURE);
        if(isset($intVal)) {
            $this->qty = $intVal;
            return true;
        }        
        return false;
    }

    
    /**
     * Aggiorna il numero delle pizze dell'articolo
     * @param int $addQty
     * @return boolean true se il valore e' ammissibile ed e' stato aggiornato
     * correttamente, false altrimenti
     */
    public function updateQty($addQty) {
        $intVal = filter_var($addQty, FILTER_VALIDATE_INT, FILTER_NULL_ON_FAILURE);
        if(isset($intVal)) {
            $this->qty += $intVal;
            //aggiorna il prezzo
            self::calcolaPrezzoArticolo();
            return true;
        } 
        return false;
    }
        
    /**
     * Restituisce il prezzo dell'articolo
     * @return float
     */
    public function getPrezzoArticolo() {
        return $this->totale;
//        number_format((float)$this->totale, 2, ',', '');
    }
        
    /**
     * Restituisce il prezzo di ogni pizza
     * @return float
     */
    public function getPrezzoPizza() {        
        return number_format((float)$this->prezzo_pizza, 2, ',', '');
    }

    /**
     * Calcola il prezzo dell'articolo
     * @return boolean true se il calcolo è stato effettuato con successo, 
     * false altrimenti
     */
    private function calcolaPrezzoArticolo() {
        $this->totale = $this->prezzo_pizza * $this->qty;
        return true;
    }
    
    /**
     * Confronta due oggetti di tipo Articolo
     * @param Articolo $articolo
     * @return boolean true se i due articoli (param. implicito $this
     * e param. esplicito $articolo) coincidono, false altrimenti
     */
    public function equals(ArticoloSession $articolo) {
        if(!isset($articolo))
            return false;
        
        return self::getPizza()->equals($articolo->getPizza()) && 
                self::getSize() == $articolo->getSize();
    }
    
    
}

?>
