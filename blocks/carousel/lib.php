<?php

function block_carousel_pluginfile($course, $birecord_or_cm, $context, $filearea, $args, $forcedownload) {
    global $SCRIPT;

    if ($context->contextlevel != CONTEXT_BLOCK) {
        send_file_not_found();
    }

    require_course_login($course);

    if ($filearea !== 'attachment') {
        send_file_not_found();
    }

    $fs = get_file_storage();

    $filename = array_pop($args);
    $itemid = $args ? implode('/', $args) : '0';
    if (!$file = $fs->get_file($context->id, 'block_carousel', 'attachment', $itemid, "/", $filename) or $file->is_directory()) {
        send_file_not_found();
    }

    if ($parentcontext = get_context_instance_by_id($birecord_or_cm->parentcontextid)) {
        if ($parentcontext->contextlevel == CONTEXT_USER) {
            // force download on all personal pages including /my/
            //because we do not have reliable way to find out from where this is used
            $forcedownload = true;
        }
    } else {
        // weird, there should be parent context, better force dowload then
        $forcedownload = true;
    }

    session_get_instance()->write_close();
    send_stored_file($file, 60*60, 0, $forcedownload);
}

function block_carousel_transparent_text($thetext="No text entered", $textcolor="#FFFFFF", $textsize=24) {
    $angle = 0;
    $font = "arial";
    $rgb = str_split(ltrim($textcolor,'#'),2); 

    $size = imagettfbbox($textsize, $angle, $font, $thetext);
    // Without that "+10" there, it clips a bit of the last letter.  No idea why.
    $textbox = imagecreatetruecolor(abs($size[2]) + abs($size[0]+10), abs($size[5]) + abs($size[1]));
    imagesavealpha($textbox, true);
    imagealphablending($textbox, false);

    $transparentcolor = imagecolorallocatealpha($textbox, 00, 00, 00, 127);
    imagefill($textbox, 0, 0, $transparentcolor);

    $textcolor = imagecolorallocate($textbox,hexdec($rgb[0]),hexdec($rgb[1]),hexdec($rgb[2]));
    imagettftext($textbox, $textsize, 0, 0, abs($size[5]), $textcolor, $font, $thetext);

    return($textbox);
}

function block_carousel_create_overlay($parameters) {
    //$in = $_GET['image'];
    // Get what we need from the URL parameters
    parse_str(urldecode($parameters)); 
    //$image = $image_location;
    if(!isset($title)) {
        $title = ucwords("This image still needs a title");
    }
    if(!isset($description)) {
        $description = ucwords("This image still needs a description");
    }
    //if(isset($title)) {
        //$title = ucwords($title);
    //} else {
        //$title = ucwords("This image still needs a title");
    //}
    //if(isset($description)) {
        //$description = ucwords($description);
    //} else {
        //$description = ucwords("This image still needs a description");
    //}
    if(!isset($overlay_height)) {
        $overlay_height = '100';
    }
    if(!isset($overlay_width)) {
        $image_properties = getimagesize($image);
        $overlay_width = $image_properties[0];
    }
    //@$overlay_height = $_GET['height'];
    //@$overlay_width = $_GET['width'];

    // -255 = lightest,255 = darkest
    $brightness = 175;
    $opacity = 127;
    $determine_image_type = getimagesize($image);
    //echo("<!-- flurg");
    //error_log(implode(",",$determine_image_type));
    //echo("-->");
    switch($determine_image_type[2]) {
      case 1:
        $in = imagecreatefromgif($image);
        break;
      case 2:
        $in = imagecreatefromjpeg($image);
        break;
      case 3:
        $in = imagecreatefrompng($image);
        break;
    }
    $height = imagesy($in);
    $width = imagesx($in);
    $how_far_down = 300;
    $title_color = '#FFFFFF';
    $description_color = '#ede39b';
    $out = imagecreatetruecolor($width, $height);
    $overlay = imagecreatetruecolor($overlay_width, $overlay_height);
    $bg2 = imagecolortransparent($overlay, imagecolorallocatealpha($overlay, 255, 255, 255, 127));
    imagecopy($out, $in, 0, 0, 0, 0, $width, $height);
    imagealphablending($out, false);
    imagecopy($overlay, $out, 0, 0, 0, $how_far_down, $width, $height);
    imagesavealpha($overlay, true);
    $opacity_slice = imagecreatetruecolor(1, $overlay_height);
    $bg1 = imagecolortransparent($opacity_slice, imagecolorallocatealpha($opacity_slice, 255, 255, 255, 127));
    imagefill($opacity_slice, 0, 0, $bg1);

    for ($x = 0; $x < $overlay_width; $x++) {    
        $t = (((127-$opacity)) + ($opacity * ($x / $overlay_width)));
        $b = (((0 - $brightness)) + ($brightness * ($x / $overlay_width)));
        //imagecopy($opacity_slice, $out, 0, 0, $overlay_width - $x, $how_far_down, 1, $overlay_height);
        imagecopymerge($opacity_slice, $out, 0, 0, $overlay_width - $x, $how_far_down, 1, $overlay_height, 100);
        imagefilter($opacity_slice, IMG_FILTER_BRIGHTNESS, $b);
        imagefilter($opacity_slice, IMG_FILTER_COLORIZE, 0, 0, 0, $t);
        imagecopyresampled($overlay, $opacity_slice, $overlay_width - $x, 0, 0, 0, 1, $overlay_height, 1, $overlay_height);
    }

    $title_text = block_carousel_transparent_text($title, $title_color, 20);
    $description_text = block_carousel_transparent_text($description, $description_color, 14);
    imagecopy($overlay, $title_text, $width - imagesx($title_text) - 10, 20, 0, 0, imagesx($title_text), imagesy($title_text));
    imagecopy($overlay, $description_text, $width - imagesx($description_text) - 10, 20 + imagesy($title_text) + 10, 0, 0, imagesx($description_text), imagesy($description_text));
    imagesavealpha($overlay, true);
    imagecopymerge($out, $overlay, 0, $how_far_down, 0, 0, $overlay_width, $overlay_height, 100);
    imagesavealpha($out, true);

    header('Content-type: image/png');
    imagepng($out);
}

?>
