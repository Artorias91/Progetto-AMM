<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Cliente
 *
 * @author amm
 */

include_once 'User.php';
include_once 'Pagamento.php';
include_once 'Indirizzo.php';

class Cliente extends User {

    /**
     * Metodo di pagamento del cliente.
     * @var Pagamento 
     */    
    private $pagamento;

    /**
     * Indirizzo abitativo del cliente.
     * @var Indirizzo 
     */    
    private $indirizzo;

    
    
    public function __construct() {
        parent::__construct();
        $this->setRuolo(User::Cliente);
    }
    
    /**
     * Imposta un nuovo metodo di pagamento per il cliente
     * @param Pagamento $pagamento il nuovo metodo di pagamento del cliente
     * @return boolean true or false
     */
    public function setPagamento(Pagamento $pagamento) {
        $this->pagamento = $pagamento;
        return true;
    }

    /**
     * Restituisce il metodo di pagamento del cliente
     * @return Pagamento
     */    
    public function getPagamento() {
        return $this->pagamento;
    }
    /**
     * Imposta un nuovo indirizzo abitativo del cliente
     * @param Indirizzo $indirizzo il nuovo indirizzo
     * @return boolean true or false
     */
    public function setIndirizzo(Indirizzo $indirizzo) {
        $this->indirizzo = $indirizzo;
        return true;
    }

    /**
     * Restituisce l'indirizzo abitativo del cliente
     * @return Indirizzo
     */    
    public function getIndirizzo() {
        return $this->indirizzo;
    }    
    
}

?>
