
<?php 
    if(count($ordini) > 0) {
?>

<table class="ordini">
    <thead>
        <tr>
            <th>n.</th>
            <th>Data ordine</th>
            <th style="width: 45%;">Articoli<br><span style="font-size: x-small;">[qt&agrave;, tipo, dimensione e prezzo]</span></th>
            <th>Subtotale<br><span style="font-size: small;">(EURO)</span></th>
            <th>Stato</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $i = 0;
        foreach ($ordini as $ordine) { ?>
        <tr <?= $i % 2 == 1 ? 'class="par"' : '' ?>>
            <td><?=$ordine->getId()?></td>
            <td><?=$ordine->getDataCreazione()?></td>
            <td>
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
            <td><?=$ordine->getDataConclusione()?></td>
                
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