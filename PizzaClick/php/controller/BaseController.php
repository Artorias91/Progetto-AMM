<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of BaseController
 *
 * @author amm
 */

include_once basename(__DIR__) . '/../view/ViewDescriptor.php';
include_once basename(__DIR__) . '/../model/UserFactory.php';
include_once basename(__DIR__) . '/../model/User.php';

include_once basename(__DIR__) . '/../model/PizzaFactory.php';
include_once basename(__DIR__) . '/../model/Pizza.php';


class BaseController {

    const user = 'user';
    const role = 'role';
    const impersonato = '_imp';
    
    public function __construct() {}

    public function handle_input(&$request) {
        
        $vd = new ViewDescriptor();
        
        $vd->setPagina($request['page']);
                
        $this->setImpToken($vd, $request);
        
        if(isset($request["cmd"])) {
            switch ($request["cmd"]) {

                case 'login':
                    $username = isset($request['username']) ? $request['username'] : '';
                    $password = isset($request['password']) ? $request['password'] : '';
                    $this->login($vd, $username, $password);
                    
                    if($this->loggedIn()) {
                        $user = UserFactory::instance()->cercaUtentePerId($_SESSION[self::user], $_SESSION[self::role]);
                        
                        //nel caso si sia loggato il cliente
                        if(isset($_SESSION[self::role]) && 
                                $_SESSION[self::role] == Cliente::Cliente) {
                            //carica le pizze da visualizzare sulla galleria
//                            $listaPizzeConImg = PizzaFactory::instance()->getListaPizze(true);
//                            reset($listaPizzeConImg);
//                            $pizza = current($listaPizzeConImg);
//                            $index = 0;
//                            $vd->setSottoPagina('home');
                            header('Location: ' . Settings::getApplicationPath() . 'php/cliente/home');
                        }
                    }
                    break;
                default://autentificazione fallita: mostra messaggio di errore nella pagina di login
                    $this->showLoginPage($vd);
//                    break;
            }
        }
        else {
            //utente autentificato: aggiorna la vista in base al ruolo
            if($this->loggedIn()) {
                $user = UserFactory::instance()->cercaUtentePerId($_SESSION[self::user], $_SESSION[self::role]);                
                $this->showHomeUser($vd);                
            }//utente non autentificato: mostra la pagina di login
            else {
                $this->showLoginPage($vd);
            }
        }
//        if($vd->isJson()) {
//            $this->showHomeUser($vd);
//        }
        require basename(__DIR__) . '/../view/master.php';

    }
 
    /**
     * Verifica se l'utente sia correttamente autenticato
     * @return boolean true se l'utente era gia' autenticato, false altrimenti
     */
    protected function loggedIn() {
        return isset($_SESSION) && array_key_exists(self::user, $_SESSION);
    }
   
    /**
     * Procedura di autenticazione 
     * @param ViewDescriptor $vd descrittore della vista
     * @param string $username lo username specificato
     * @param string $password la password specificata
     */
    protected function login($vd, $username, $password) {
        // carichiamo i dati dell'utente
        
        $user = UserFactory::instance()->caricaUtente($username, $password);
        if (isset($user) && $user->esiste()) {
            // utente autenticato
            $_SESSION[self::user] = $user->getId();
            $_SESSION[self::role] = $user->getRuolo();
            $this->showHomeUser($vd);
        } else {
            $vd->setMessaggioErrore("Utente sconosciuto o password errata");
            $this->showLoginPage($vd);
        }
    }

    /**
     * Procedura di logout dal sistema 
     * @param type $vd il descrittore della pagina
     */
    protected function logout($vd) {
        // reset array $_SESSION
        $_SESSION = array();
        // termino la validita' del cookie di sessione
        if (session_id() != '' || isset($_COOKIE[session_name()])) {
            // imposto il termine di validita' al mese scorso
            setcookie(session_name(), '', time() - 2592000, '/');
        }
        // distruggo il file di sessione
        session_destroy();
        $this->showLoginPage($vd);
    }
    
