<?php

require_once 'class.utilities.php';

class Images{
	public static function str2img($strImage) {
		list($type, $data) = explode(';', $strImage);
		list(, $data)      = explode(',', $data);

		$rand1 = Utility::generateRandomString('alphanumaric',10);
		$rand2 = Utility::generateRandomString('alphanumaric',10);
		$newName = $rand1 . "-" . $rand2;
		$extension = "";

		if ($type=="data:image/png") {
			$extension = ".png";
			$location   = "uploads/" . $newName . $extension;
			$img       = Images::convertStrToPNG($data,$location);
			return $location;
		} else if ($type=="data:image/jpg") {
			$extension = ".jpg";
			$location   = "uploads/" . $newName . $extension;
			$img       = Images::convertStrToJPG($data,$location);
			return $location;
		} else if ($type=="data:image/jpeg") {
			$extension = ".jpg";
			$location   = "uploads/" . $newName . $extension;
			$img       = Images::convertStrToJPEG($data,$location);
			return $location;
		} else if ($type=="data:image/gif") {
			$extension = ".gif";
			$location   = "uploads/" . $newName . $extension;
			$img       = Images::convertStrToGIF($data,$location);
			return $location;
		}		
	}

	public static function convertStrToJPG($img,$location) {
		$imageData = base64_decode($img);
		$source = imagecreatefromstring($imageData);
		$angle = 0;
		$rotate = imagerotate($source, $angle, 0); // if want to rotate the image
		$imageSave = imagejpeg($rotate,$location,100);
		imagedestroy($source);
		if(!empty($imageSave))
			return true;
		return false;
	}

	public static function convertStrToJPEG($img,$location) {
		$imageData = base64_decode($img);
		$source = imagecreatefromstring($imageData);
		$angle = 0;
		$rotate = imagerotate($source, $angle, 0); // if want to rotate the image
		$imageSave = imagejpeg($rotate,$location,100);
		imagedestroy($source);
		if(!empty($imageSave))
			return true;
		return false;
	}

	public static function convertStrToPNG($img,$location) {
		$imageData = base64_decode($img);
		$img = file_put_contents($location, $imageData);
		if(!empty($img))
			return true;
		return false;
	}

	public static function convertStrToGIF($img,$location) {
		$imageData = base64_decode($img);
		$source = imagecreatefromstring($imageData);
		$imageSave = imagegif($source,$location);
		imagedestroy($source);
		return true;
	}

	public static function png2jpg($originalFile, $outputFile, $quality) {
	    $image = imagecreatefrompng($originalFile);
	    imagejpeg($image, $outputFile, $quality);
	    imagedestroy($image);
	}

	public static function jpg2png($originalFile, $outputFile) {
		imagepng(imagecreatefromstring(file_get_contents($originalFile)), $outputFile);
	}

	public static function gif2png($originalFile, $outputFile) {
		imagepng(imagecreatefromstring(file_get_contents($originalFile)), $outputFile);
	}

	public static function png2gif($originalFile, $outputFile, $quality) {
		imagegif(imagecreatefrompng(file_get_contents($originalFile)), $outputFile);
	}

	public static function str2png($strImage) {
		$imageData = $strImage;
		$imageData = explode(',', $imageData);
		$imageData = $imageData[1];

		$imageData = base64_decode($imageData);

		$rand1 = Utility::generateRandomString('alphanumaric',10);
		$rand2 = Utility::generateRandomString('alphanumaric',10);
		$imageName = $rand1 . "-" . $rand2 . ".png";
		$newImage  = "final/" . $imageName;

		file_put_contents($newImage, $imageData);

		return $imageName;
	}

	public static function str2jpg($strImage) {
		$imageData = $strImage;
		$imageData = explode(',', $imageData);
		$imageData = $imageData[1];

		$imageData = base64_decode($imageData);
		$source    = imagecreatefromstring($imageData);

		$angle     = 0;
		$rotate    = imagerotate($source, $angle, 0); // if want to rotate the image

		$rand1 = Utility::generateRandomString('alphanumaric',10);
		$rand2 = Utility::generateRandomString('alphanumaric',10);
		$imageName = $rand1 . "-" . $rand2 . ".jpg";
		$newImage  = "uploads/" . $imageName;

		file_put_contents($newImage, $imageData);
		$imageSave = imagejpeg($rotate,$newImage,100);
		imagedestroy($source);

		return $newImage;
	}
}


