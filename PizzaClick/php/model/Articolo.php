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

include_once 'Pizza.php';


class Articolo {
    
    /**
     * Incremento del costo base della pizza nel caso la dimensione
     * sia 'Grande'
     */
    const IncrCost = 0.5;

    /**
     * Decremento del costo base della pizza nel caso la dimensione
     * sia 'ridotta'
     */    
    const DecrCost = -0.5;

    
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
    private $prezzo;
    
    /**
     * L'ordine a cui l'articolo appartiene
     * @var \Ordine
     */        
    private $ordine;
    
    
    
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
     * @return string
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
        $this->prezzo_pizza = $pizza->getPrezzo();
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
                    $this->prezzo_pizza += self::DecrCost;
                    break;
                case self::Grande :
                    $this->prezzo_pizza += self::IncrCost;                    
                    break;
                default :
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
        return $this->prezzo;
    }
        
    /**
     * Restituisce il prezzo di ogni pizza
     * @return float
     */
    public function getPrezzoPizza() {
        return $this->prezzo_pizza;
    }

    /**
     * Calcola il prezzo dell'articolo
     * @return boolean true se il calcolo è stato effettuato con successo, 
     * false altrimenti
     */
    private function calcolaPrezzoArticolo() {
        $this->prezzo = $this->prezzo_pizza * $this->qty;
        return true;
    }
    
    /**
     * Confronta due oggetti di tipo Articolo
     * @param Articolo $articolo
     * @return boolean true se i due articoli (param. implicito $this
     * e param. esplicito $articolo) coincidono, false altrimenti
     */
    public function equals(Articolo $articolo) {
        if(!isset($articolo))
            return false;
        
        return self::getPizza()->equals($articolo->getPizza()) && 
                self::getSize() == $articolo->getSize();
    }
    
    
}

?>
