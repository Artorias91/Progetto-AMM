<form method="post" action="cliente/indirizzo">
    <input type="hidden" name="cmd" value="aggiorna_indirizzo"/>    
    <label for="destinatario">Destinatario</label>
    <input class="text" required placeholder="Nome e cognome" name="destinatario" id="destinatario" 
           value="<?= $user->getIndirizzo()->getDestinatario() ?>"/>
    <br>
    <label for="indirizzo">Indirizzo</label>
    <input class="text" required placeholder="Via e numero civico" name="indirizzo" id="indirizzo" 
           value="<?= $user->getIndirizzo()->getNomeIndirizzo() ?>"/>
    <br>        
    <label for="citta">Citta</label>
    <input class="text" required name="citta" id="citta" 
           value="<?= $user->getIndirizzo()->getCitta() ?>"/>
    <br>
    <label for="provincia">Provincia</label>
    <input class="text" required name="provincia" id="provincia" 
           value="<?= $user->getIndirizzo()->getProvincia() ?>"/>
    <br>
    <label for="cap">CAP</label>
    <input class="text" required name="cap" id="cap" 
           value="<?= $user->getIndirizzo()->getCap() ?>"/>
    <br>
    <label for="telefono">Telefono</label>
    <input class="text" required placeholder="&phone;" maxlength="10" name="telefono" id="telefono" 
           value="<?= $user->getIndirizzo()->getTelefono() ?>"/>
    <br>                    
    <input class="modifica" type="submit" value="Salva modifiche"/>
</form>