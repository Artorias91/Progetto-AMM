<div class="content-resume-price" 
     style="<?= $vd->getSottoPagina() != 'conferma_ordine_step2' ? 'width: 65%; margin-bottom: 5%;' : 'width: 45%; margin: 5% 0;' ?>">        
    <table class="resume-price">
        <tbody>
            <tr>
                <td style="text-align: left;">Articoli (<?= $this->getQtyTotalePizze() ?>)</td>
                <td style="text-align: right;">&euro;<?= $this->getSubTotale() ?></td>
            </tr>
            <tr>
                <td style="text-align: left;">Costi spedizione</td>
                <td style="text-align: right;">&euro;3,00</td>
            </tr>
            <? if($vd->getSottoPagina() == 'conferma_ordine_step3') { ?>
            <tr>
                <td colspan="2" style="text-align: left;">
                    <a href="cliente/conferma_ordine_step2">Modifica</a>                    
                </td>
            </tr>
            <? } ?>
            <tr>
                <td colspan="2"><hr></td>
            </tr>
            <tr>
                <td style="text-align: left;"><strong>Totale ordine</strong></td>
                <td style="text-align: right;"><strong>&euro;<?= $this->getSubTotale(true) ?></strong></td>
            </tr>
        </tbody>
    </table>
</div>
<div style="clear: both;"></div>    
