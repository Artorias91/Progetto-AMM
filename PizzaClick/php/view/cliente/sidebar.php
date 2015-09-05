<?php
switch ($vd->getSottoPagina()) {
    case 'cronologia_ordini': case 'account': case 'base': case 'password': case 'indirizzo': case 'pagamento': case 'visualizza_pagamento': 
        include 'sidebar_account.php';
        break;
    case 'conferma_ordine_step1': case 'conferma_ordine_step2': case 'conferma_ordine_step3':
?>
<div class="sub" style="margin-bottom: 5%; background-color: #FFF5E8;">
    <ol style="float: right;">
        <li class="<?= $vd->getSottoPagina() == 'conferma_ordine_step1' ? 'actual' : '' ?>">Seleziona indirizzo di spedizione</li>
        <li class="<? echo $vd->getSottoPagina() == 'conferma_ordine_step2' ? 'actual ' : ''; 
        echo $vd->getSottoPagina() == 'conferma_ordine_step2' || $vd->getSottoPagina() == 'conferma_ordine_step3' ? 'completed' : 'uncompleted' ?>"
            >Modifica articoli</li>
        <li class="<? echo $vd->getSottoPagina() == 'conferma_ordine_step3' ? 'actual ' : ''; 
        echo $vd->getSottoPagina() == 'conferma_ordine_step3' ? 'completed' : 'uncompleted' ?>"
            >Seleziona metodo di pagamento<br>e conferma ordine</li>
    </ol>
    <div style="clear: both;"></div>        
</div>
<?      if($vd->getSottoPagina() != 'conferma_ordine_step1') { ?> 
<div class="sub" style="margin-top: 5%;">
    <?php
            if($vd->getSottoPagina() == 'conferma_ordine_step3') {
                include_once 'resume.php';                
            }
            include_once 'indirizzo.php';    
    ?>
</div> 
    <?      
        } 
        break;
        default:
            include 'selectmenu.php';
            break;
    }
?>
