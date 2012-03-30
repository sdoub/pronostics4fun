<?php
require_once('begin.file.php');

#########################################################################################################
# CONSTANTS																								#
# You can alter the options below																		#
#########################################################################################################
/*$upload_dir = "upload_pic"; 				// The directory for the images to be saved in
 $upload_path = $upload_dir."/";				// The path to where the image will be saved
 $large_image_prefix = "resize_"; 			// The prefix name to large image
 $thumb_image_prefix = "thumbnail_";			// The prefix name to the thumb image
 //$large_image_name = $large_image_prefix.$_SESSION['random_key'];     // New name of the large image (append the timestamp to the filename)
 //$thumb_image_name = $thumb_image_prefix.$_SESSION['random_key'];     // New name of the thumbnail image (append the timestamp to the filename)
 $max_file = "3"; 							// Maximum file size in MB
 $max_width = "500";							// Max width allowed for the large image
 $thumb_width = "82";						// Width of thumbnail image
 $thumb_height = "82";						// Height of thumbnail image
 // Only one of these image types should be allowed for upload
 $allowed_image_types = array('image/pjpeg'=>"jpg",'image/jpeg'=>"jpg",'image/jpg'=>"jpg",'image/png'=>"png",'image/x-png'=>"png",'image/gif'=>"gif");
 $allowed_image_ext = array_unique($allowed_image_types); // do not change this
 $image_ext = "";	// initialise variable, do not change this.
 foreach ($allowed_image_ext as $mime_type => $ext) {
 $image_ext.= strtoupper($ext)." ";
 }
 */

##########################################################################################################
# IMAGE FUNCTIONS																						 #
# You do not need to alter these functions																 #
##########################################################################################################
/*function resizeImage($image,$width,$height,$scale) {
 list($imagewidth, $imageheight, $imageType) = getimagesize($image);
 $imageType = image_type_to_mime_type($imageType);
 $newImageWidth = ceil($width * $scale);
 $newImageHeight = ceil($height * $scale);
 $newImage = imagecreatetruecolor($newImageWidth,$newImageHeight);
 switch($imageType) {
 case "image/gif":
 $source=imagecreatefromgif($image);
 break;
 case "image/pjpeg":
 case "image/jpeg":
 case "image/jpg":
 $source=imagecreatefromjpeg($image);
 break;
 case "image/png":
 case "image/x-png":
 $source=imagecreatefrompng($image);
 break;
 }
 imagecopyresampled($newImage,$source,0,0,0,0,$newImageWidth,$newImageHeight,$width,$height);

 switch($imageType) {
 case "image/gif":
 imagegif($newImage,$image);
 break;
 case "image/pjpeg":
 case "image/jpeg":
 case "image/jpg":
 imagejpeg($newImage,$image,90);
 break;
 case "image/png":
 case "image/x-png":
 imagepng($newImage,$image);
 break;
 }

 chmod($image, 0777);
 return $image;
 }
 //You do not need to alter these functions
 function resizeThumbnailImage($thumb_image_name, $image, $width, $height, $start_width, $start_height, $scale){
 list($imagewidth, $imageheight, $imageType) = getimagesize($image);
 $imageType = image_type_to_mime_type($imageType);

 $newImageWidth = ceil($width * $scale);
 $newImageHeight = ceil($height * $scale);
 $newImage = imagecreatetruecolor(82,82);
 switch($imageType) {
 case "image/gif":
 $source=imagecreatefromgif($image);
 break;
 case "image/pjpeg":
 case "image/jpeg":
 case "image/jpg":
 $source=imagecreatefromjpeg($image);
 break;
 case "image/png":
 case "image/x-png":
 $source=imagecreatefrompng($image);
 break;
 }
 imagecopyresampled($newImage,$source,0,0,$start_width,$start_height,$newImageWidth,$newImageHeight,$width,$height);
 switch($imageType) {
 case "image/gif":
 imagegif($newImage,$thumb_image_name);
 break;
 case "image/pjpeg":
 case "image/jpeg":
 case "image/jpg":
 imagejpeg($newImage,$thumb_image_name,90);
 break;
 case "image/png":
 case "image/x-png":
 imagepng($newImage,$thumb_image_name);
 break;
 }
 chmod($thumb_image_name, 0777);
 return $thumb_image_name;
 }
 //You do not need to alter these functions
 function getHeight($image) {
 $size = getimagesize($image);
 $height = $size[1];
 return $height;
 }
 //You do not need to alter these functions
 function getWidth($image) {
 $size = getimagesize($image);
 $width = $size[0];
 return $width;
 }
 */


require_once('lib/phpthumb/phpthumb.class.php');

// create phpThumb object
$phpThumb = new phpThumb();

//Get the new coordinates to crop the image.
$x1 = $_POST["x1"];
$y1 = $_POST["y1"];
$w = $_POST["w"];
$h = $_POST["h"];
$fileExt = $_POST["fileExt"];
//Scale the image to the thumb_width set above
//$scale = $thumb_width/$w;

// this is very important when using a single object to process multiple images
$phpThumb->resetObject();

$destinationFileName = $_authorisation->getConnectedUserKey()."_".time().".".$fileExt;
// set data source -- do this first, any settings must be made AFTER this call
$phpThumb->setSourceFilename('images/avatars/'.$_authorisation->getConnectedUserKey()."original.".$fileExt);  // for static demo only

//$phpThumb->setParameter('config_document_root', '/www');
//$phpThumb->setParameter('config_cache_directory', '/www/cache/');

// set parameters (see "URL Parameters" in phpthumb.readme.txt)
$phpThumb->setParameter('w', 82);
$phpThumb->setParameter('w', 82);
$phpThumb->setParameter('sx', $x1);
$phpThumb->setParameter('sy', $y1);
$phpThumb->setParameter('sw', $w);
$phpThumb->setParameter('sh', $h);

// set options (see phpThumb.config.php)
// here you must preface each option with "config_"
$phpThumb->setParameter('config_output_format', $fileExt);
$arr = array();
// generate & output thumbnail
$output_filename = 'images/avatars/'.$destinationFileName;
if ($phpThumb->GenerateThumbnail()) { // this line is VERY important, do not remove it!
  //$output_size_x = ImageSX($phpThumb->gdimg_output);
  //$output_size_y = ImageSY($phpThumb->gdimg_output);
  $arr["generated"]= true;


  if ($output_filename || $capture_raw_data) {
    $cropped= $phpThumb->RenderToFile($output_filename);
    $arr["rendered"]= true;
  }

} else {
  $arr["generated"]= false;

  $cropped = false;
  $arr["error"] = $phpThumb->fatalerror;
}
//$cropped = resizeThumbnailImage('images/avatars/'.$_authorisation->getConnectedUserKey().'.'.$fileExt, 'images/avatars/'.$_authorisation->getConnectedUserKey()."original.".$fileExt,$w,$h,$x1,$y1,$scale);
//Reload the page again to view the thumbnail

if ($cropped) {
  $arr["success"]=true;
  $arr["filePath"]= ROOT_SITE . '/images/avatars/'.$destinationFileName;
  $arr["fileName"]= $destinationFileName;
}
else {
  $arr["filePath"]= ROOT_SITE . '/images/avatars/'.$destinationFileName;
  $arr["fileName"]= $destinationFileName;
  $arr["success"]=false;
}

writeJsonResponse($arr);

require_once('end.file.php');
?>