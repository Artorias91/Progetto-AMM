<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of UserFactory
 *
 * @author amm
 */

include_once 'User.php';
include_once 'Cliente.php';
include_once 'Admin.php';
include_once 'Db.php';

include_once 'IndirizzoFactory.php';
include_once 'PagamentoFactory.php';


class UserFactory {
    //put your code here
    
    private static $singleton;
    
    private function __construct() {}
            
    public static function instance() {
        if(!isset(self::$singleton)) {
            self::$singleton = new UserFactory();
        }
        return self::$singleton;
    }
    

    /**
     * Carica un utente tramite username e password
     * @param string $username
     * @param string $password
     * @return \User|\Admin|\Cliente
     */
    public function caricaUtente($username, $password) {

        $mysqli = Db::getInstance()->connectDb();
        if (!isset($mysqli)) {
            error_log("[loadUser] impossibile inizializzare il database");
            $mysqli->close();
            return null;
        }

        // cerco prima nella tabella clienti
        $query = "select * from clienti where username = ? and password = ?";

        $stmt = $mysqli->stmt_init();
        $stmt->prepare($query);
        if (!$stmt) {
            error_log("[loadUser] impossibile" .
                    " inizializzare il prepared statement");
            $mysqli->close();
            return null;
        }
        if (!$stmt->bind_param('ss', $username, $password)) {
            error_log("[loadUser] impossibile" .
                    " effettuare il binding in input");
            $mysqli->close();
            return null;
        }

        $cliente = self::caricaClienteDaStmt($stmt);
        if (isset($cliente)) {
            // ho trovato un cliente
            $mysqli->close();
            return $cliente;
        }

        // ora cerco un admin
        $query = "select * from admins where admins.username = ? and admins.password = ?";

        $stmt = $mysqli->stmt_init();
        $stmt->prepare($query);
        if (!$stmt) {
            error_log("[loadUser] impossibile" .
                    " inizializzare il prepared statement");
            $mysqli->close();
            return null;
        }

        if (!$stmt->bind_param('ss', $username, $password)) {
            error_log("[loadUser] impossibile" .
                    " effettuare il binding in input");
            $mysqli->close();
            return null;
        }

        $admin = self::caricaAdminDaStmt($stmt);
        if (isset($admin)) {
            // ho trovato un admin
            $mysqli->close();
            return $admin;
        }
    }

    /**
     * Restituisce un array con gli admins presenti nel sistema
     * @return array
     */
    public function &getListaAdmins() {
        $admins = array();
        $query = "select * from admins";
        
        $mysqli = Db::getInstance()->connectDb();
        if (!isset($mysqli)) {
            error_log("[getListaAdmins] impossibile inizializzare il database");
            $mysqli->close();
            return $admins;
        }
        $result = $mysqli->query($query);
        if ($mysqli->errno > 0) {
            error_log("[getListaAdmins] impossibile eseguire la query");
            $mysqli->close();
            return $admins;
        }

        while ($row = $result->fetch_array()) {
            $admins[] = self::creaAdminDaArray($row);
        }

        $mysqli->close();
        return $admins;
    }

    
    /**
     * Restituisce la lista dei clienti presenti nel sistema
     * @return array
     */
    public function &getListaClienti() {
        $clienti = array();
        $query = "select * from clienti";
        
        $mysqli = Db::getInstance()->connectDb();
        if (!isset($mysqli)) {
            error_log("[getListaClienti] impossibile inizializzare il database");
            $mysqli->close();
            return $clienti;
        }
        $result = $mysqli->query($query);
        if ($mysqli->errno > 0) {
            error_log("[getListaClienti] impossibile eseguire la query");
            $mysqli->close();
            return $clienti;
        }

        while ($row = $result->fetch_array()) {
            $clienti[] = self::creaClienteDaArray($row);
        }

        return $clienti;
    }

    /**
     * Cerca un cliente per id
     * @param int $id
     * @return Cliente un oggetto Cliente nel caso sia stato trovato,
     * NULL altrimenti
     */
    public function cercaUtentePerId($id, $role) {
        $intval = filter_var($id, FILTER_VALIDATE_INT, FILTER_NULL_ON_FAILURE);
        if (!isset($intval)) {
            return null;
        }
        $mysqli = Db::getInstance()->connectDb();
        if (!isset($mysqli)) {
            error_log("[cercaUtentePerId] impossibile inizializzare il database");
            $mysqli->close();
            return null;
        }

        switch ($role) {
            case User::Cliente:
                $query = "select * from clienti where id = ?";

                $stmt = $mysqli->stmt_init();
                $stmt->prepare($query);
                if (!$stmt) {
                    error_log("[cercaUtentePerId] impossibile" .
                            " inizializzare il prepared statement");
                    $mysqli->close();
                    return null;
                }

                if (!$stmt->bind_param('i', $intval)) {
                    error_log("[cercaUtentePerId] impossibile" .
                            " effettuare il binding in input");
                    $mysqli->close();
                    return null;
                }

                return self::caricaClienteDaStmt($stmt);
                break;

            case User::Admin:
                $query = "select * from admins where id = ?";

                $stmt = $mysqli->stmt_init();
                $stmt->prepare($query);
                if (!$stmt) {
                    error_log("[cercaUtentePerId] impossibile" .
                            " inizializzare il prepared statement");
                    $mysqli->close();
                    return null;
                }

                if (!$stmt->bind_param('i', $intval)) {
                    error_log("[loadUser] impossibile" .
                            " effettuare il binding in input");
                    $mysqli->close();
                    return null;
                }

                $toRet =  self::caricaAdminDaStmt($stmt);
                $mysqli->close();
                return $toRet;
                break;

            default: return null;
        }
    }

