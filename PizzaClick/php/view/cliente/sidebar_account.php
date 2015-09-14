<div class="sub">
    <h3>Cliente</h3>
    <ul>
        <li>
            <a href="cliente/home">Home</a>
        </li>
        <li>
            <a href="cliente/account">Account</a>
            <?php if ($vd->getSottoPagina() == 'cronologia_ordini' || 
                    $vd->getSottoPagina() == 'ordini_attivi') { ?>
            <ul>
                <li><a href="cliente/cronologia_ordini">Visualizza cronologia ordini</a></li>
                <li><a href="cliente/ordini_attivi">Visualizza ordini in corso</a></li>
            </ul>
            <?php } ?>
        </li>
    </ul>
</div>