<form method="post" action="cliente/visualizza_pagamento">
    <input type="hidden" name="cmd" value="v_pagamento"/>
    <label for="carta">Carta</label>
    <select name="carta" id="carta">
        <option value="" selected disabled>Seleziona ...</option>
    <?php foreach($pagamenti as $pagamento) { ?>
        <option value="<?=$pagamento->getId()?>">
            <?= 'termina con ' . substr($pagamento->getNumeroCarta(), -4) ?>
        </option>
    <?php } ?>       
    </select>
    <input style="margin: 0;" type="submit" value="Visualizza"/>
</form>