    /**
     * Crea un cliente da una riga del db
     * @param type $row
     * @return \Cliente
     */
    public function creaClienteDaArray($row) {
        $cliente = new Cliente();
        
        $cliente->setRuolo(User::Cliente);
        
        $cliente->setId($row['id']);
        $cliente->setUsername($row['username']);
        $cliente->setPassword($row['password']);
        $cliente->setEmail($row['email']);
        $cliente->setNome($row['nome']);
        $cliente->setCognome($row['cognome']);

        $cliente->setIndirizzo(IndirizzoFactory::instance()->
                cercaIndirizzoPerId($row['indirizzo']));
        
//        echo $cliente->getIndirizzo()->getDestinatario();

//        $cliente->setPagamento(PagamentoFactory::instance()->
//                caricaPagamentoPerId($row['pagamento']));
        
//        echo substr($cliente->getPagamento()->getScadenzaCarta(), -2);

        return $cliente;
    }

    /**
     * Crea un admin da una riga del db
     * @param type $row
     * @return \Admin
     */
    public function creaAdminDaArray($row) {
        $admin = new Admin();
        $admin->setId($row['admins_id']);
        $admin->setUsername($row['admins_username']);
        $admin->setPassword($row['admins_password']);
        $admin->setEmail($row['admins_email']);
        $admin->setNome($row['admins_nome']);
        $admin->setCognome($row['admins_cognome']);
        $admin->setRuolo(User::Admin);

        return $admin;
    }

    /**
     * Salva i dati relativi ad un utente sul db
     * @param User $user
     * @return il numero di righe modificate
     */
    public function salva(User $user) {
        $mysqli = Db::getInstance()->connectDb();
        if (!isset($mysqli)) {
            error_log("[salva] impossibile inizializzare il database");
            $mysqli->close();
            return 0;
        }

        $stmt = $mysqli->stmt_init();
        $count = 0;
        switch ($user->getRuolo()) {
            case User::Cliente:
                $count = $this->salvaCliente($user, $stmt);
                break;
            case User::Admin:
                $count = $this->salvaAdmin($user, $stmt);
        }

        $stmt->close();
        $mysqli->close();
        
        echo 'username:' . $user->getUsername() . '<br>';
        echo 'count:' . $count . '<br>';
        
        return $count;
    }

    /**
     * Rende persistenti le modifiche all'anagrafica di un cliente sul db
     * @param Cliente $s il cliente considerato
     * @param mysqli_stmt $stmt un prepared statement
     * @return int il numero di righe modificate
     */
    private function salvaCliente(Cliente $s, mysqli_stmt $stmt) {
        $query = " update clienti set 
                    username = ?,
                    password = ?,
                    email = ?,
                    nome = ?,
                    cognome = ?
                    where clienti.id = ?
                 ";
        $stmt->prepare($query);
        if (!$stmt) {
            error_log("[salvaCliente] impossibile" .
                    " inizializzare il prepared statement");
            
            echo 'impossibile" .
                    " inizializzare il prepared statement';
            
            return 0;
        }

        if (!$stmt->bind_param('sssssi', 
                $s->getUsername(), 
                $s->getPassword(),
                $s->getEmail(), 
                $s->getNome(), 
                $s->getCognome(), 
                $s->getId())) {
            error_log("[salvaCliente] impossibile" .
                    " effettuare il binding in input");
            
            echo 'impossibile" .
                    " effettuare il binding in input';
            
            return 0;
        }

        if (!$stmt->execute()) {
            error_log("[caricaRegistrati] impossibile" .
                    " eseguire lo statement");
            
            echo 'impossibile" .
                    " eseguire lo statement';
            
            return 0;
        }
        
//        echo 'affected_rows: ' . $stmt->affected_rows . '<br>';

//        if($stmt->affected_rows == 0)
//            return 1;
        
        return $stmt->affected_rows;
    }
    
