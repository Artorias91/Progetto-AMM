<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ViewDescriptor
 *
 * @author amm
 */
class ViewDescriptor {
    /**
     * GET http
     */

    const get = 'get';
    /**
     * Post HTTP
     */
    const post = 'post';

    /**
     * Titolo della finestra del browser
     * @var string
     */
    private $titolo;


    private $header_file;
    
    
    
    private $ajax_file;

    /**
     * File che include la definizione HTML della sidebar (sinistra)
     * @var string 
     */
    private $sidebar_file;


    /**
     * File che include la definizione HTML del contenuto principale
     * @var string 
     */
    private $content_file;

    /**
     * Messaggio di errore da mostrare dopo un input (nascosto se nullo)
     * @var string 
     */
    private $messaggioErrore;

    /**
     * Messaggio di conferma da mostrare dopo un input (nascosto se nullo)
     * @var string 
     */
    private $messaggioConferma;
    
    /**
     * Pagina della vista corrente 
     * (le funzionalita' sono divise in tre categorie: 
     * amministratore, studente e docente, corrispondenti alle sottocartelle 
     * di view nel progetto)
     * @var string 
     */
    private $pagina;
    /**
     * Sottopagina della vista corrente (una per funzionalita' da supportare)
     * (le funzionalita' sono divise in tre categorie: 
     * amministratore, studente e docente, corrispondenti alle sottocartelle 
     * di view nel progetto)
     * @var string 
     */
    private $sottoPagina;
    /**
     * Variabile utilizzata in modalita' amministratore per impersonare 
     * degli utenti (vedere metodo setImpToken)
     * @var string 
     */
    private $impToken;

    /**
     * lista di script javascript da aggiungere alla pagina
     * @var array
     */
    private $js;
    /**
     * lista di stili css da aggiungere alla pagina
     * @var array
     */
    private $css;
    
    /**
     * flag per dati json (non scrive html)
     * @var boolean
     */
    private $json;
    
    
    private $breadcrumb;
    
    private $titoloStep;
    
    /**
     * Costruttore
     */
    public function __construct() {
        $this->js = array();
        $this->css = array();
        $this->json = false;
        
        $this->order = false;
//        $this->orderList = array();
    }

    /**
     * Restituisce il titolo della scheda del browser
     * @return string
     */
    public function getTitolo() {
        return $this->titolo;
    }

    /**
     * Imposta il titolo della scheda del browser
     * @param string $titolo il titolo della scheda del browser
     */
    public function setTitolo($titolo) {
        $this->titolo = $titolo;
    }
    
    public function getHeaderFile() {
        return $this->header_file;
    }

    public function setHeaderFile($header) {
        $this->header_file = $header;
    }
    
    public function getAjaxFile() {
        return $this->ajax_file;
    }
    
    public function setAjaxFile($ajax) {
        $this->ajax_file = $ajax;
    }
    
        /**
     * Restituisce il path al file che include la definizione HTML della sidebar sinistra
     * @return string
     */
    public function getSidebarFile() {
        return $this->sidebar_file;
    }

    /**
     * Imposta il path al file che include la definizione HTML della sidebar sinistra
     * @param type $leftBar
     */
    public function setSidebarFile($sidebar) {
        $this->sidebar_file = $sidebar;
    }


     /**
     * Imposta il file che include la definizione HTML del contenuto principale
     * @return string
     */
    public function setContentFile($contentFile) {
        $this->content_file = $contentFile;
    }

    /**
     * Restituisce il path al file che contiene il contenuto principale
     * @return string
     */
    public function getContentFile() {
        return $this->content_file;
    }
    
    /**
     * Restituisce il testo del messaggio di errore
     * @return string
     */
    public function getMessaggioErrore() {
        return $this->messaggioErrore;
    }

      /**
     * Imposta un messaggio di errore
     * @return string
     */
    public function setMessaggioErrore($msg) {
        $this->messaggioErrore = $msg;
    }

