<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Pizza
 *
 * @author amm
 */
class Pizza {    
    
    private $id;
    
    private $nome;
    
    private $ingredienti_extra;
    
    private $prezzo;
    
    //opzionale
    private $url_img;

    
    public function __construct() {}

    
    public function getId() {
        return $this->id;
    }
    
    public function setId($id) {
        $intVal = filter_var($id, FILTER_VALIDATE_INT, FILTER_NULL_ON_FAILURE);
        if(isset($intVal)){
            $this->id = $intVal;
            return true;
        }
        
        return false;    
    }
    
    public function getNome() {
        return $this->nome;
    }    
    
    public function setNome($nome) {
        $this->nome = $nome;
        return true;
    }
    
    public function getIngredientiExtra() {
        return $this->ingredienti_extra;
    }    
    
    public function setIngredientiExtra($ingredienti) {
        if($ingredienti == NULL)
            $ingredienti = 'nessuno';
        $this->ingredienti_extra = $ingredienti;
        return true;
    }
    
    public function getPrezzo() {
        return $this->prezzo;
//        return number_format((float)$this->prezzo, 2, ',', '');
    }    
    
    public function setPrezzo($prezzo) {
        $floatVal = filter_var($prezzo, FILTER_VALIDATE_FLOAT, FILTER_NULL_ON_FAILURE);
        
        if(isset($floatVal)) {
            $this->prezzo = $floatVal;
            return true;            
        }
        return false;    
    }
    
    public function getUrlImg() {
        return $this->url_img;
    }
    
    public function setUrlImage($url) {
        if(file_exists('../' . $url)) {
            $this->url_img = '../' . $url;
            return true;            
        }
        return false;  
    }
    
    /**
     * Confronta due oggetti di tipo Pizza
     * @param Pizza $pizza
     * @return boolean true se le due pizze (param. implicito $this
     * e param. esplicito $pizza) coincidono, false altrimenti
     */
    public function equals(Pizza $pizza) {
        if(!isset($pizza))
            return false;
        
        return $this->id == $pizza->getId() &&
            $this->nome == $pizza->getNome();
    }
        
    
}

?>
