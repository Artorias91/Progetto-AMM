<form method="get" action="cliente/visualizza_pagamento">
    <label for="titolareCarta">Nome del titolare della carta</label>
    <input class="text" readonly required placeholder="Nome e cognome" name="titolareCarta" id="titolareCarta"
           value="<?= $carta->getTitolareCarta() ?>"/><br>
    <label for="numeroCarta">Numero della carta</label>
    <input class="text" readonly required maxlength="16" name="numeroCarta" id="numeroCarta"
           value="<?= $carta->getNumeroCarta() ?>"/><br>
    <label for="codiceCarta">Codice di sicurezza</label>
    <input class="text" readonly required maxlength="3" name="codiceCarta" type="password" id="codiceCarta"
           value="<?= $carta->getCodiceCarta() ?>"/><br>
    <fieldset class="scadenza">
        <legend>Data di scadenza</legend>
        <label for="mese">Mese</label>
        <select disabled name="mese" id="mese">
        <?php for($i = 1; $i <= 12; $i++) { ?>
            <option value="<?=$i > 9 ? $i : 0 . $i?>" 
            <?= $carta->
                    getMeseScadenza() == $i ? 'selected' : '' ?>
            ><?=$i > 9 ? $i : 0 . $i?></option>
        <?php } ?>            
        </select>
        <label for="anno">Anno</label>
        <select disabled name="anno" id="anno">
        <?php for($i = 2018; $i <= 2033; $i++) { ?>
            <option value="<?=$i?>" 
            <?= $carta->
                    getAnnoScadenza() == substr($i, -2) ? 'selected' : '' ?>                    
            ><?=$i?></option>
        <?php } ?>            
        </select>            
    </fieldset>
</form>