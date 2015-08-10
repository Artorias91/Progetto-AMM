<?php
    $json = array();

    $json["id"] = $index;
    $json["name"] = $pizza->getNome();
    $json["price"] = $pizza->getPrezzo();
    $json["ingredients"] = $pizza->getIngredientiExtra();
    
    $json["image"] = $pizza->getUrlImg();
    //        $json["img_width"] = $pizza->getImgWidth();
    //        $json["img_height"] = $pizza->getImgHeight();

    echo json_encode($json);     
?>
