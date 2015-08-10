<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ElencoArticoli
 *
 * @author amm
 */

include_once 'Articolo.php';


class ElencoArticoli {

    private $id;
    
    /**
     * Un template per la costruzione degli articoli da inserire 
     * in una lista omogenea (stessa tipologia di pizza e stessa dimensione)
     * @var \Articolo
     */
    private $template;
    
    /**
     * La lista degli articoli inseriti
     * @var array 
     */
    private $articoli;
    
    /**
     * Costruttore lista articoli
     * @param int $id
     * @param \Articolo $articolo
     */
    public function __construct($id, Articolo &$articolo) {
        $this->id = intval($id);
        $this->template = $articolo;
        $this->articoli[] = array();
    }

    
    /**
     * Restituisce l'articolo che fa da matrice (per tipologia di pizza 
     * e dimensione) a tutti gli articoli inseriti nella lista
     * @return Articolo
     */
    public function getTemplate(){
        return $this->template;
    }        
    
    /**
     * Restituisce l'identificatore unico
     * @return int
     */
    public function getId() {
        return $this->id;
    }
    
    /**
     * Restituisce la lista di articoli
     * @return array
     */
    public function &getArticoli() {
        return $this->articoli;
    }
    
    /**
     * Aggiunge un articolo alla lista se non presente, 
     * altrimenti se presente lo modifica 
     * @param Articolo $articolo
     * @return boolean true se l'articolo è stato aggiunto/modificato 
     * correttamente, false altrimenti
     */
    public function aggiungiArticolo(Articolo $articolo) {
        $pos = $this->posizione($articolo);
        
        if($pos > -1) {
            $this->articoli[$pos]->updateQty($articolo->getQty());
            return true;            
        } elseif($pos == -1) {
            $this->articoli[] = $articolo;
            return true;
        } else {
            return false;
        }
    }
    
    /**
     * Rimuove un articolo dalla lista se presente
     * @param Articolo $articolo
     * @return boolean true se l'articolo è stato rimosso, false altrimenti
     */
    public function rimuoviArticolo(Articolo $articolo) {
        $pos = $this->posizione($articolo);
        
        if($pos > -1) {
            array_splice($this->articoli, $pos, 1);
            return true;
        }
        return false;
    }

        
    
    /**
     * Ricerca la posizione di un articolo nella lista
     * @param Articolo $articolo l'articolo da cercare
     * @return int la posizione dell'esame se presente, -1 altrimenti
     */
    private function posizione(Articolo $articolo) {
        foreach ($this->articoli as $key => $value) {
            if($value->equals($articolo))
                return $key;            
        }
        return -1;
    }
    
    
}

?>
