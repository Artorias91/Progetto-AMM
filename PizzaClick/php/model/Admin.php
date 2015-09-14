<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Admin
 *
 * @author amm
 */

include_once 'User.php';


class Admin extends User {
    
    public function __construct() {
        parent::__construct();
        $this->setRuolo(User::Admin);
    }
}

?>
