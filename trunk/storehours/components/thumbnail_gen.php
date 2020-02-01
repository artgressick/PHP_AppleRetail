<?php 
/*
	Function createtn($file $dest)
		Creates a resized image.  This only works with png's or jpg's.
		$file		Original filename
		$dest		Height of resized image
*/	

function createtn($file, $dest) {
	$system = explode(".",$file);
	
	// If the file type is jpg or png, create a temporary image to work from
	if(preg_match("/(jpg|jpeg)$/",$file)) { $src_img = imagecreatefromjpeg($dest.$file); }
	if(preg_match("/png$/",$file)) { $src_img = imagecreatefrompng($dest.$file); }
	
	// Getting the old image sizes
	$oldW = imageSX($src_img);
	$oldH = imageSY($src_img);

	if($oldW > $oldH) {
		$mult = (150 / $oldW);
	} else {
		$mult = (150 / $oldH);
	}
	$w = $mult * $oldW;
	$h = $mult * $oldH;

	// Create true color version of the temp image.
	$dst_img = ImageCreateTrueColor($w,$h);
	
	// Resample and recreate the new image
	imagecopyresampled($dst_img, $src_img, 0, 0, 0, 0, $w, $h, $oldW, $oldH); 
	
	// Check the file type, and create a new image with the '_tn' on the end
	//    representing a "ThumbNail" version of the image.
	if (preg_match("/png/",$system[1]))	{
		$nn = $system[0]."_tn.png";
		imagepng($dst_img,$dest.$nn); 
	} else {
		$nn = $system[0]."_tn.jpg";
		imagejpeg($dst_img,$dest.$nn); 
	}

	if($oldW > $oldH) {
		$mult = (500 / $oldW);
	} else {
		$mult = (500 / $oldH);
	}
	$w = $mult * $oldW;
	$h = $mult * $oldH;

	// Create true color version of the temp image.
	$dst_img = ImageCreateTrueColor($w,$h);
	
	// Resample and recreate the new image
	imagecopyresampled($dst_img, $src_img, 0, 0, 0, 0, $w, $h, $oldW, $oldH); 
	
	// Check the file type, and create a new image with the '_tn' on the end
	//    representing a "ThumbNail" version of the image.
	if (preg_match("/png/",$system[1]))	{
		$nn = $system[0]."_medium.png";
		imagepng($dst_img,$dest.$nn); 
	} else {
		$nn = $system[0]."_medium.jpg";
		imagejpeg($dst_img,$dest.$nn); 
	}

}
?>
