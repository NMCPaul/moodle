<?php

    require_once('lib.php');
    $clean = urldecode($_SERVER['QUERY_STRING']);
    parse_str($clean);
    $image_details = getimagesize($image);
    header('Content-type: image/png');
    //$finished_image = block_carousel_create_overlay($image, $title, $description, $overlay_width, $overlay_height);
    $finished_image = block_carousel_create_overlay($_SERVER['QUERY_STRING']);
?>