    /**
     * Imposta la variabile del descrittore della vista legato 
     * all'utente da impersonare nel caso sia stato specificato nella richiesta
     * @param ViewDescriptor $vd il descrittore della vista
     * @param array $request la richiesta
     */
    protected function setImpToken(ViewDescriptor $vd, &$request) {

        if (array_key_exists('_imp', $request)) {
            $vd->setImpToken($request['_imp']);
        }
    }    

    
 /**
     * Imposta la vista master.php per visualizzare la pagina di login
     * @param ViewDescriptor $vd il descrittore della vista
     */
    protected function showLoginPage($vd) {
        // mostro la pagina di login
        $vd->setTitolo("Pizza Click! - login");
        $vd->setContentFile(basename(__DIR__) . '/../view/login/login-content.php');
        $vd->setHeaderFile(basename(__DIR__) . '/../view/admin/header.php');
        $vd->setSidebarFile(basename(__DIR__) . '/../view/admin/sidebar.php');
   
    }

    /**
     * Seleziona quale pagina mostrare in base al ruolo dell'utente corrente
     * @param ViewDescriptor $vd il descrittore della vista
     */
    protected function showHomeUser($vd) {
        $user = UserFactory::instance()->cercaUtentePerId($_SESSION[self::user], $_SESSION[self::role]);
        switch ($user->getRuolo()) {
            case User::Admin:
                $this->showHomeAdmin($vd);
                break;
            case User::Cliente:
                $this->showHomeCliente($vd);
                break;
        }
    }

    protected function showHomeCliente($vd) {
        // mostro la home dei clienti
        $vd->setTitolo("Pizza Click! - cliente ");
        $vd->setHeaderFile(basename(__DIR__) . '/../view/cliente/header.php');
        $vd->setContentFile(basename(__DIR__) . '/../view/cliente/content.php');
        $vd->setSidebarFile(basename(__DIR__) . '/../view/cliente/sidebar.php');
    }    
    
    protected function showHomeAdmin($vd) {
        // mostro la home dell'admin
        $vd->setTitolo("Pizza Click! - admin ");
        $vd->setHeaderFile(basename(__DIR__) . '/../view/admin/header.php');
        $vd->setContentFile(basename(__DIR__) . '/../view/admin/content.php');
        $vd->setSidebarFile(basename(__DIR__) . '/../view/admin/sidebar.php');
    }    

    
    /**
     * Crea un messaggio di feedback per l'utente 
     * @param ViewDescriptor $vd il descrittore della pagina
     * @param array $msg_e lista di messaggi di errore
     * @param array $msg_c lista di messaggi di conferma
     * @param string $ifMsg il messaggio da mostrare nel caso in cui
     * non siano stati rilevati errori o apportate modifiche
     */
    protected function creaFeedbackUtente($vd, &$msg_e, &$msg_c, &$ifMsg) {
        if (count($msg_e) > 0) {
            // ci sono messaggi di errore nell'array,
            // qualcosa e' andato storto...
            $error = "Sono stati riscontrati i seguenti problemi:\n<ul>\n";
            foreach ($msg_e as $m) {
                $error = $error . $m . "\n";
            }
            // imposto il messaggio di errore
            $vd->setMessaggioErrore($error);
        }
        if(count($msg_c) > 0){
            // ci sono messaggi di conferma nell'array,
            // quindi mostro le modifiche applicate
            $ok = "Sono state applicate le seguenti modifiche:\n<ul>\n";
            foreach ($msg_c as $m) {
                $ok = $ok . $m . "\n";
            }
            $vd->setMessaggioConferma($ok);
        }
        //nessun errore rilevato e nessuna modifica applicata
        if(empty($msg_e) && empty($msg_c)) {
            $ifMsg = 'Nessuna modifica applicata:' .
            "</br><span style=\"font-size: 8pt;\">$ifMsg</span></li>";
            $vd->setMessaggioErrore($ifMsg);
        }
    }        
    
    
    /**
     * Aggiorno lo username di un utente (comune a Clienti e Admins)
     * @param User $user l'utente da aggiornare
     * @param array $request la richiesta http da gestire
     * @param array $msg_e riferimento ad un array da riempire con eventuali errori
     * @param array $msg_c riferimento ad un array da riempire con eventuali msg di conferma
     * messaggi d'errore
     */    
    public function aggiornaUsername($user, &$request, &$msg_e, &$msg_c) {
        if(isset($request['username'])) {
            if(! UserFactory::instance()->checkIfUsernameIsAlreadyUsed($request['username'], $user)) {
                if($user->getUsername() == $request['username']) {
                    return;//$msg_e[] = '<li>Lo username inserito &egrave; gi&agrave; in uso.</li>';
                } elseif(!$user->setUsername($request['username'])) {
                    $msg_e[] = '<li>Lo username inserito non è valido:' .
                            "</br><span style=\"font-size: 8pt;\">puoi inserire solo lettere " . 
                            '(devono essere almeno 5)</span></li>';
                } else {
                    $msg_c[] = '<li>Lo username inserito &egrave; stato aggiornato</li>';
                }                
            } else {
                $msg_e[] = '<li>Spiacente, lo username inserito non è disponibile</li>';
            }            
        }
        if(empty($msg_e) && ! empty($msg_c)) {
            if(UserFactory::instance()->salva($user) != 1) {
                $msg_e[] = '<li>Salvataggio non riuscito</li>';
            }
        }
    }
    
