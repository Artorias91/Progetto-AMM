<div>
    <h2>Indirizzo di consegna</h2>
    <form method="post" action="cliente/impostazioni<?= '?'.$vd->scriviToken()?>">
        <input type="hidden" name="cmd" value="indirizzo"/>    
        <label for="indirizzo">Indirizzo</label>
        <input name="indirizzo" id="indirizzo" value="<?= $user->getIndirizzo() ?>"/></br>
        <label for="citta">Citta</label>
        <input name="citta" id="citta" value="<?= $user->getCitta() ?>"/></br>
        <label for="provincia">Provincia</label>
        <input name="provincia" id="provincia" value="<?= $user->getProvincia() ?>"/></br>
        <label for="cap">CAP</label>
        <input name="cap" id="cap" value="<?= $user->getCap() ?>"/></br>
        <input type="submit" value="Salva modifiche"/>
    </form>        
</div>
<div>
    <h2>Metodo di pagamento</h2>
    <form method="post" action="cliente/impostazioni<?= '?'.$vd->scriviToken()?>">
        <input type="hidden" name="cmd" value="pagamento"/>    
        <label for="ora">Ora</label>
        <input type="time" id="tempo"/></br>
        <label for="numero_carta">Numero carta di credito</label>
        <input name="numero_carta" id="numero_carta" value=""/></br>
        <label for="scadenza_carta">Data di scadenza</label>
        <input name="scadenza_carta" id="scadenza_carta" value=""/></br>
        <label for="titolare_carta">Nome Titolare carta</label>
        <input name="titolare_carta" id="titolare_carta" value=""/></br>
        <label for="cod_carta">Codice di sicurezza</label>
        <input name="cod_carta" id="cod_carta" type="password" value=""/></br>
        <input type="submit" value="Salva modifiche"/>

    </form>        
</div>