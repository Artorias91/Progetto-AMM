<ul>
    <li style="list-style-type: none;">
        <?= $user->getNome() ?> <?= $user->getCognome() ?>
    </li>
    <li>
        <a href="cliente/home">Home</a>
    </li>
    <li>
        <a href="cliente/account">Impostazioni account</a>
    </li>
</ul>
<script>
    $(function() {
      $( "#calendar" ).datepicker($.datepicker.regional['it']);
    });
</script>
<div id="calendar" style="height: 232px;"></div>