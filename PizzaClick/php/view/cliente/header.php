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
<?php
if($vd->getSottoPagina() == 'home' || 
        $vd->getSottoPagina() == 'account') {
    ?>
<div class="cart-container">
    <div class="cart cart-off">
        <div class="cart-icon"></div>
        <div class="cart-qty">
            <div class="cart-qty-ct"> <?= $this->getQtyTotalePizze() ?> </div>
        </div>
    </div>
</div>
    <?php
    if(isset($_SESSION[self::elenco_articoli]) && 
            count($_SESSION[self::elenco_articoli]) > 0) {
        ?>
<div class="dropdown-pointer left-pointer" style="display: none;"></div>
<div class="dropdown left-dd" style="display: none;">
    <table class="carrello" style="font-size: 9pt;">
        <thead>
            <tr>
                <th>QTA'</th>
                <th>TIPO</th>
                <th>DIM</th>
                <th>EURO (1)</th>
                <th>-</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td colspan="5"><hr></td>
            </tr>                
            <?php
            foreach ($_SESSION[self::elenco_articoli] as $key => $value) {
                ?>
            <tr id="<?= $key ?>" class="item">
                <td><?= $value->getQty() ?></td>
                <td><?= $value->getPizza()->getNome() ?></td>
                <td><?= $value->getSize() ?></td>
                <td><?= $value->getPrezzoPizza() ?></td>                
                <td>
                    <a href="cliente/home?cmd=remove&key=<?=$key?>" alt="rimuovi">
                        <img alt="rimuovi" src="../img/remove-icon.png">
                    </a>                        
                </td>
            </tr>
                <?php  
            }
            ?>
            <tr>
                <td colspan="5"><hr></td>
            </tr>
        </tbody>
        <tfoot style="text-align: center;">
            <tr style="vertical-align: super; font-weight: bold; text-shadow: 0px 1px 0px #555;">
                <?php $a = $this->getQtyTotalePizze() > 1 ? ' pizze' : ' pizza'; ?>
                <td><?= $this->getQtyTotalePizze() . $a ?></td>
                <td colspan="2" style="text-align: left; padding-left: 5%;">TOTALE:<br>
                    <span style="font-size: x-small; font-weight: normal;">Esclusi costi spedizione</span>
                </td>
                <td ><?= $this->getSubTotale() ?></td>
            </tr>
            <tr>
                <td colspan="5" style="padding-top: 3%;">
                    <form method="post" action="cliente/conferma_ordine_step1" style="display: inline;">
                        <input class="checkout" type="submit" value="Ordina">
                    </form>                        
                </td>                    
            </tr>
        </tfoot>                
    </table>
</div>
    <?php        
    }   
}
?>
<div class="nav">
    <ul>
        <li>
            <a href="cliente/home">Home</a>
        </li>
        <li class="account">Account</li>		
    </ul>                
</div>
<div class="dropdown-pointer right-pointer" style="display: none;"></div>
<div class="dropdown right-dd" style="display: none;">
    <ul>
        <li>
            <a href="cliente/account">Gestione account</a>
        </li>
        <li>
            <a class="logout" href="cliente?cmd=logout">Esci</a>
        </li>
    </ul>		
</div>
