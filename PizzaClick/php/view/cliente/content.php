<?php
//include_once 'php/view/ViewDescriptor.php';

switch ($vd->getSottoPagina()) {
    case 'base': case 'password': case 'indirizzo': case 'pagamento': case 'visualizza_pagamento':
?>
<!-- breadcrumb -->
<h4>
    <ol>
        <li><a href="cliente/account">Impostazioni account</a></li>
        <span>&nbsp;&nbsp&rtrif;&nbsp;&nbsp</span>
        <li><?= $vd->getBreadcrumb() ?></li>
    </ol>
</h4>
<!-- fine breadcrumb -->

<?php
        break;
}
switch ($vd->getSottoPagina()) {
    case 'account':
        include 'content_account.php';
        break;
    case 'base':
?>
<form method="post" action="cliente/impostazioni<?= '?'.$vd->scriviToken()?>">
    <input type="hidden" name="cmd" value="base"/>    
    <label for="nome">Nome</label>
    <input readonly name="nome" id="nome" value="<?= $user->getNome() ?>"/><br>
    <label for="cognome">Cognome</label>
    <input readonly name="cognome" id="cognome" value="<?= $user->getCognome() ?>"/><br>
    <label for="username">Username</label>
    <input required name="username" id="username" value="<?= $user->getUsername() ?>"/><br>
    <label for="email">E-mail</label>
    <input required name="email" id="email" value="<?= $user->getEmail() ?>"/><br>
    <input class="modifica" type="submit" value="Salva modifiche"/>
</form>        
<?php
        break;
    case 'password':
?>
<form method="post" action="cliente/impostazioni<?= '?'.$vd->scriviToken()?>">
    <input type="hidden" name="cmd" value="password"/>    
    <label for="oldPass">Password attuale</label>
    <input required type="password" name="oldPass" id="oldPass"/><br>
    <label for="pass1">Nuova password</label>
    <input required type="password" name="pass1" id="pass1"/><br>
    <label for="pass2">Conferma nuova password</label>
    <input required type="password" name="pass2" id="pass2"/><br>
    <input class="modifica" type="submit" value="Salva modifiche"/>
</form>        
<?php
        break;
    case 'indirizzo':
?>
<form method="post" action="cliente/impostazioni<?= '?'.$vd->scriviToken()?>">
    <input type="hidden" name="cmd" value="indirizzo"/>    
    <label for="destinatario">Destinatario</label>
    <input required placeholder="Nome e cognome" name="destinatario" id="destinatario" 
           value="<?= $user->getIndirizzo()->getDestinatario() ?>"/>
    <br>
    <label for="indirizzo">Indirizzo</label>
    <input required placeholder="Via e numero civico" name="indirizzo" id="indirizzo" 
           value="<?= $user->getIndirizzo()->getNomeIndirizzo() ?>"/>
    <br>        
    <label for="citta">Citta</label>
    <input required name="citta" id="citta" 
           value="<?= $user->getIndirizzo()->getCitta() ?>"/>
    <br>
    <label for="provincia">Provincia</label>
    <input required name="provincia" id="provincia" 
           value="<?= $user->getIndirizzo()->getProvincia() ?>"/>
    <br>
    <label for="cap">CAP</label>
    <input required name="cap" id="cap" 
           value="<?= $user->getIndirizzo()->getCap() ?>"/>
    <br>
    <label for="telefono">Telefono</label>
    <input required maxlength="10" name="telefono" id="telefono" 
           value="<?= $user->getIndirizzo()->getTelefono() ?>"/>
    <br>                    
    <input class="modifica" type="submit" value="Salva modifiche"/>
</form>        
<?php
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
?>
<!--<script type="text/javascript">
    //da cambiare???    
    var $n_pizza = <?= 3 ?>;
</script>-->

<!-- gallery -->
<link rel="stylesheet" type="text/css" href="../css/gallery-style.css">
<script type="text/javascript" src="../js/gallery-script-ajax.js"></script>

<div class="galleryContent">
    <div class="galleryPreviewContent">
        <div class="galleryPreviewFormDropdown" style="display: none;">
            <form class="gallery" id="aggiungi" method="post" action="index.php?page=cliente">
                <input type ="hidden" id="pizza-gallery" name="pizza-gallery" value="<?= $index ?>"/>                            
                <label for="size">Dimensione</label>
                <select name="size" id="size">
                    <option value="ridotta">Ridotta</option>
                    <option value="normale" selected>Normale</option>
                    <option value="gigante">Gigante</option>
                </select><br>
                <label for="quantity">Quantit&agrave;</label>
                <select name="quantity" id="quantity">
                <?php for($i = 1; $i < 11; $i++) { ?>
                    <option value="<?=$i?>"><?=$i?></option>
                <?php } ?>
                </select>
                  <button type="submit"name="cmd" value="add">Aggiungi al carrello</button>
            </form>          
        </div>            
        <div class="galleryPreviewImg">
            <img class="previewImg"
                 src="<?= $pizza->getUrlImg() ?>" 
                 alt="<?= $pizza->getNome() ?>" />
            <div class="galleryDescriptPreviewImg">
                <span class="descriptPreviewImg">
                    <?= $pizza->getNome() . ": " . $pizza->getIngredientiExtra() ?> 
                </span>
                <span class="price">
                    <?= "€" . $pizza->getPrezzo() ?>
                </span>
            </div>
        </div>
        <div class="galleryPreviewArrows">
            <a href="#" class="prev">&lt;</a>
            <a href="#" class="next">&gt;</a>
        </div>
    </div>
    <div class="galleryNavBullets">
        <?php 
        foreach ($listaPizzeConImg as $key => $value) {
            ?>
        <a class="galleryBullet" id=<?=$key?>></a>
        <?php
        }
        ?>
    </div>
</div>
<?php
        break;
}
?>