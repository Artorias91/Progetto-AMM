<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <base href="<?='http://' . $_SERVER['HTTP_HOST'] . '/PizzaClick/'?>"/>
        <title>Errore</title>
    </head>
    <body>
        <h1><?= $titolo ?></h1>
        <p>
            <?=
            $messaggio
            ?>
        </p>
        <?php if (isset($login)) { ?>
        <p>Puoi autenticarti alla pagina di <a href="<?= Settings::getApplicationPath() ?>php/login">login</a></p>
        <?php } ?>
    </body>
</html>