    /**
     * Rende persistenti le modifiche all'anagrafica di un admin sul db
     * @param Admin $d l'admin considerato
     * @param mysqli_stmt $stmt un prepared statement
     * @return int il numero di righe modificate
     */
    private function salvaAdmin(Admin $d, mysqli_stmt $stmt) {
        $query = " update admins set 
                    password = ?,
                    nome = ?,
                    cognome = ?,
                    email = ?,
                    where admins.id = ?
                    ";
        $stmt->prepare($query);
        if (!$stmt) {
            error_log("[salvaAdmin] impossibile" .
                    " inizializzare il prepared statement");
            return 0;
        }

        if (!$stmt->bind_param('ssssi', 
                $d->getPassword(), 
                $d->getNome(), 
                $d->getCognome(), 
                $d->getEmail(), 
                $d->getId())) {
            error_log("[salvaAdmin] impossibile" .
                    " effettuare il binding in input");
            return 0;
        }

        if (!$stmt->execute()) {
            error_log("[caricaRegistrati] impossibile" .
                    " eseguire lo statement");
            return 0;
        }

        return $stmt->affected_rows;
    }

    /**
     * Carica un admin eseguendo un prepared statement
     * @param mysqli_stmt $stmt
     * @return null
     */
    private function caricaAdminDaStmt(mysqli_stmt $stmt) {

        if (!$stmt->execute()) {
            error_log("[caricaAdminDaStmt] impossibile" .
                    " eseguire lo statement");
            return null;
        }

        $row = array();
        $bind = $stmt->bind_result(
                $row['admins_id'], 
                $row['admins_nome'], 
                $row['admins_cognome'], 
                $row['admins_email'], 
                $row['admins_username'], 
                $row['admins_password']);
        if (!$bind) {
            error_log("[caricaAdminDaStmt] impossibile" .
                    " effettuare il binding in output");
            return null;
        }

        if (!$stmt->fetch()) {
            return null;
        }

        $stmt->close();

        return self::creaAdminDaArray($row);
    }

    /**
     * Carica un cliente eseguendo un prepared statement
     * @param mysqli_stmt $stmt
     * @return null
     */
    private function caricaClienteDaStmt(mysqli_stmt $stmt) {

        if (!$stmt->execute()) {
            error_log("[caricaClienteDaStmt] impossibile" .
                    " eseguire lo statement");
            return null;
        }

        $row = array();
        $bind = $stmt->bind_result(
                $row['id'], 
                $row['username'],
                $row['password'],
                $row['email'], 
                $row['nome'],
                $row['cognome'],                
                $row['indirizzo']
                );
        if (!$bind) {
            error_log("[caricaClienteDaStmt] impossibile" .
                    " effettuare il binding in output");
            return null;
        }

        if (!$stmt->fetch()) {
            return null;
        }

        $stmt->close();

        return self::creaClienteDaArray($row);
    }
    /** 
     * Verifica se l'username $username non sia già in possesso 
     * da un altro utente (cliente o admin)
     * @param string $username
     * @param User $user
     * @return true if already used otherwise false
     */
    public function checkIfUsernameIsAlreadyUsed($username, User $user) {

        $mysqli = Db::getInstance()->connectDb();
        if (!isset($mysqli)) {
            error_log("[checkIfUsernameIsAlreadyUsed] impossibile inizializzare il database");
            $mysqli->close();
            return null;
        }
        
        switch ($user->getRuolo()) {
            case User::Cliente:
                $query = "select count(*) from (
                    (select username from clienti 
                        where id <> ?) 
                        UNION ALL 
                    (select username from admins) 
                    )   dt 
                    where username = ?";

                break;
            case User::Admin:
                $query = "select count(*) from (
                    (select username from clienti) 
                        UNION ALL 
                    (select username from admins 
                        where id <> ?) 
                    )   dt 
                    where username = ?"; 
                break;
            default:
                return null;
        }
        $stmt = $mysqli->stmt_init();
        $stmt->prepare($query);
        if (!$stmt) {
            error_log("[checkIfUsernameIsAlreadyUsed] impossibile" .
                    " inizializzare il prepared statement");
            $mysqli->close();
            return null;
        }
        if (!$stmt->bind_param('is', $user->getId(), $username)) {
             error_log("[checkIfUsernameIsAlreadyUsed] impossibile" .
                     " effettuare il binding in input");
             $mysqli->close();
             return null;
         }
        if (!$stmt->execute()) {
            error_log("[checkIfUsernameIsAlreadyUsed] impossibile" .
                    " eseguire lo statement");
            return null;
        }
        $count = 0;
        
        $bind = $stmt->bind_result($count);
        
        if (!$bind) {
            error_log("[checkIfUsernameIsAlreadyUsed] impossibile" .
                    " effettuare il binding in output");
            return null;
        }
        
        if (!$stmt->fetch()) {
            return null;
        }        
        
        $stmt->close();
        
//        echo "numero: " . $count . '<br>';
        
        return $count < 1 ? false : true;
    }
}

?>
