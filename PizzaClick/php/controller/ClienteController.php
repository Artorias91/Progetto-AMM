<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ClienteController
 *
 * @author amm
 */
include_once 'BaseController.php';
include_once basename(__DIR__) . '/../view/ViewDescriptor.php';
include_once basename(__DIR__) . '/../model/UserFactory.php';

include_once basename(__DIR__) . '/../model/PizzaFactory.php';
include_once basename(__DIR__) . '/../model/Pizza.php';
include_once basename(__DIR__) . '/../model/Articolo.php';

include_once basename(__DIR__) . '/../model/Articolo.php';

class ClienteController extends BaseController {

    const elenco_articoli = 'elenco_articoli';
    
    public function __construct() {
        parent::__construct();
    }
    
    public function handle_input(&$request) {
        
        $vd = new ViewDescriptor();
        
        $vd->setPagina($request['page']);
        
        $this->setImpToken($vd, $request);
                
        //untente non autentificato
        if(!$this->loggedIn()) {
            $this->showLoginPage($vd);
        }//utente autentificato
        else {
            $user = UserFactory::instance()->cercaUtentePerId(
                $_SESSION[BaseController::user], $_SESSION[BaseController::role]);
            
            {//carica le pizze da visualizzare sulla galleria
                $listaPizzeConImg = PizzaFactory::instance()->getListaPizze(true);
                reset($listaPizzeConImg);
                $pizza = current($listaPizzeConImg);    
                $index = 0;
                //        var_dump($listaPizzeConImg);
                $vd->setAjaxFile(basename(__DIR__) . '/../view/ajax.php');        
            }
            
            if(isset($request["subpage"])) {

                switch ($request["subpage"]) {
                    case 'base':
                        $vd->setSottoPagina('base');
                        $vd->setBreadcrumb("Modifica username o e-mail");
                        
                        break;
                    case 'password':
                        $vd->setSottoPagina('password');
                        $vd->setBreadcrumb("Modifica password");
                        
                        break;
                    case 'pagamento':
                        $vd->setSottoPagina('pagamento');
                        $vd->setBreadcrumb("Visualizza i tuoi metodi di pagamento");
                        $pagamenti = PagamentoFactory::instance()->getListaPagamentiPerCliente($user);
                        
                        break;
                    case 'visualizza_pagamento':
                        $vd->setSottoPagina('visualizza_pagamento');
                        $vd->setBreadcrumb("Visualizza i tuoi metodi di pagamento");
                        
                        break;
                    case 'indirizzo':
                        $vd->setSottoPagina('indirizzo');
                        $vd->setBreadcrumb("Modifica indirizzo di consegna");

                        break;
                    case 'account':
                        $msg = array();
                        if (!isset($_SESSION[self::elenco_articoli])) {
                            $_SESSION[self::elenco_articoli] = array();
                        }     
                        
                        $vd->setSottoPagina('account');
                        break;
                    default:
                        $msg = array();
                        if (!isset($_SESSION[self::elenco_articoli])) {
                            $_SESSION[self::elenco_articoli] = array();
                        }     
                        
                        $vd->setSottoPagina('home');
                        break;
                }
            }
            
            if(isset($request["cmd"])) {
                switch ($request["cmd"]) {
                    case 'remove':
                        if(isset($request['key'])) {
                            $key = intval($request['key']);
                            if(array_key_exists($key, $_SESSION[self::elenco_articoli])) {
                                $this->rimuoviArticolo($_SESSION[self::elenco_articoli][$key]);
                            }
                        }
                        $vd->setSottoPagina('home');
                        $this->showHomeCliente($vd);

                        header('Location: ' . Settings::getApplicationPath() . 'php/cliente/home');
                        exit();

                        break;
                    
                    /**
                     * è stato aggiunto al carrello un "articolo", 
                     * ovvero una quantità di pizze di un determinato tipo e di una certa dimensione
                     */
                    case 'add':
//                        file_put_contents('php/text.txt', $request['pizza-gallery'], FILE_APPEND);
//                        file_put_contents('php/text.txt', "\r\n", FILE_APPEND);
//                        file_put_contents('php/text.txt', $request['quantity'], FILE_APPEND);
//                        file_put_contents('php/text.txt', "\r\n", FILE_APPEND);
//                        file_put_contents('php/text.txt', $request['size'], FILE_APPEND);

                        //controlla la provenienza della richiesta: form's sidebar ...
                        if(isset($request['pizza-selection']) && isset($request['size']) && isset($request['quantity'])) {
                            
                            $articolo = new Articolo(PizzaFactory::instance()->
                                    cercaPizzaPerId(intval($request['pizza-selection'])), $request['size'], intval($request['quantity']));
                            
                            $this->aggiungiArticolo($articolo);
                        }
                        //... altrimenti form's gallery 
                        if(isset($request['pizza-gallery']) && isset($request['size']) && isset($request['quantity'])) {

                            $key = intval($request['pizza-gallery']);
                            if(array_key_exists($key, $listaPizzeConImg)) {
                                $articolo = new Articolo(PizzaFactory::instance()->
                                        cercaPizzaPerId($listaPizzeConImg[$key]->getId()), $request['size'], intval($request['quantity']));
                                                            
                            $this->aggiungiArticolo($articolo);                              
                            }
                        }
                        file_put_contents('php/text.txt', $_SERVER['REQUEST_URI'], FILE_APPEND);

                        $vd->setSottoPagina('home');
                        $this->showHomeCliente($vd);
                        /**
                         * Evita l'auto-submit del form in seguito alla ricarica della pagina nel browser.
                         * Problema presente anche nel progetto d'esempio:
                         * http://spano.sc.unica.it/amm2014/davide/esami14/php/docente/reg_esami_step1?cmd=r_nuovo
                         */
                        header('Location: ' . Settings::getApplicationPath() . 'php/cliente/home');
                        exit();
                        
                        break;
                    
                    case 'v_pagamento':
                        $msg = array();
                        
                        $carta = new Pagamento;
                        $pagamenti = PagamentoFactory::instance()->getListaPagamentiPerCliente($user);
                        
                        if (isset($request['carta'])) {
                            $intVal = filter_var($request['carta'], FILTER_VALIDATE_INT, FILTER_NULL_ON_FAILURE);
                            if (!isset($intVal) || $intVal < 1 || $intVal > count($pagamenti)) {
                                $msg[] = '<li>Il metodo di pagamento specificato non &egrave; corretto</li>';
                            }
//                            $this->creaFeedbackUtente($vd, array(), array(), '');
                            $carta = $pagamenti[$intVal - 1];
                        }          
                        
                        $vd->setSottoPagina('visualizza_pagamento');
                        $vd->setBreadcrumb("Visualizza i tuoi metodi di pagamento");                            
                        $this->showHomeUser($vd);                        
                        break;
                    
                    case 'password':
                        $msg_errori = array();
                        $msg_conferme = array();
                        $ifMsg = "cambia la password";
                        
                        $this->aggiornaPassword($user, $request, $msg_errori, $msg_conferme);
                        $this->creaFeedbackUtente($vd, $msg_errori, $msg_conferme, $ifMsg);                        
                        
                        $vd->setSottoPagina('password');
                        $vd->setBreadcrumb("Modifica password");
                        
                        $this->showHomeUser($vd);                        
                        break;
                    case 'base':
                        $msg_errori = array();
                        $msg_conferme = array();
                        $ifMsg = "aggiorna username o e-mail";
                        
                        $this->aggiornaUsername($user, $request, $msg_errori, $msg_conferme);

                        $this->aggiornaEmail($user, $request, $msg_errori, $msg_conferme);
                        
//                        $this->aggiornaTelefono($user, $request, $msg_errori, $msg_conferme);
                        
                        $this->creaFeedbackUtente($vd, $msg_errori, $msg_conferme, $ifMsg);                       
                        
                        $vd->setSottoPagina('base');
                        $vd->setBreadcrumb("Modifica username o e-mail");

                        
                        $this->showHomeUser($vd);

                        break;
                    case 'logout':
                        $this->logout($vd);
                        break;                    
                    case 'go':
                        $vd->toggleJson();

                        $index = filter_var($request["id"], FILTER_VALIDATE_INT, FILTER_NULL_ON_FAILURE);
                        
                        if(!array_key_exists($index, $listaPizzeConImg))
                            $index = 0;
                        
                        $pizza = $listaPizzeConImg[$index];

                        break;
                    case 'next':
                        $vd->toggleJson();

                        $index = filter_var($request["id"], FILTER_VALIDATE_INT, FILTER_NULL_ON_FAILURE);
                        
                        if (!isset($index))
                            $intVal = 0;                    
                        
                        $index++;
                        
                        if($index > count($listaPizzeConImg) - 1)
                            $index = 0;
                        
                        $pizza = $listaPizzeConImg[$index];

                        break;
                    case 'prev':
                        $vd->toggleJson();

                        $index = filter_var($request["id"], FILTER_VALIDATE_INT, FILTER_NULL_ON_FAILURE);

                        if (!isset($index))
                            $index = 0;                    

                        $index--;

                        if($index < 0)
                            $index = count($listaPizzeConImg) - 1;

                        $pizza = $listaPizzeConImg[$index];                            

                        break;
                    default : $this->showHomeCliente($vd);
                } 
            } else {// nessun comando
                $user = UserFactory::instance()->cercaUtentePerId(
                        $_SESSION[BaseController::user], $_SESSION[BaseController::role]);
                $this->showHomeUser($vd);
            }
        }
 
        require basename(__DIR__) . '/../view/master.php';
    }
    
