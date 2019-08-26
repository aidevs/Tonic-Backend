<?php
/**
 * tp_watermark.class.php
 * Thumbnail Party Watermark class
 *
 * @author Mickey9801 <mickey9801@gmail.com>
 * @copyright copyright ComicParty.com 2006
 * @version 1.0.2
 * @package thumbnail_party
 * 
 * DESCRIPTION :
 * The class is a supplementary class to calculate watermark geometry
 * on the Thumbnail. Only png file is acceptable for watermark
 *
 * Mickey9801 2007-08-03 12:56 HKT
 *       Fix bug : set_position checking error
 * Mickey9801 2007-05-24 21:25 HKT
 *       Change error reporting method
 * Mickey9801 2006-06-01 03:38 HKT
 *       First edition launched
 *
 */
define('WATERMARK_POSITION_TOP_LEFT', 10);
define('WATERMARK_POSITION_TOP_MIDDLE', 11);
define('WATERMARK_POSITION_TOP_RIGHT', 12);
define('WATERMARK_POSITION_CENTER_LEFT', 20);
define('WATERMARK_POSITION_CENTER_MIDDLE', 21);
define('WATERMARK_POSITION_CENTER_RIGHT', 22);
define('WATERMARK_POSITION_BOTTOM_LEFT', 30);
define('WATERMARK_POSITION_BOTTOM_MIDDLE', 31);
define('WATERMARK_POSITION_BOTTOM_RIGHT', 32);
define('WATERMARK_POSITION_RANDOM', 99);

class tp_watermark {
	var $strWatermarkPath = NULL;
	var $intX = 0;
	var $intY = 0;
	var $intWatermarkWidth;
	var $intWatermarkHeight;
	var $intChoppedWidth;
	var $intChoppedHeight;
	var $intChopAreaWidth = 0;
	var $intChopAreaHeight = 0;
	var $intOpacity = 50;
	var $intWatermarkPosition = WATERMARK_POSITION_RANDOM;
	var $boolError = FALSE;
	
	/**
	 * Constructor
	 *
	 * @param string $strWatermarkPath path and filename to watermark image
	 * @return void
	 * @access public
	 */
	function tp_watermark ($strWatermarkPath) {
		$this->set_watermark($strWatermarkPath);
	}
	
	/**
	 * Set watermark file path
	 * Set to NULL for shutdown watermark feature
	 *
	 * @param string $strWatermarkPath path and filename to watermark image
	 * @return boolean true if watermark file found
	 * @access public
	 */
	function set_watermark ($strWatermarkPath=NULL) {
		if ($strWatermarkPath == NULL) {
			$this->strWatermarkPath = NULL;
			return TRUE;
		}
		if (file_exists($strWatermarkPath)) {
			$arrWatermarkInfo = getimagesize($strWatermarkPath);
			if (strpos($arrWatermarkInfo['mime'], 'png')!==FALSE) {
				$this->strWatermarkPath = $strWatermarkPath;
				$this->intWatermarkWidth = $arrWatermarkInfo[0];
				$this->intWatermarkHeight = $arrWatermarkInfo[1];
				$this->intChoppedWidth = $this->intWatermarkWidth;
				$this->intChoppedHeight = $this->intWatermarkHeight;
				return TRUE;
			} else {
				$this->boolError = TRUE;
				return FALSE;
			}
		} else {
			$this->boolError = TRUE;
			return FALSE;
		}
	}
	
	/**
	 * Set watermark position
	 *
	 * @param integer $intWatermarkPosition position of watermark image will be placed in
	 * @return boolean true if watermark position correct
	 * @access public
	 */
	function set_position ($intWatermarkPosition=WATERMARK_POSITION_RANDOM) {
		if (
			$intWatermarkPosition == WATERMARK_POSITION_RANDOM ||
			(
				floor($intWatermarkPosition/10) <= 3 &&
				($intWatermarkPosition%3) <= 2
			)
		) {
			$this->intWatermarkPosition = (int)$intWatermarkPosition;
			return TRUE;
		} else {
			$this->boolError = TRUE;
			return FALSE;
		}
	}
	
	/**
	 * Set watermark opacity
	 *
	 * @param integer $intOpacity opacity of watermark image will be shown
	 * @return boolean true
	 * @access public
	 */
	function set_opacity ($intOpacity=50) {
		$this->intOpacity = abs(intval($intOpacity));
		return TRUE;
	}
	
