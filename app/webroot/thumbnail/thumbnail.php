<?php
/**
 * thumbnail.php
 * display resized thumbnail
 *
 * @author Mickey9801 <mickey9801@gmail.com>
 * @copyright copyright ComicParty.com 2006
 * @version 1.1.1
 * @package thumbnail_part
 * 
 * USAGE :
 * <img src="thumbnail.php?file=120x215_jpg&w=200&h=300&color=FF0000&el=0&gd=0" />
 *
 * PARAMETERS :
 * file        str    urlencoded path to source image. Submit only a
 *                    file name when used with mod_rewrite
 * w           int    output image width
 * h           int    output image height
 * color       str    background color of output image. You can
 *                    provide the value in '#RRGGBB' or 'RRGGBB'
 *                    format
 * gd          bool   submit TRUE(1) to use GDlib, otherwise use
 *                    ImageMagick in default path
 * el          bool   submit TRUE(1) to enlarge source image when
 *                    output size is larger then source size
 * 
 * HISTORY :
 * Mickey9801 2007-06-04 19:45 HKT
 *       Added cache lifetime setting
 * Mickey9801 2006-05-27 17:25 HKT
 *       Added watermark for example
 * Mickey9801 2006-05-22 03:38 HKT
 *       First edition launched
 * Mickey9801 2007-05-24 21:36 HKT
 *       Modified error tracking on thumbnailParty construct
 *
 */
//ini_set('display_errors', '1');
//error_reporting(E_ALL);

require('thumbnail_party.class.php');
$imagemagick_path = '/usr/bin/'; // Path to ImageMagick. Change this if it is different

$strFile        = (isset($_GET['file']))  ? $_GET['file']     : NULL;
$intWidth       = (isset($_GET['w']))     ? (int)$_GET['w']   : 100;
$intHeight      = (isset($_GET['h']))     ? (int)$_GET['h']   : 100;
$strColor       = (isset($_GET['color'])) ? $_GET['color']    : NULL;
$boolUseGD      = (isset($_GET['gd']))    ? (bool)$_GET['gd'] : FALSE;
$boolEnlarge    = (isset($_GET['el']))    ? (bool)$_GET['el'] : FALSE;
$boolWatermark  = (isset($_GET['wm']))    ? (bool)$_GET['wm'] : FALSE;
$crop  			= (!empty($_GET['crop']))  ? (int)$_GET['crop'] : '';
$strBgTransparent=(!empty($_GET['tp']))  ? (int)$_GET['tp'] : '';

if ($intWidth == 0) $intWidth = 100;
if ($intHeight == 0) $intHeight = 100;
$pathExtenxion = pathinfo($strFile,PATHINFO_EXTENSION);
// Build thumbnail_party object
$thumb =new thumbnail_party($intWidth, $intHeight, $strColor, $pathExtenxion);
if ($thumb->isError()) {
	err_img_builder($thumb->get_error_msg());
}
// Switch between GD and ImageMagick
if ($boolUseGD) {
	if (!$thumb->set_imagemagick_path(NULL)) {
		err_img_builder($thumb->get_error_msg(),$intWidth, $intHeight);
	}
} else {
	if (!$thumb->set_imagemagick_path($imagemagick_path)) {
		err_img_builder($thumb->get_error_msg(),$intWidth, $intHeight);
	}
}
// Assign picture quality for JPEG image
$thumb->intJPEGQuality = 100;

// Set cache lifetime
$thumb->intCacheLifetime = 3600;
//Set crop
$thumb->crop = $crop;
//Set transparnt
$thumb->strBgTransparent = $strBgTransparent;
// Set watermark at random position
if ($boolWatermark) {
	if (!$thumb->set_watermark('../images/watermark.png', WATERMARK_POSITION_RANDOM, 50)) {
		err_img_builder($thumb->get_error_msg(),$intWidth, $intHeight);
	}
}

// Select enlarge mode for source image that smaller then output size
if (!$boolEnlarge) $thumb->set_enlarge_mode(THUMBNAIL_ENLARGE_NONE);
else $thumb->set_enlarge_mode(THUMBNAIL_ENLARGE_ALWAYS);

if (!isset($strFile)) err_img_builder('Invalid Source Image');

// This is for user who using mod_rewrite to simplify the query string
// It is not possible to submit a path when using mod_rewrite to convert a path
// if you are not using mod_rewrite,
// you may consider submit the full urlencoded source path
$strFile =str_replace(' ','%20',$strFile);

if (!$thumb->set_source_file($strFile)) err_img_builder($thumb->get_error_msg());

if (!$thumb->output()) err_img_builder($thumb->get_error_msg(),$intWidth, $intHeight);

exit();

//========================= Functions
/**
 * generate a image to show thumbnail_party error message
 *
 * @param string $strErrorMsg Error message will be shown in image
 * @return void
 */
function err_img_builder ($strErrorMsg,$intWidth=150, $intHeight=300) {
	$arrMsg = explode("\n", wordwrap($strErrorMsg,15,"\n",TRUE));
	$resWarningImg = imagecreate($intWidth, $intHeight);
	$intBgColor = imagecolorallocate($resWarningImg, 136, 136, 255);
	$intTextColor = imagecolorallocate($resWarningImg, 255, 0, 0);
	for ($i=0; $i < count($arrMsg); $i++) {
		imagestring($resWarningImg, 2, 0, $i*12, $arrMsg[$i], $intTextColor);
	}

	header("Content-type: image/png");
	imagepng($resWarningImg);
	imagedestroy($resWarningImg);
}
?>