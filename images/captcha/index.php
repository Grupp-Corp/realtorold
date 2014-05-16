<?php
// Include the random string file
require 'rand.php';
// Begin a new session
session_start();
// Set the session contents
$_SESSION['captcha_id'] = $str;
// Set the content type
//header('Content-type: image/png');
header('Cache-control: no-cache');
// Create an image from button.png
$image = imagecreatefrompng('button.png');
// Set the font colour
$colour = imagecolorallocate($image, 183, 178, 152);
// Set the font
$font = '../../fonts/Anorexia.ttf';
// Set a random integer for the rotation between -15 and 15 degrees
$rotate = rand(-15, 15);
// Create an image using our original image and adding the detail
imagettftext($image, 14, $rotate, 18, 30, $colour, $font, $str);
// Output the image as a png
imagepng($image);
?>