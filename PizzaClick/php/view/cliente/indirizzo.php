<div style="padding: 3%; border: 1px solid #ddd; border-radius: 3px; background-color: #F0EFEF; <?= 
$vd->getSottoPagina() != 'conferma_ordine_step1' ? 'margin-left: 36%;' : '' ?>">
    <ul style="list-style-type: none;">     
        <li><b><?=$user->getIndirizzo()->getDestinatario()?></b></li>
        <li><?=$user->getIndirizzo()->getNomeIndirizzo()?></li>
        <li><?=$user->getIndirizzo()->getCitta()?>, <?=$user->getIndirizzo()->getProvincia()?></li>
        <li><?=$user->getIndirizzo()->getCAP()?></li>
        <li><a href="cliente/indirizzo">Modifica</a></li>
            <? if($vd->getSottoPagina() == 'conferma_ordine_step1') { ?>        
        <li><hr style="width: 100%;"></li>
        <li>
            <form method="post" action="cliente/conferma_ordine_step2" style="display: inline;">
                <input type="submit" value="Invia a questo indirizzo">
            </form>                   
        </li>
            <? } ?>
    </ul>
</div>
