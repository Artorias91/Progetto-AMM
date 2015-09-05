<form method="get" action="cliente/impostazioni<?= '?'.$vd->scriviToken()?>">
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
    <!--<fieldset>
        <legend>Indirizzo associato</legend>
        <label for="destinatario">Destinatario</label>
        <input required placeholder="Nome e cognome" name="destinatario" id="destinatario" 
               value="<?= $carta->
                getIndirizzo()->getDestinatario() ?>"/>
        <br>
        <label for="indirizzo">Indirizzo</label>
        <input required placeholder="Via e numero civico" name="indirizzo" id="indirizzo" 
               value="<?= $carta->
                getIndirizzo()->getNomeIndirizzo() ?>"/>
        <br>        
        <label for="citta">Citta</label>
        <input required name="citta" id="citta" 
               value="<?= $carta->
                getIndirizzo()->getCitta() ?>"/>
        <br>
        <label for="provincia">Provincia</label>
        <input required name="provincia" id="provincia" 
               value="<?= $carta->
                getIndirizzo()->getProvincia() ?>"/>
        <br>
        <label for="cap">CAP</label>
        <input required name="cap" id="cap" 
               value="<?= $carta->
                getIndirizzo()->getCap() ?>"/>
        <br>
        <label for="telefono">Telefono</label>
        <input required maxlength="10" name="telefono" id="telefono" 
               value="<?= $carta->
                getIndirizzo()->getTelefono() ?>"/>
        <br>                    
    </fieldset>-->
</form>