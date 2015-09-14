<div class="sub">
    <h3><?= $vd->getTitoloStep() ?></h3>
<?php
    switch($vd->getSottoPagina()) {
        case 'conferma_ordine_step1':
            ?>
<!--<div class="sub">
    <h3>Seleziona indirizzo di consegna</h3>-->
    <? include 'indirizzo.php'; ?>    
<!--</div>-->
<?php
            break;
        case 'conferma_ordine_step2':
            ?>
<!--<div class="sub">
    <h3>Riepilogo articoli</h3>    -->
    <div>
        <table class="resume-order">
            <thead>
                <tr>
                    <th style="width: 15%;">Qt&agrave;</th>
                    <th>Tipo</th>
                    <th style="width: 40%;">Ingredienti extra</th>
                    <th>Dimensione</th>
                    <th style="width: 15%;">Prezzo</th>
                    <th style="width: 10%;">-</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $i = 0;
                foreach ($_SESSION[self::elenco_articoli] as $key => $value) {
                    ?>
                <tr <?= $i % 2 == 1 ? 'class="par"' : '' ?> id="<?= $key ?>">
                    <td>&times;<?= $value->getQty() ?></td>
                    <td><?= $value->getPizza()->getNome() ?></td>
                    <td class="resize"><?= $value->getPizza()->getIngredientiExtra() ?></td>
                    <td><?= $value->getSize() ?></td>
                    <td>&euro;<?= $value->getPrezzoArticolo() ?></td>
                    <td>
                        <a href="cliente/conferma_ordine_step2?cmd=remove&key=<?=$key?>" alt="rimuovi">
                            <img alt="rimuovi" src="../img/remove-icon.png">
                        </a>
                    </td>
                </tr>
                    <?php
                    $i++;
                }
                ?>
                <tr><td colspan="6"><hr></td></tr>                                
            </tbody>
        </table>        
    </div>
    <? include 'resume.php'; ?>
    <hr style="width: 100%;">
    <div style="float: right;">
        <a href="cliente/conferma_ordine_step1">Indietro</a>        
        <form method="post" action="cliente/conferma_ordine_step3" style="display: inline;">
            <input type="submit" value="Continua">
        </form>
    </div>        
<!--</div>-->
<?php
            break;
        case 'conferma_ordine_step3':
            ?>
<!--<div class="sub">
    <h3>Seleziona metodo di pagamento</h3>-->
    <form method="post" action="cliente">    
        <input type="hidden" name="cmd" value="ordina"/>
        <table class="pagamenti">
            <thead>
                <tr>
                    <th>Le tue carte di credito</th>
                    <th>Titolare</th>
                    <th>Scadenza</th>
                </tr>
            </thead>                
            <tbody>
                <tr><td colspan="3"><hr></td></tr>
        <?php
        $i = 0;
        foreach($pagamenti as $pagamento) { ?>
                <tr>
                    <td>
                        <input <?= $i % 2 == 0 ? 'checked' : '' ?> type="radio" name="carta" value="<?=$pagamento->getId()?>">
                        <label style="width: auto;" for="carta"><?= 'termina con ' . substr($pagamento->getNumeroCarta(), -4) ?></label><br>                 
                    </td>
                    <td><?= $pagamento->getTitolareCarta() ?></td>
                    <td><?= $pagamento->getMeseScadenza() . '/' . $pagamento->getAnnoScadenza() ?></td>
                </tr>
            <?php
            $i++;    
        } ?>
                <tr><td colspan="3"><hr></td></tr>                
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3">
                        <input class="modifica" style="width: 100%; padding: 6px; margin: 0;" type="submit" value="Concludi ordine"/>        
                    </td>
                </tr>
            </tfoot>
        </table>
<!--        <a href="cliente?cmd=conferma_ordine_step2">Indietro</a>        -->
    </form>    
<!--</div>-->    
<?php
            
            break;
    } 
?>
</div>


