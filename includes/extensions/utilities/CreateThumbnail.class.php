<?php
class CreateThumbNail extends CCTemplate
{
	// Creating the thumbnail based on the aspect ratio of the image
	public function createthumb($name, $filename, $new_w, $new_h) {
		$system = explode(".", $name);
		$found = 0;
		if (preg_match("/jpg|jpeg/", $system[1])) {
			$src_img = @imagecreatefromjpeg($name);
			$found = 1;
		}
		if (preg_match("/gif/", $system[1])) {
			$src_img = @imagecreatefromgif($name);
			$found = 1;
		}
		if (preg_match("/png/", $system[1])) {
			$src_img = @imagecreatefrompng($name);
			$found = 1;
		}
		if ($found == 1) {
			$old_x = @imageSX($src_img);
			$old_y = @imageSY($src_img);
			if ($old_x > $old_y) {
				$thumb_w = $new_w;
				$thumb_h = $old_y * ($new_h / $old_x);
			}
			if ($old_x < $old_y) {
				$thumb_w = $old_x * ($new_w / $old_y);
				$thumb_h = $new_h;
			}
			if ($old_x == $old_y) {
				$thumb_w = $new_w;
				$thumb_h = $new_h;
			}
			$dst_img = imagecreatetruecolor($thumb_w,$thumb_h);
			@imagecopyresampled($dst_img, $src_img, 0, 0, 0, 0, $thumb_w, $thumb_h, $old_x, $old_y); 
			if (preg_match("/png/", $system[1])) {
				imagepng($dst_img, $filename);
			} elseif (preg_match("/gif/", $system[1])) {
				imagegif($dst_img, $filename);
			} else {
				imagejpeg($dst_img, $filename); 
			}
			@imagedestroy($dst_img);
			@imagedestroy($src_img);
		}
	}
	// Find the image from a full URL
	public function GetImageFromUrl($link) {
		if (file_exists($link)) {
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_POST, 0);
			curl_setopt($ch, CURLOPT_URL, $link);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			$result = curl_exec($ch);
			curl_close($ch);
			return $result;
		} else {
			return NULL;	
		}
	}
	// Load the thumbnail
	public function ThumbLoad($img, $root, $save_loc, $save_name, $save_thumb_loc, $save_thumb_name, $width, $height, $class, $alt) {
		$theimage = $this->GetImageFromUrl($img);
		$savefile = fopen($root . $save_loc . $save_name, 'w');
		fwrite($savefile, $theimage);
		fclose($savefile);
		$this->createthumb($root . $save_loc . $save_name, $root . $save_thumb_loc . $save_thumb_name, $width, $height, $class, $alt);
		return '<img src="' . SITE_PATH . 'images/' . $save_thumb_loc . $save_thumb_name . '" alt="' . $alt . '" class="' . $class . '" />';
	}
}
/*
Basic Example Useage:
// Configuration
$img = 'http://www.hc-sc.gc.ca/cps-spc/images/hecs-sesc/pubs/cons/info_secondhand-produits18.jpg'; // File
$image_name = 'imagename'; // Save draft name as...
$image_thumb_name = 'tn_imagename'; // Save as...
$image_alt = 'Title';
// DO NOT MODIFY BELOW
$save_name = $image_name . '-' . $sb_lang . $ext;
$save_thumb_name = $image_thumb_name . '-' . $sb_lang . $ext;
$find_ext = explode(".", $img);
$ext = '.' . $find_ext[count($find_ext) - 1];
$alt = htmlentities($image_alt, ENT_QUOTES, "iso-8859-1");
$a = new CreateThumbNail();
$thumbnail = $a->ThumbLoad($img, THUMBNAIL_ROOT, SAVE_TEMP_THUMB, $save_name,SAVE_THUMB, $save_thumb_name, THUMB_WIDTH, THUMB_HEIGHT, THUMB_CLASS, $alt);
// The Return
echo $thumbnail; 
*/
?>