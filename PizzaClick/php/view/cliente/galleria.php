<!-- gallery -->
<link rel="stylesheet" type="text/css" href="../css/gallery-style.css">
<script type="text/javascript" src="../js/gallery-script-ajax.js"></script>

<div class="galleryContent">
    <div class="galleryPreviewContent">
        <div class="galleryPreviewFormDropdown" style="display: none;">
            <form class="gallery" id="aggiungi" method="post" action="index.php?page=cliente">
                <input type ="hidden" id="pizza-gallery" name="pizza-gallery" value="<?= $index ?>"/>                            
                <label for="size">Dimensione</label>
                <select name="size" id="size">
                    <option value="ridotta">Ridotta</option>
                    <option value="normale" selected>Normale</option>
                    <option value="grande">Grande</option>
                </select><br>
                <label for="quantity">Quantit&agrave;</label>
                <select name="quantity" id="quantity">
                <?php for($i = 1; $i < 11; $i++) { ?>
                    <option value="<?=$i?>"><?=$i?></option>
                <?php } ?>
                </select>
                  <button type="submit"name="cmd" value="add">Aggiungi al carrello</button>
            </form>          
        </div>            
        <div class="galleryPreviewImg">
            <img class="previewImg"
                 src="<?= $pizza->getUrlImg() ?>" 
                 alt="<?= $pizza->getNome() ?>" />
            <div class="galleryDescriptPreviewImg">
                <span class="descriptPreviewImg">
                    <?= $pizza->getNome() . ": " . $pizza->getIngredientiExtra() ?> 
                </span>
                <span class="price">EURO <?= $pizza->getPrezzo() ?></span>
            </div>
        </div>
        <div class="galleryPreviewArrows">
            <a href="#" class="prev">&lt;</a>
            <a href="#" class="next">&gt;</a>
        </div>
    </div>
    <div class="galleryNavBullets">
        <?php 
        foreach ($listaPizzeConImg as $key => $value) {
            ?>
        <a class="galleryBullet" id=<?=$key?>></a>
        <?php
        }
        ?>
    </div>
</div>