   /**
     * Aggiorno l'indirizzo email di un utente (comune a Clienti e Admins)
     * @param User $user l'utente da aggiornare
     * @param array $request la richiesta http da gestire
     * @param array $msg_e riferimento ad un array da riempire con eventuali errori
     * @param array $msg_c riferimento ad un array da riempire con eventuali msg di conferma
     * messaggi d'errore
     */
    protected function aggiornaEmail($user, &$request, &$msg_e, &$msg_c) {
        if (isset($request['email'])) {
            if($user->getEmail() == $request['email']) {
                return;//$msg_e[] = '<li>L\'indirizzo email inserito &egrave; gi&agrave; in uso.</li>';
            } elseif (!$user->setEmail($request['email'])) {
                $msg_e[] = '<li>L\'indirizzo email specificato non &egrave; valido</li>';
            } else {
                $msg_c[] = '<li>L\'indirizzo email &egrave; stato aggiornato</li>';
            }
        }
        // salviamo i dati se non ci sono stati errori
        if (empty($msg_e) && ! empty($msg_c)) {
            if (UserFactory::instance()->salva($user) != 1) {
                $msg_e[] = '<li>Salvataggio non riuscito</li>';
            }
        }
    }    
   /**
     * Aggiorno il numero di telefono di un utente (comune a Clienti e Admins)
     * @param User $user l'utente da aggiornare
     * @param array $request la richiesta http da gestire
     * @param array $msg_e riferimento ad un array da riempire con eventuali errori
     * @param array $msg_c riferimento ad un array da riempire con eventuali msg di conferma
     * messaggi d'errore
     */
    protected function aggiornaTelefono($user, &$request, &$msg_e, &$msg_c) {
        if (isset($request['telefono'])) {
            if($user->getTelefono() == $request['telefono']) {
                return;//$msg_e[] = '<li>L\'indirizzo email inserito &egrave; gi&agrave; in uso.</li>';
            } elseif (!$user->setTelefono($request['telefono'])) {
                $msg_e[] = '<li>Il numero di telefono specificato non &egrave; valido</li>';
            } else {
                $msg_c[] = '<li>Il numero di telefono &egrave; stato aggiornato</li>';
            }
        }        
        // salviamo i dati se non ci sono stati errori
        if (empty($msg_e) && ! empty($msg_c)) {
            if (UserFactory::instance()->salva($user) != 1) {
                $msg_e[] = '<li>Salvataggio non riuscito</li>';
            }
        }
    }        

    /**
     * Aggiorno la password di un utente (comune a Clienti e Admins)
     * @param User $user l'utente da aggiornare
     * @param array $request la richiesta http da gestire
     * @param array $msg_e riferimento ad un array da riempire con eventuali errori
     * @param array $msg_c riferimento ad un array da riempire con eventuali msg di conferma
     * messaggi d'errore
     */
    protected function aggiornaPassword($user, &$request, &$msg_e, &$msg_c) {
        if (isset($request['oldPass']) && isset($request['pass1']) && isset($request['pass2'])) {
            if(strcmp($request['oldPass'], $user->getPassword()) == 0) {
                if (strcmp($request['pass1'], $request['pass2']) == 0) {
                    if (!$user->setPassword($request['pass1'])) {
                        $msg_e[] = '<li>Il formato della password non &egrave; corretto</li>';
                    } else {
                        $msg_c[] = '<li>La password &egrave; stata cambiata</li>';                
                    }
                } else {
                    $msg_e[] = '<li>Le due password non coincidono</li>';
                }                
            } else {
                $msg_e[] = '<li>La password attuale non &egrave; corretta</li>';                
            }
        } 
        
        // salviamo i dati se non ci sono stati errori
        if (empty($msg_e) && ! empty($msg_c)) {
            if (UserFactory::instance()->salva($user) != 1) {
                $msg[] = '<li>Salvataggio non riuscito</li>';
            }
        }
    }
    
    
    
}

?>