    private function getQtyTotalePizze() {
        $totale = 0;
        if(!empty($_SESSION[self::elenco_articoli])) {
            foreach ($_SESSION[self::elenco_articoli] as $value) {
                $totale += $value->getQty();            
            }            
        }
        return $totale;      
    }

    private function getPrezzoTotale() {
        $totale = 0;
        if(!empty($_SESSION[self::elenco_articoli])) {
            foreach ($_SESSION[self::elenco_articoli] as $value) {
                $totale += $value->getPrezzoArticolo();            
            }            
        }
        return $totale;        
    }

    /**
     * Aggiunge un articolo alla lista se non presente, 
     * altrimenti se presente lo modifica 
     * @param Articolo $articolo
     * @return boolean true se l'articolo è stato aggiunto/modificato 
     * correttamente, false altrimenti
     */
    private function aggiungiArticolo(Articolo $articolo) {
        $pos = $this->posizione($articolo);
        
        if($pos > -1) {
            $_SESSION[self::elenco_articoli][$pos]->updateQty($articolo->getQty());
            return true;            
        } elseif($pos == -1) {
            $_SESSION[self::elenco_articoli][] = $articolo;
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
    private function rimuoviArticolo(Articolo $articolo) {
        $pos = $this->posizione($articolo);
        
        if($pos > -1) {
            array_splice($_SESSION[self::elenco_articoli], $pos, 1);
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
        foreach ($_SESSION[self::elenco_articoli] as $key => $value) {
            if($value->equals($articolo))
                return $key;            
        }
        return -1;
    }    
    
}

?>
