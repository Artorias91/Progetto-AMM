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
            ?>
            <?php
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
        <footer>
            <h1>footer</h1>
        </footer>
        
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