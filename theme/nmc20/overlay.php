<?php
    $in = $_GET['image'];
    $title = $_GET['title'];
    $description = $_GET['description'];
    #$in = imagecreatefromjpeg('img.jpg');
    $opacity = 127;        //    starting transparency (0-127, 0 being opaque)
    $height = $_GET['height'];
    $width = $_GET['width'];
    //$height = imagesy($in);
    //$width = imagesx($in);
    
    // create new image to use for output. fill with transparency. ALPHA BLENDING MUST BE FALSE
    $out = imagecreatetruecolor($width, $height);
    imagealphablending($out, false);
#    $bg = imagecolortransparent($out, imagecolorallocatealpha($out, 255, 255, 255, 127));
#    imagefill($out, 0, 0, $bg);
#    imagefilledrectangle($out, 0, 0, $width, $height, $bg);
    
    // copy original image onto new one, leaving space underneath for reflection and 'gap'
    imagecopyresampled ( $out , $in , 0, 0, 0, 0, $width, $height, $width, $height);

     // create new single-line image to act as buffer while applying transparency
    $opacity_slice = imagecreatetruecolor($width, 1);
    imagealphablending($opacity_slice, false);
#    $bg1 = imagecolortransparent($opacity_slice, imagecolorallocatealpha($opacity_slice, 255, 255, 255, 127));
#    imagefill($opacity_slice, 0, 0, $bg1);

    // 1. copy each line individually, starting at the 'bottom' of the image, working upwards. 
    // 2. set transparency to vary between opacity and 127
    // 3. copy line back to mirrored position in original
    //for ($y = 0; $y<$reflection_height;$y++)
    //{    
        //$t = ((127-$opacity) + ($opacity*($y/$reflection_height)));
        //imagecopy($opacity_slice, $out, 0, 0, 0, imagesy($in)  - $y, imagesx($in), 1);
        //imagefilter($opacity_slice, IMG_FILTER_COLORIZE, 0, 0, 0, $t);
        //imagecopyresized($out, $opacity_slice, $a, imagesy($in) + $y + $gap, 0, 0, imagesx($in) - (2*$a), 1, imagesx($in), 1);
    //}
     // create new single-line image to act as buffer while applying transparency
    $opacity_slice = imagecreatetruecolor(1, $height);
    imagealphablending($opacity_slice, false);
#    $bg1 = imagecolortransparent($opacity_slice, imagecolorallocatealpha($opacity_slice, 255, 255, 255, 127));
#    imagefill($opacity_slice, 0, 0, $bg1);
    for ($x = 0; $x <= $width; $x++)
    {    
        $t = (((127-$opacity) - 35) + ($opacity * ($x / $width)));
        imagecopy($opacity_slice, $out, 0, 0, $width - $x, 0, 1, $height);
        imagefilter($opacity_slice, IMG_FILTER_COLORIZE, 0, 0, 0, $t);
        imagecopyresampled($out, $opacity_slice, $width - $x, 0, 0, 0, 1, $height, 1, $height);
    }
    
    // output image to view
    header('Content-type: image/png');
    imagesavealpha($out,true);
    ob_start();
    imagepng($out);
    $image = ob_get_clean();
    echo($image);
?>
