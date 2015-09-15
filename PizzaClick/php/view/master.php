<?php
    include_once 'ViewDescriptor.php';
    if(!$vd->isJson()) {    
?>
<!DOCTYPE html>
<html>
    <head>
        <title><?= $vd->getTitolo() ?></title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">    
        <base href="<?= Settings::getApplicationPath() ?>php/"/>
        <link type="image/x-icon" rel="shortcut icon" href="../img/pizza-favicon.png">

        <!-- lib -->
        <script src="../lib/jquery-1.11.2.min.js"></script>

        <!-- home base -->
        <link rel="stylesheet" type="text/css" href="../css/style.css">
        <!--<script type="text/javascript" src="js/script.js"></script>-->

    </head>

    <body>
        <div class="page">

            <div class="header">
                <?php
                    $header = $vd->getHeaderFile();
                    require "$header";
                ?>
            </div>

            <div class="sidebar">
                <?php
                    $sidebar = $vd->getSidebarFile();
                    require "$sidebar";
                ?>
            </div>

            <div class="content">
                <?php
                $content = $vd->getContentFile();
                require "$content";
                
                if ($vd->getMessaggioErrore() != null) {
                    ?>
                    <div class="error">
                        <div>
                            <?=
                            $vd->getMessaggioErrore();
                            ?>
                        </div>
                    </div>
                    <?php
                }
                ?>
                <?php
                if ($vd->getMessaggioConferma() != null) {
                    ?>
                    <div class="confirm">
                        <div>
                            <?=
                            $vd->getMessaggioConferma();
                            ?>
                        </div>
                    </div>
                    <?php
                }
                ?>
            </div>
            <div style="clear: both;"></div>
            <div class="footer">
                <div class="footer-content">
                <ul>
                    <li>PizzaClick, Mattia Contini, 2015</li>
                    <li>
                        <a class="html" href="http://validator.w3.org/check/referer">HTML Valid</a>                
                    </li>
                    <li>
                        <a class="css" href="http://jigsaw.w3.org/css-validator/check/referer">CSS Valid</a>                
                    </li>
                </ul>
                </div>
            </div>
        </div>
    </body>
</html>
<?php         
    } else {
        header('Cache-Control: no-cache, must-revalidate');
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
        header('Content-type: application/json');
        
        $ajax = $vd->getAjaxFile();
        require "$ajax";
    }
?>
