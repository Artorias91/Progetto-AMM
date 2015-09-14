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
include_once basename(__DIR__) . '/../model/ArticoloSession.php';
include_once basename(__DIR__) . '/../model/OrdineFactory.php';


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
                    case 'conferma_ordine_step1':

                        $vd->setSottoPagina('conferma_ordine_step1');
                        
                        $vd->setTitoloStep('Passo 1: seleziona indirizzo di consegna');
                        
                        break;
                    case 'conferma_ordine_step2':

                        $vd->setSottoPagina('conferma_ordine_step2');
                        
                        $vd->setTitoloStep('Passo 2: riepilogo articoli');
                        
                        break;
                    case 'conferma_ordine_step3':
                        
                        $vd->setSottoPagina('conferma_ordine_step3');

                        $vd->setTitoloStep('Passo 3: seleziona metodo di pagamento');
                        
                        $pagamenti = PagamentoFactory::instance()->getListaPagamentiPerCliente($user);
                                                
                        break;
                    case 'cronologia_ordini':
                        $ordini = OrdineFactory::instance()->getListaOrdiniPerCliente($user);
                        $vd->setSottoPagina('cronologia_ordini');
                        $vd->setBreadcrumb("Visualizza cronologia ordini");
                        
                        break;
                    case 'ordini_attivi':
                        $ordini = OrdineFactory::instance()->getListaOrdiniPerCliente($user, true);
                        $vd->setSottoPagina('cronologia_ordini');
                        $vd->setBreadcrumb("Visualizza ordini in corso");
                        
                        break;

                    default:
                        if (!isset($_SESSION[self::elenco_articoli])) {
                            $_SESSION[self::elenco_articoli] = array();
                        }
                        $vd->setSottoPagina('home');
                        break;
                }
            }
            
            if(isset($request["cmd"])) {
                switch ($request["cmd"]) {
                    //salvataggio permanente elenco articoli
                    case 'ordina':
                        $msg = array();
                    
                        
                        if(!empty($_SESSION[self::elenco_articoli])) {
                            if(isset($request['carta'])) {
                                $carta = intval($request['carta']);
                                
                                $pagamento = PagamentoFactory::instance()->
                                        cercaPagamentoPerId($carta);
                                
                                if(!isset($pagamento)) {
                                    $msg[] = 'Il metodo di pagamento inserito non &egrave; valido';
                                    $vd->setTitoloStep('Passo 3: seleziona metodo di pagamento');
                                    $vd->setSottoPagina('conferma_ordine_step3');                                    
                                } else {
                                    if ( ! OrdineFactory::instance()->
                                            salvaOrdine($_SESSION[self::elenco_articoli], 
                                                    $user->getId(), $pagamento, $this->getSubTotale(true)) ) {
                                        $msg[] = '<li>Spiacente. Impossibile terminare la transazione</li>';
                                        $vd->setTitoloStep('Passo 3: seleziona metodo di pagamento');
                                        $vd->setSottoPagina('conferma_ordine_step3');
                                    } 
                                    else {
                                        $_SESSION[self::elenco_articoli] = array();
                                        $vd->setSottoPagina('home');                                    
                                    }                                    
                                }                                
                            }
                            
                            $this->creaFeedbackUtente($msg, $vd, 'Ordine inviato');
                        }
                        
                        $this->showHomeCliente($vd);
                        $pagamenti = PagamentoFactory::instance()->getListaPagamentiPerCliente($user);                        

                        break;
                    
                    case 'remove':
                        if(isset($request['key'])) {
                            $key = intval($request['key']);
                            if(array_key_exists($key, $_SESSION[self::elenco_articoli])) {
                                $this->rimuoviArticolo($_SESSION[self::elenco_articoli][$key]);
                            }
                        }

                        $subpage = $_REQUEST['subpage'];      
                        
                        if(empty($_SESSION[self::elenco_articoli])) {
                            $subpage = 'home';
                            $vd->setSottoPagina('home');                        
                        } 
                        
                        $this->showHomeCliente($vd);

                        header('Location: ' . Settings::getApplicationPath() . "php/cliente/$subpage");
                        exit();

                        break;
                    
                    /**
                     * è stato aggiunto al carrello un "articolo", 
                     * ovvero una quantità di pizze di un determinato tipo e di una certa dimensione
                     */
                    case 'add':
                        //controlla la provenienza della richiesta: form's sidebar ...
                        if(isset($request['pizza-selection']) && 
                                isset($request['size']) && 
                                isset($request['quantity'])) {
                            
                            $articolo = 
                                new ArticoloSession(
                                        PizzaFactory::instance()->cercaPizzaPerId(intval($request['pizza-selection'])), 
                                        $request['size'], 
                                        intval($request['quantity']));
                            
                            $this->aggiungiArticolo($articolo);
                        }
                        //... altrimenti form's gallery 
                        if(isset($request['pizza-gallery']) && 
                                isset($request['size']) && 
                                isset($request['quantity'])) {

                            $key = intval($request['pizza-gallery']);
                            if(array_key_exists($key, $listaPizzeConImg)) {
                                $articolo = 
                                    new ArticoloSession(
                                            PizzaFactory::instance()->
                                            cercaPizzaPerId($listaPizzeConImg[$key]->getId()), 
                                            $request['size'], 
                                            intval($request['quantity']));
                                                            
                                $this->aggiungiArticolo($articolo); 
                            }
                        }
//                        file_put_contents('php/text.txt', $_SERVER['REQUEST_URI'], FILE_APPEND);

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
                            $carta = $pagamenti[$intVal - 1];
                            $vd->setSottoPagina('visualizza_pagamento');                        
                        } else {
//                            echo 'problema';
                            $vd->setSottoPagina('pagamento');                        
                        }          
                        
                        $vd->setBreadcrumb("Visualizza i tuoi metodi di pagamento");                            
                        $this->showHomeUser($vd);                        
                        break;
                    case 'aggiorna_indirizzo':
                        $msg = array();                       
                        $this->aggiornaIndirizzo($user, $request, $msg);
                        $this->creaFeedbackUtente($msg, $vd, "Indirizzo di consegna aggiornato");
                        $this->showHomeUser($vd);                        
                        
                        break;
                    case 'aggiorna_password':
                        $msg = array();
                        
                        $this->aggiornaPassword($user, $request, $msg);
                        $this->creaFeedbackUtente($msg, $vd, 'Password aggiornata');                        
                        
                        $vd->setSottoPagina('password');
                        $vd->setBreadcrumb("Modifica password");
                        
                        $this->showHomeUser($vd);                        
                        break;
                    case 'aggiorna_info_base':
                        $msg = array();
                        
                        $vd->setSottoPagina('base');
                        $vd->setBreadcrumb("Modifica username o e-mail");
                         
                        $this->aggiornaInfoBase($user, $request, $msg);
                        
                        $this->creaFeedbackUtente($msg, $vd, 'Username/e-mail sono stati aggiornati');                       
                                               
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

    /**
     * Calcola il prezzo dell'ordine attuale
     * @param boolean $flag abilita/disabilita il calcolo del prezzo di spedizione
     * @return float prezzo dell'ordine
     */
    private function getSubTotale($flag = false) {
        (!$flag) ? $totale = 0 : $totale = 3.;
        if(!empty($_SESSION[self::elenco_articoli])) {
            foreach ($_SESSION[self::elenco_articoli] as $value) {
                $totale += $value->getPrezzoArticolo();            
            }            
        }
        
        
        return number_format((float)$totale, 2, ',', '');        
    }
    

    /**
     * Aggiunge un articolo alla lista se non presente, 
     * altrimenti se presente lo modifica 
     * @param Articolo $articolo
     * @return boolean true se l'articolo è stato aggiunto/modificato 
     * correttamente, false altrimenti
     */
    private function aggiungiArticolo(ArticoloSession $articolo) {
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
    private function rimuoviArticolo(ArticoloSession $articolo) {
        $pos = $this->posizione($articolo);
        
        if($pos > -1) {
            array_splice($_SESSION[self::elenco_articoli], $pos, 1);
            return true;
        }
        return false;
    }

        
    
    /**
     * Ricerca la posizione di un articolo nella lista
     * @param ArticoloSession $articolo l'articolo da cercare
     * @return int la posizione dell'esame se presente, -1 altrimenti
     */
    private function posizione(ArticoloSession $articolo) {
        foreach ($_SESSION[self::elenco_articoli] as $key => $value) {
            if($value->equals($articolo))
                return $key;            
        }
        return -1;
    }    

    /**
     * Aggiorno l'indirizzo di un utente (comune a Studente e Docente)
     * @param User $user l'utente da aggiornare
     * @param array $request la richiesta http da gestire
     * @param array $msg riferimento ad un array da riempire con eventuali
     * messaggi d'errore
     */
    protected function aggiornaIndirizzo(Cliente $user, &$request, &$msg) {
        if (isset($request['destinatario'])) {
            if (!$user->getIndirizzo()->
                    setDestinatario($request['destinatario'])) {
                $msg[] = '<li>Il formato del numero civico non &egrave; corretto</li>';
            }
        }
        if (isset($request['indirizzo'])) {
            if (!$user->getIndirizzo()->
                    setNomeIndirizzo($request['indirizzo'])) {
                $msg[] = '<li>L\'indirizzo specificato non &egrave; corretto</li>';
            }
        }
        if (isset($request['citta'])) {
            if (!$user->getIndirizzo()->
                    setCitta($request['citta'])) {
                $msg[] = '<li>La citt&agrave; specificata non &egrave; corretta</li>';
            }
        }
        if (isset($request['provincia'])) {
            if (!$user->getIndirizzo()->
                    setProvincia($request['provincia'])) {
                $msg[] = '<li>La provincia specificata &egrave; corretta</li>';
            }
        }
        if (isset($request['cap'])) {
            if (!$user->getIndirizzo()->
                    setCap($request['cap'])) {
                $msg[] = '<li>Il CAP specificato non &egrave; corretto</li>';
            }
        }
        if (isset($request['telefono'])) {
            if (!$user->getIndirizzo()->
                    setTelefono($request['telefono'])) {
                $msg[] = '<li>Il numero di telefono specificato non &egrave; corretto</li>';
            }
        }        
        // salviamo i dati se non ci sono stati errori
        if (count($msg) == 0) {
            if (IndirizzoFactory::instance()->salvaIndirizzo($user->getIndirizzo()) != 1) {
                $msg[] = '<li>Salvataggio non riuscito</li>';
            }
        }
    }
    
    
}

?>
