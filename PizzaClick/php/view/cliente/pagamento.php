<script src="../lib/jquery-ui-1.11.3/jquery-ui.min.js"></script>

<script>
    function checkIfOptionIsSelected() { 
        if (document.forms['pagamenti'].firstChild.parentElement[1].value == '') { 
            alert('Seleziona un metodo di pagamento');
            return false; 
        } 
        return true; 
    }
</script>

<form id="pagamenti" method="post" action="cliente/visualizza_pagamento" onsubmit="return checkIfOptionIsSelected()">
    <input type="hidden" name="cmd" value="v_pagamento"/>
    <label for="carta">Carta</label>
    <select name="carta" id="carta">
        <option value="" selected disabled>Seleziona ...</option>
    <?php foreach($pagamenti as $key => $value) { ?>
        <option value="<?= $key ?>">
            <?= 'termina con ' . substr($value->getNumeroCarta(), -4) ?>
        </option>
    <?php } ?>       
    </select>
    <input style="margin: 0;" type="submit" value="Visualizza"/>
</form>
