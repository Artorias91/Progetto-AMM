<?php

include_once 'controller/BaseController.php';
include_once 'controller/ClienteController.php';

date_default_timezone_set("Europe/Rome");

FrontController::dispatch($_REQUEST);

class FrontController {
    public static function dispatch(&$request) {
        session_start();
        
        if (isset($request["page"])) {
            switch ($request["page"]) {
                case "login":
                    $controller = new BaseController;
                    $controller->handle_input($request);
                    break;
                case "cliente":
                    $controller = new ClienteController;
                    if (isset($_SESSION[BaseController::role]) &&
                        $_SESSION[BaseController::role] != User::Cliente) {
                            self::write403();
                    }
                    $controller->handle_input($request);
                    break;
//                case 'admin':
//                    $controller = new AdminController;
//                    $controller->handle_input($request);
//                    break;

                default:
                    self::write404();
                    break;
            }
        } else {
            self::write404();
        }
        
        
//        include 'php/view/master.php';
    }

    /**
     * Crea una pagina di errore quando il path specificato non esiste
     */    
    public static function write404() {
        // impostiamo il codice della risposta http a 404 (file not found)
        header('HTTP/1.0 404 Not Found');
        $titolo = "File non trovato!";
        $messaggio = "La pagina che hai richiesto non &egrave; disponibile";
        include_once('error.php');
        exit();
    }    
    /**
     * Crea una pagina di errore quando l'utente non ha i privilegi 
     * per accedere alla pagina
     */
    public static function write403() {
        // impostiamo il codice della risposta http a 404 (file not found)
        header('HTTP/1.0 403 Forbidden');
        $titolo = "Accesso negato";
        $messaggio = "Non hai i diritti per accedere a questa pagina";
        $login = true;
        include_once('error.php');
        exit();
    }    
}

    
?>
