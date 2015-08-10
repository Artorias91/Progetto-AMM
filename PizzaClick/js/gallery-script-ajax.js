function changePizza(pizza) {
//    console.log(pizza);
    //image
    $('img.previewImg').attr({
        src: pizza.image,
        alt: pizza.name
    });
    //description
    $('span.descriptPreviewImg').text(pizza.name + ": " + pizza.ingredients);
    //price
    $('span.price').text("€" + pizza.price);
    
    //hidden input
    $("input#pizza-gallery").attr("value", pizza.id);

    $('a.galleryBullet').removeClass("active");
    
    /* stranezze: a volte funziona altre no 
     * (probabilmente dovuto ad un conflitto tra id) ...
     */
//    $('a.galleryBullet#' + pizza.id).addClass("active");
    
    /* ... per questo motivo, meglio usare javascript
     */
    document.getElementsByClassName("galleryBullet")[pizza.id]
    .className += " active";

    // fai il reset del timer e riavvialo
//    clearInterval(slot);
//    timer();
}

/**
 * slot di tempo per ciascuna pizza
 */
var slot = 0;

/**
 * Cambia automaticamente la pizza da visualizzare,
 * generando un click ogni 5 sec
 */
var timer = function() {
    slot = setInterval(function () { $('a.next').click(); }, 5000);
}


$(document).ready(function () {
//    console.log($("input#pizza-gallery").attr("value"));
    // avvia il timer
//    timer();

    $('.galleryNavBullets a:first-child').addClass("active");
    
    //BULLETS
    $('a.galleryBullet').click(function () {
       $.ajax({
           url: "index.php?page=cliente",
           data: {
               cmd: 'go',
               id: $(this).attr("id")
           },
            dataType: 'json',
            success : function(data, state) {
                changePizza(data);
            },
            error : function(data, state) {
                console.log("status error: " + state);
                console.log(data);
            }            
        }) 
    });
    

    //FRECCIA INDIETRO
    $('a.prev').click(function (e) {
        e.preventDefault();
        $.ajax({
            url: "index.php?page=cliente",
            data: {
                cmd: 'prev',
                id: $("input#pizza-gallery").attr("value")
            },
            dataType: 'json',
            success : function(data, state) {
                changePizza(data);
            },
            error : function(data, state) {
                console.log("status error: " + state);
                console.log(data);
            }
        });
//        return false;
    });

    //FRECCIA AVANTI
    $('a.next').click(function (e) {
        e.preventDefault();

        $.ajax({
            url: "index.php?page=cliente",
            data: {
                cmd: 'next',
                id: $("input#pizza-gallery").attr("value")
            },
            dataType: 'json',
            success : function(data, state) {
                changePizza(data);
            },
            error : function(data, state) {
                console.log("status error: " + state);
                console.log(data);
            }
        });
//        return false;
    });

})

$(document).ready(function () {
    $(".galleryPreviewImg").click(function (e) {
        e.preventDefault();
        $("div.galleryPreviewFormDropdown").toggle();
    });
    $(document).click(function(e){
        if(!$(e.target).closest('.galleryPreviewImg, div.galleryPreviewFormDropdown').length) {
            //$("div.cart").removeClass("active");
            $("div.galleryPreviewFormDropdown").hide();

            $('.galleryPreviewFormDropdown select option').prop('selected', function() {
                return this.defaultSelected;
            });           
        }
    });        
})