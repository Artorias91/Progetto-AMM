<?php
    switch ($vd->getSottoPagina()) {
        case 'account':
            include 'sidebar_account.php';
            break;
        case 'base': case 'password': case 'indirizzo': case 'pagamento': case 'visualizza_pagamento': 
            include 'sidebar_account.php';
            break;
//        case '':
//            include '';
//            break;        
        default:
?>
<!-- lib jQuery UI -->
<!-- <script src="../lib/jquery-ui-1.11.3/jquery-ui.min.js"></script>
<link rel="stylesheet" href="../lib/jquery-ui-1.11.3/jquery-ui.min.css"> -->

<!-- script e css -->
<!-- <script type="text/javascript" src="../js/sidebar-form-ui.js"></script> -->
<link rel="stylesheet" type="text/css" href="../css/form-style.css">


<script>
function checkIfOptionIsSelected() { 
    if (document.forms['aggiungi'].firstChild.parentElement[0].value == '') { 
        alert('Seleziona il tipo di pizza');
        return false; 
    } 
    return true; 
}
</script>    
<div class="selection">
    <h2>Scegli la tua pizza preferita</h2><hr>
    <form class="selection" id="aggiungi" method="post" action="index.php?page=cliente" onsubmit="return checkIfOptionIsSelected()">
        <label for="pizza">Tipo</label>
        <select name="pizza-selection" id="pizza-selection">
           <option value="" selected disabled>Seleziona ...</option>
           <?php 
           foreach (PizzaFactory::instance()->getListaPizze(false) as $value) {
               ?>
           <option value="<?=$value->getId()?>"
                   data-ingredienti="<?=$value->getIngredientiExtra()?>"
                   data-prezzo="<?=$value->getPrezzo()?>">
                       <?=$value->getNome()?>
           </option>
           <?php
           }
           ?>
        </select><br>
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
        </select><br>
        <button class="addPizza" type="submit" name="cmd" value="add">
            <div class="add-icon"></div>
            <div class="add-text">Aggiungi al carrello</div>
        </button>
    </form>
</div>
<?php
            break;
    }
?>
