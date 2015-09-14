<?php
if(in_array($vd->getSottoPagina(), 
        array('visualizza_pagamento', 'pagamento', 'indirizzo', 'password', 'base', 'cronologia_ordini'))) {
    ?>
<div class="sub">
    <h4>
        <ol class="breadcrumb">
            <li><a href="cliente/account">Account</a></li>
            <span>&nbsp;&nbsp&rtrif;&nbsp;&nbsp</span>
            <li><?= $vd->getBreadcrumb() ?></li>
        </ol>
    </h4>
<?php
}
switch ($vd->getSottoPagina()) {   
    case 'conferma_ordine_step1': case 'conferma_ordine_step2': case 'conferma_ordine_step3':
        include 'conferma_ordine_step.php';
        break;
    case 'account':
        include 'content_account.php';
        break;
    case 'cronologia_ordini':
        include 'cronologia_ordini.php';
        break;     
    case 'base':
?>
<form method="post" action="cliente/impostazioni<?= '?'.$vd->scriviToken()?>">
    <input type="hidden" name="cmd" value="aggiorna_info_base"/>    
    <label for="nome">Nome</label>
    <input class="text" readonly name="nome" id="nome" value="<?= $user->getNome() ?>"/><br>
    <label for="cognome">Cognome</label>
    <input class="text" readonly name="cognome" id="cognome" value="<?= $user->getCognome() ?>"/><br>
    <label for="username">Username</label>
    <input class="text" required name="username" id="username" value="<?= $user->getUsername() ?>"/><br>
    <label for="email">E-mail</label>
    <input class="text" required name="email" id="email" value="<?= $user->getEmail() ?>"/><br>
    <input class="modifica" type="submit" value="Salva modifiche"/>
</form>        
<?php
        break;
    case 'password':
?>
<form method="post" action="cliente/password">
    <input type="hidden" name="cmd" value="aggiorna_password"/>    
    <label for="oldPass">Password attuale</label>
    <input class="text" required type="password" name="oldPass" id="oldPass"/><br>
    <label for="pass1">Nuova password</label>
    <input class="text" required type="password" name="pass1" id="pass1"/><br>
    <label for="pass2">Conferma nuova password</label>
    <input class="text" class="text" required type="password" name="pass2" id="pass2"/><br>
    <input class="modifica" type="submit" value="Salva modifiche"/>
</form>        
<?php
        break;
    case 'indirizzo':
        include 'aggiorna_indirizzo.php';
        break;
    case 'pagamento':
        include 'pagamento.php';   
        break;
?>
<?php
    case 'visualizza_pagamento':
        include 'pagamento.php';
        include 'visualizza_pagamento.php';
        break;
    default:
        include 'galleria.php';
        break;
}
?>
<?php
if(in_array($vd->getSottoPagina(), array('visualizza_pagamento', 'pagamento', 'indirizzo', 'password', 'base', 'cronologia_ordini'))) {
    ?>
</div>
<?php
}