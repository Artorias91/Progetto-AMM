<div class="sub">
<?php
switch ($vd->getSottoPagina()) {
    case 'gestione_ordini':
        include 'gestione_ordini.php';
        break;
    default:
?>
    <h3>Gestione ordini</h3>
    <ul>
        <li>
            <a href="admin/gestione_ordini">Visualizza ordini attivi</a>
        </li>
    </ul>
<?php
        break;
}
?>
</div>