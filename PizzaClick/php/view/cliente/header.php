<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<script type="text/javascript">
$(document).ready(function () {
    $("div.cart").click(function (e) {
        //$(this).addClass("active");
        $("div.dropdown.left-dd").toggle();
        $("div.dropdown-pointer.left-pointer").toggle();
        e.preventDefault();
    });
    $(document).click(function(e){
        if(!$(e.target).closest('div.cart, div.dropdown, li.account').length){
            //$("div.cart").removeClass("active");
            $("div.dropdown").hide();
            $("div.dropdown-pointer").hide();
        }
    });

    $("li.account").click(function (e) {
        $("div.dropdown.right-dd").toggle();
        $("div.dropdown-pointer.right-pointer").toggle();
        e.preventDefault();
    });
    
    if(document.getElementsByClassName("left-dd")[0] !== undefined) {
        document.getElementsByClassName("cart")[0].className = 'cart';
        document.getElementsByClassName("cart")[0].className += ' cart-on';        
    }
})
</script>
<nav>
    <?php
    if($vd->getSottoPagina() == 'home' || 
            $vd->getSottoPagina() == 'account') {
        ?>
    <div class="cart-container">
        <div class="cart cart-off">
            <div class="cart-icon"></div>
            <div class="cart-qty">
                <div class="cart-qty-ct"> <?= $this->getQtyTotalePizze()?> </div>
            </div>
        </div>
    </div>
        <?php
        if(isset($_SESSION[self::elenco_articoli]) && 
                count($_SESSION[self::elenco_articoli]) > 0) {
            ?>
    <div class="dropdown-pointer left-pointer" style="display: none;"></div>
    <div class="dropdown left-dd" style="display: none;">
        <ul class="items">
            <?php
            foreach ($_SESSION[self::elenco_articoli] as $key => $value) {
                ?>
            <li id="<?= $key ?>" class="item">
                <span>&times;<?= $value->getQty() ?></span>
                <span><?= $value->getPizza()->getNome() ?></span>
                <span><?= $value->getSize() ?></span>
                <span>&euro;<?= $value->getPrezzoPizza() ?></span>                
                <a class="remove-item" href="cliente?cmd=remove&key=<?=$key?>"></a>
            </li>
                <?php  
            }
            ?>
        </ul>		
        <div class="resume">
            <span class="qty" style="position: relative; left: -43px;"><?= $this->getQtyTotalePizze() . ' pizze'?></span>
            <span class="subtot" style=" position: relative; right: -55px;">&euro;<?= $this->getPrezzototale() ?></span>
            <form method="get" action="cliente?cmd=conferma_ordine_step1" style="display: inline;">
                <input style="position: relative; right: -66px;" class="checkout" type="submit" value="Ordina">
            </form>
        </div>
    </div>
        <?php        
        }   
    }
    ?>
    <div class="nav">
        <ul>
            <li class="link">
                <a href="cliente/home">Home</a>
            </li>
            <li class="link account">Account
                <!--<a class="account" href="<?='http://' . $_SERVER['HTTP_HOST'] . '/PizzaClick/'?>account">Account</a>-->
            </li>		
        </ul>                
    </div>
    <div class="dropdown-pointer right-pointer" style="display: none;"></div>
    <div class="dropdown right-dd" style="display: none;">
        <ul class="ul-account">
            <li>
                <a href="cliente/account">Gestione account</a>
            </li>
            <li>
                <p class="logout">
                    <a href="cliente?cmd=logout">Logout</a>
                </p>
            </li>
        </ul>		
    </div>

</nav>
