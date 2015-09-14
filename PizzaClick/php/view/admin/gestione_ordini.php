<?php 
    if(count($ordini) > 0) {
?>

<table class="ordini">
    <thead>
        <tr>
            <th>Indirizzo cliente</th>
            <th>Data ordine<!-- <a href="">&dtrif;</a>--></th>
            <th style="width: 45%;">Articoli<br><span style="font-size: x-small;">[qt&agrave;, tipo, dimensione e prezzo]</span></th>
            <th>Subtotale<br><span style="font-size: small;">(EURO)<!-- <a href="">&utrif;</a> --></span></th>
            <th>-</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $i = 0;
        foreach ($ordini as $ordine) { ?>
        <tr <?= $i % 2 == 1 ? 'class="par"' : '' ?>>
            <td class="resize">
                <ul style="list-style-type: none; padding-left: 0.5em;">     
                    <li><b><?=$ordine->getCliente()->getIndirizzo()->getDestinatario()?></b></li>
                    <li><?=$ordine->getCliente()->getIndirizzo()->getNomeIndirizzo()?></li>
                    <li><?=$ordine->getCliente()->getIndirizzo()->getCitta()?>, 
                            <?=$ordine->getCliente()->getIndirizzo()->getProvincia()?></li>
                    <li><?=$ordine->getCliente()->getIndirizzo()->getCAP()?></li>   
<!--                    <li><?=$ordine->getCliente()->getIndirizzo()->getTelefono()?></li>   -->
                </ul>                
            </td>
            <td><?=$ordine->getDataCreazione()?></td>
            <td class="resize">
                <ul>
                    <?php
                        foreach ($ordine->getArticoli() as $articolo) {
                    ?>
                    <li>x<?php echo $articolo->getQty() . ', ' 
                            . PizzaFactory::instance()->cercaPizzaPerId($articolo->getPizzaId())->getNome() . ', '
                            . $articolo->getSize() . ', '
                            . '€' . $articolo->getPrezzo(); ?></li>
                    <?php                            
                        } ?>
                </ul>
            </td>
            <td><?=$ordine->getSubtotale()?></td>
            <td><a href="admin/ordini_attivi?cmd=invia&id=<?= $ordine->getId() ?>">Invia</a></td>
                
        </tr>
        <?php
            $i++;
        } ?>
            <tr><td colspan="6"><hr></td></tr>                                        
    </tbody>
</table>
<?php
    } else {
        ?>
<p>Nessun ordine trovato</p>
<?php
    }
?>