	/**
	 * Get the watermark position and size
	 *
	 * @param array $arrOutputGeometry geometry of output thumbnail
	 * @return array geometry of watermark in the output image
	 * @access protected
	 */
	function get_geometry (&$arrOutputGeometry) {
		// random watermark position
		if ($this->intWatermarkPosition == WATERMARK_POSITION_RANDOM) {
			$this->intWatermarkPosition = (mt_rand(1,3) * 10) + mt_rand(0,2);
		}
		
		switch ($this->intWatermarkPosition) {
			case WATERMARK_POSITION_TOP_LEFT :
				$this->intX = $arrOutputGeometry['intOutputX'];
				$this->intY = $arrOutputGeometry['intOutputY'];
				break;
			case WATERMARK_POSITION_TOP_MIDDLE :
				$this->intX = ($arrOutputGeometry['intCanvasWidth'] - $this->intWatermarkWidth) / 2;
				$this->intY = $arrOutputGeometry['intOutputY'];
				break;
			case WATERMARK_POSITION_TOP_RIGHT :
				$this->intX = $arrOutputGeometry['intOutputX'] + $arrOutputGeometry['dblOutputWidth'] - $this->intWatermarkWidth;
				$this->intY = $arrOutputGeometry['intOutputY'];
				break;
			case WATERMARK_POSITION_CENTER_LEFT :
				$this->intX = $arrOutputGeometry['intOutputX'];
				$this->intY = ($arrOutputGeometry['intCanvasHeight'] - $this->intWatermarkHeight) / 2;
				break;
			case WATERMARK_POSITION_CENTER_MIDDLE :
				$this->intX = ($arrOutputGeometry['intCanvasWidth'] - $this->intWatermarkWidth) / 2;
				$this->intY = ($arrOutputGeometry['intCanvasHeight'] - $this->intWatermarkHeight) / 2;
				break;
			case WATERMARK_POSITION_CENTER_RIGHT :
				$this->intX = $arrOutputGeometry['intOutputX'] + $arrOutputGeometry['dblOutputWidth'] - $this->intWatermarkWidth;
				$this->intY = ($arrOutputGeometry['intCanvasHeight'] - $this->intWatermarkHeight) / 2;
				break;
			case WATERMARK_POSITION_BOTTOM_LEFT :
				$this->intX = $arrOutputGeometry['intOutputX'];
				$this->intY = $arrOutputGeometry['intOutputY'] + $arrOutputGeometry['dblOutputHeight'] - $this->intWatermarkHeight;
				break;
			case WATERMARK_POSITION_BOTTOM_MIDDLE :
				$this->intX = ($arrOutputGeometry['intCanvasWidth'] - $this->intWatermarkWidth) / 2;
				$this->intY = $arrOutputGeometry['intOutputY'] + $arrOutputGeometry['dblOutputHeight'] - $this->intWatermarkHeight;
				break;
			case WATERMARK_POSITION_BOTTOM_RIGHT :
				$this->intX = $arrOutputGeometry['intOutputX'] + $arrOutputGeometry['dblOutputWidth'] - $this->intWatermarkWidth;
				$this->intY = $arrOutputGeometry['intOutputY'] + $arrOutputGeometry['dblOutputHeight'] - $this->intWatermarkHeight;
				break;
		}
		
		// chop watermark
		$this->intChopAreaWidth = 0;
		$this->intChopAreaHeight = 0;
		if ($arrOutputGeometry['dblOutputWidth'] < $this->intWatermarkWidth) {
			$this->intChopAreaWidth = $this->intWatermarkWidth - $arrOutputGeometry['dblOutputWidth'];
			$this->intX = $arrOutputGeometry['intOutputX'];
		}
		if ($arrOutputGeometry['dblOutputHeight'] < $this->intWatermarkHeight) {
			$this->intChopAreaHeight = $this->intWatermarkHeight - $arrOutputGeometry['dblOutputHeight'];
			$this->intY = $arrOutputGeometry['intOutputY'];
		}
		$this->intChoppedWidth = $this->intWatermarkWidth - $this->intChopAreaWidth;
		$this->intChoppedHeight = $this->intWatermarkHeight - $this->intChopAreaHeight;
		
		$this->intX = round($this->intX);
		$this->intY = round($this->intY);
		if ($this->intX < 0) $this->intX = 0;
		if ($this->intY < 0) $this->intY = 0;
		if ($this->intX >= 0) $this->intX = '+'.$this->intX;
		if ($this->intY >= 0) $this->intY = '+'.$this->intY;
		return TRUE;
	}
}
?>