    /**
     * Restituisce il nome della sotto-pagina corrente
     * @return string
     */
    public function getSottoPagina() {
        return $this->sottoPagina;
    }

    /**
     * Imposta il nome della sotto-pagina corrente
     * @param string $pag
     */
    public function setSottoPagina($pag) {
        $this->sottoPagina = $pag;
    }

    /**
     * Restituisce il contenuto del messaggio di conferma
     * @return string
     */
    public function getMessaggioConferma() {
        return $this->messaggioConferma;
    }

    /**
     * Imposta il contenuto del messaggio di conferma
     * @param string $msg
     */
    public function setMessaggioConferma($msg) {
        $this->messaggioConferma = $msg;
    }

    /**
     * Restituisce il nome della pagina corrente
     * @return string
     */
    public function getPagina() {
        return $this->pagina;
    }

    /**
     * Imposta il nome della pagina corrente
     * @param string $pagina
     */
    public function setPagina($pagina) {
        $this->pagina = $pagina;
    }
    
    /**
     * Aggiunge uno script alla pagina
     * @param String $nome
     */
    public function addScript($nome){
        $this->js[] = $nome;
    }
    
    /**
     * Restituisce la lista di script
     * @return array
     */
    public function &getScripts(){
        return $this->js;
    }
    /**
     * Aggiunge uno stile alla pagina
     * @param String $nome
     */
    public function addStyle($nome){
        $this->css[] = $nome;
    }
    
    /**
     * Restituisce la lista di stili
     * @return array
     */
    public function &getStyles(){
        return $this->css;
    }
    
    /**
     * True se si devono scrivere dati json, false altrimenti
     * @return Boolean
     */
    public function isJson(){
        return $this->json;
    }
    
    /**
     * Da chiamare se la risposta contiene dati json
     */
    public function toggleJson(){
        $this->json = true;
    }
    
    /**
     * Restituisce il valore corrente del token per fare in modo che
     * un amministratore possa impersonare uno studente o un docente
     * @param string $token
     */
    public function setImpToken($token) {
        $this->impToken = $token;
    }

    /**
     * Scrive un token per gestire quale sia l'utente che l'amministratore
     * sta impersonando per svolgere delle operazioni in sua vece. 
     * 
     * Questo metodo concentra in un solo punto il mantenimento di questa
     * informazione, che deve essere appesa per ogni get e per ogni post
     * quando si accede all'interfaccia dello studente o del docente 
     * in modalita' amministratore, in modo che possano essere impersonati 
     * piu' utenti tramite diversi schede dello stesso browser
     * 
     * Se avessimo inserito questa informazione in sessione, sarebbe stato 
     * possibile gestirne solo uno. Inoltre, in caso di piu' schede aperte con 
     * lo stesso browser, i dati sarebbero stati mescolati.
     * 
     * Questo e' un esempio di gestione di variabili a livello pagina. 
     * 
     * @param string $pre il prefisso per attaccare il parametro del token nella 
     * query string. Si usi '?' se il token e' il primo parametro e '&' altrimenti
     * @param int $method metodo HTTP (get o set)
     * @return string il valore da scrivere nella URL in caso di get o come
     * hidden input in caso di form
     */
    public function scriviToken($pre = '', $method = self::get) {
        $imp = BaseController::impersonato;
        switch ($method) {
            case self::get:
                if (isset($this->impToken)) {
                    // nel caso della 
                    return $pre . "$imp=$this->impToken";
                }
                break;

            case self::post:
                if (isset($this->impToken)) {
                    return "<input type=\"hidden\" name=\"$imp\" value=\"$this->impToken\"/>";
                }
                break;
        }

        return '';
    }
    
    public function setBreadcrumb($bd) {
        $this->breadcrumb = $bd;
    } 
    public function getBreadcrumb() {
        return $this->breadcrumb;
    } 
    
    public function setTitoloStep($title) {
        $this->titoloStep = $title;
    } 
    public function getTitoloStep() {
        return $this->titoloStep;
    }
    
}

?>