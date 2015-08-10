/**
 * In questa funzione viene utilizzato JQuery UI per creare/applicare alcuni widget 
 * (selectmenu e tooltip) ad elementi presenti nel document (select e option)
 **/
$(function () {
   
  $(".addPizza").prop('disabled', true);
  
  // applica il widget (selectmenu) alla prima select con label 'Nome': 
  $("#pizza-selection").selectmenu({
    icons: {button: "ui-icon-circle-triangle-s"},
    width: 130,

    open: function(event, ui) {
      // Crea un tooltip che punta all'elemento evidenziato (tag option)
      $("ul#pizza-selection-menu > li.ui-menu-item").tooltip({
        // posizione del tooltip rispetto all'elemento evidenziato
        position : {
          my: 'left center', 
          at: 'right+10 center',
          collision: 'none'
        },
        // css da aplicare al tooltip
        tooltipClass : 'right',
        items: 'li',
        // il testo da visualizzare all'interno del tooltip
        content : function() {
          var $data_ingred = $(this).data("uiSelectmenuItem").element.data("ingredienti");
          var $data_prezzo = $(this).data("uiSelectmenuItem").element.data("prezzo");

          return '<b>Ingredienti extra: </b>' + $data_ingred + '<br><b>Prezzo: </b>€' + $data_prezzo;
        }
      });
    },
    select: function(event, ui) {
      $("div.ui-helper-hidden-accessible").remove();
      $("div.ui-effects-wrapper").remove();
    },
    close: function(event, ui) {
      $("div.ui-helper-hidden-accessible").remove();
      $("div.ui-effects-wrapper").remove();
    },
    change: function(event, ui) {
        $("#size").selectmenu("enable");
        $("#quantity").selectmenu("enable");
        $(".addPizza").prop("disabled", false);
        $(".addPizza").addClass('addPizza-on');
    }

   });

    // applica il widget (selectmenu) alla  seconda select con label 'Dimensione'
    $("#size").selectmenu({
        icons: {button: "ui-icon-circle-triangle-s"},
        width: 130
        ,disabled: true
    });

    // applica il widget (selectmenu) alla terza e ultima select con label 'Quantità'
    $("#quantity")
    .selectmenu({
        icons: {button: "ui-icon-circle-triangle-s"},
        width: 130
        ,disabled: true
    })
    .selectmenu( "menuWidget")
    .addClass( "overflow" );

});