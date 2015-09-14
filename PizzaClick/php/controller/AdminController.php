<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of AdminController
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

class AdminController extends BaseController {
    //put your code here
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
            if(isset($request["subpage"])) {
                switch ($request["subpage"]) {
                    case 'gestione_ordini':                        
                        $ordini = OrdineFactory::instance()->getListaOrdiniAttivi();
                        $vd->setSottoPagina('gestione_ordini');

                        break;
                    default :
                        $vd->setSottoPagina('home');
                        break;                        
                }
            }
        
            if(isset($request["cmd"])) {
                switch ($request["cmd"]) {
                    case 'logout':
                        $this->logout($vd);
                        break;
                    case 'invia':
                        $msg = array();
                        
                        $ordini = OrdineFactory::instance()->getListaOrdiniAttivi();
                        
                        if(isset($request['id'])) {
                            if(OrdineFactory::instance()->chiudiOrdinePerId($request['id']) != 1) {
                                $msg[] = '<li>L\'ordine #' . $request['id'] .  ' non &egrave; valido</li>';                                         
                            }
                        }
                        $this->creaFeedbackUtente($msg, $vd, 'Ordine #' . $request['id'] . ' inviato');
                        
                        $vd->setSottoPagina('gestione_ordini');                        
                        $this->showHomeAdmin($vd);
                        
                        /* Non mostra il msg di errore/conferma */
                        header('Location: ' . Settings::getApplicationPath() . 'php/admin/gestione_ordini');
                        exit();
                        /* ************************************ */                        
                        break;
                    default : $this->showHomeAdmin($vd);                    
                }
            } else {// nessun comando
                $user = UserFactory::instance()->cercaUtentePerId(
                        $_SESSION[BaseController::user], $_SESSION[BaseController::role]);
                $this->showHomeUser($vd);
            }
            
        }
        
        
        require basename(__DIR__) . '/../view/master.php';
    }
}

?>
