<?php

defined('_JEXEC') or die;

jimport('joomla.filesystem.folder');
jimport('joomla.filesystem.file');
jimport('joomla.filesystem.path');
jimport('joomla.image');

class CTHImageOptimizerHelper
{
	protected $cache_folder;
	protected $imagick_process;
	static $instance = null;
	
	public function __construct($params = array())
	{
		$this->cache_folder = 'images/CTHimageOptimizer_Images';
		
		// Make cache folder if not exists (used by resize function)
		if (!file_exists($this->cache_folder))
		{
			JFolder::create($this->cache_folder, 0777);
		}
		if (!file_exists($this->cache_folder.'/remote'))
		{
			//mkdir($this->cache_folder.'/remote');
			JFolder::create($this->cache_folder.'/remote', 0777);
		}
		$this->imagick_process = 'jimage';
	}

	public static function getInstance($params = array()){
		if(is_null(self::$instance)){
			self::$instance = new self;
		}
		return self::$instance;
	}
	
	/**
	* $imagePath : string - relative path to image file
	* $opts : array w,h,crop,risize ...
	**/
	public function resize($imagePath, $opts)
	{


		if (!$opts)
			return $imagePath;
		$imagePath = (string) $imagePath;
		
		if (!$this->_checkImage($imagePath))
			return $imagePath;
		return $this->_resize($imagePath, $opts);
	}
	
	/**
	 * Avoid errors if image corrupted
	 * @param string $image_path
	 * @return boolean
	 */
	protected function _checkImage($imagePath)
	{
		try
		{
			@$size = getimagesize($imagePath);

			if (!$size){
				$imgPath = JPath::clean(str_replace("devgenesis","",$imagePath));
				
				if(!@getimagesize(JPATH_ROOT.'/'.$imgPath)){
					die('loi roi');
					return FALSE;
				}
			}
			return TRUE;
		} catch (Exception $e) {
			return FALSE;
		}
	
	}
	
	protected function _resize($imagePath,$opts=null){
		$imagePath = urldecode($imagePath);

		# start configuration
		$cacheFolder = $this->cache_folder.'/'; # path to your cache folder, must be writeable by web server	// s2s: use $this->cache_folder
		//$remoteFolder = $cacheFolder.'remote/'; # path to the folder you wish to download remote images into
		$defaults = array('crop' => false, 'scale' => false, 'thumbnail' => false, 'maxOnly' => false,
				'canvas-color' => 'transparent', 'output-filename' => false,
				'quality' => 100, 'cache_http_minutes' => 20);

		$opts = array_merge($defaults, $opts);

		$purl = parse_url($imagePath);
		$finfo = pathinfo($imagePath);
		$ext = $finfo['extension'];
		//echo '<pre>';var_dump($purl);var_dump($finfo);die;
		//var_dump($finfo);die;
		# check for remote image..
		
		if(isset($purl['scheme']) && ($purl['scheme'] == 'http' || $purl['scheme'] == 'https')):
			# grab the image, and cache it so we have something to work with..
			list($filename) = explode('?',$finfo['basename']);
			$local_filepath = $cacheFolder.'remote/'.$filename;
			$download_image = true;
			if(file_exists($_SERVER['DOCUMENT_ROOT'] . $local_filepath)):
				if(filemtime($local_filepath) < strtotime('+'.$opts['cache_http_minutes'].' minutes')):
					$download_image = false;
				endif;
			endif;
			if($download_image == true):
				$img = file_get_contents($imagePath);
				file_put_contents($local_filepath,$img);
			endif;
			$imagePath = $local_filepath;
		endif;
		//echo $ext.' '.$imagePath;die;
		$absoluteImagePath = JPath::clean(JPATH_ROOT.'/'.$imagePath);
	
		//echo $absoluteImagePath;die;

		
	
		/*s2s: maxOnly FIX - start*/
		if ($opts['maxOnly']) {
			$imagesize = getimagesize($absoluteImagePath);
			//echo'<pre>';var_dump($imagesize);die;
			if (isset($opts['w'])) if ($opts['w'] > $imagesize[0]) $opts['w'] = $imagesize[0];
			if (isset($opts['h'])) if ($opts['h'] > $imagesize[1]) $opts['h'] = $imagesize[1];
			$opts['maxOnly'] = false;
		}
		// check if original size is less than option size
		if ($opts['crop'] /*&& $this->imagick_process == 'exec'*/) {	// fix crop: in some cases doesn't work (in exec mode)
			$imagesize = getimagesize($absoluteImagePath);	// 0 => width, 1 => height
			//echo'<pre>';var_dump($imagesize);die;
			if ($imagesize[0] > $imagesize[1] && $imagesize[0]/$imagesize[1] < $opts['w']/$opts['h'] ||
				$imagesize[0] < $imagesize[1] && $imagesize[0]/$imagesize[1] > $opts['w']/$opts['h'])
			{
				$opts['crop'] = true;
				$opts['resize'] = TRUE;
			}
		}
		/*s2s - end*/
		
		
	
		//$path_to_convert = 'convert'; # this could be something like /usr/bin/convert or /opt/local/share/bin/convert 	//s2s ORIGINALE
		//$path_to_convert = $this->imagick_path_to_convert;	// s2s imagick convert path from config
	
		## you shouldn't need to configure anything else beyond this point
	
		list($orig_w,$orig_h) = getimagesize($absoluteImagePath);

		if (isset($opts['w'])) {
            if (stripos($opts['w'], '%') !== false) {
                $w = (int)((float)str_replace('%', '', $opts['w']) / 100 * $orig_w);
            }
            else{
            	$w = (int)$opts['w'];
            }
        }
        if (isset($opts['h'])) {
            if (stripos($opts['h'], '%') !== false) {
                $h = (int)((float)str_replace('%', '', $opts['h']) / 100 * $orig_h);
            }
            else{
            	$h = (int)$opts['h'];
            }
        }

		if(!isset($opts['w']) && !isset($opts['h'])){
			list($w,$h) = array($orig_w,$orig_h);
		}
		//$filename = md5_file($absoluteImagePath);

		//$imageExt = JFile::getExt($absoluteImagePaths);
		$imageName = preg_replace('/\.'.$ext.'$/', '', JFile::getName($absoluteImagePath));
		$imageNameNew = $imageName.'_w'.$w.'xh'.$h;

		if(!empty($w) and !empty($h)):
			$imageNameNew = $imageName.'_w'.$w.'_h'.$h.(isset($opts['crop']) && $opts['crop'] == true ? "_cp" : "").(isset($opts['scale']) && $opts['scale'] == true ? "_sc" : "").'_q'.$opts['quality'];
		elseif(!empty($w)):
			$imageNameNew = $imageName.'_w'.$w.'_q'.$opts['quality'];
		elseif(!empty($h)):
			$imageNameNew = $imageName.'_h'.$h.'_q'.$opts['quality'];
		else:
			return false;
		endif;

		//$absoluteImagePathNew = str_replace($imageName.'.'.$ext, $imageNameNew.'.'.$ext, $absoluteImagePath);//JPath::clean(JPATH_ROOT.'/'.$relativePathNew);
		$absoluteImagePathNew = JPath::clean(JPATH_ROOT.'/'.$cacheFolder.$imageNameNew.'.'.$ext);
		$relativeImagePathNew = preg_replace('/\\\\/', '/',str_replace(JPATH_ROOT, "", $absoluteImagePathNew));

		if (JFile::exists($absoluteImagePathNew))
		{
			$imageDateOrigin = filemtime($absoluteImagePath);
			$imageDateThumb = filemtime($absoluteImagePathNew);
			$clearCache = ($imageDateOrigin > $imageDateThumb);

			if($clearCache == false)
			{
				return $relativeImagePathNew;
			}
		}

		//echo $relativeImagePathNew;die;
			
		if ($this->imagick_process == 'jimage' && class_exists('JImage'))	// s2s Joomla
		{
			// s2s Use Joomla JImage class (GD)
			if (empty($w)) $w = 0;
			if (empty($h)) $h = 0;
			//echo $imagePath;die;
			// Keep proportions if w or h is not defined
			list($width, $height) = getimagesize($absoluteImagePath);
		//echo $width.' '.$height;die;
			if (!$w) $w = ($h / $height) * $width;
			if (!$h) $h = ($w / $width) * $height;

			//echo $imagePath;die;
			//echo (JURI::root().$imagePath);die;
			// http://stackoverflow.com/questions/10842734/how-resize-image-in-joomla
			try {
				$image = new JImage();
				$image->loadFile($absoluteImagePath);
				//echo'<pre>';var_dump($image);die;
			} catch (Exception $e) {
				return str_replace(JPATH_ROOT, "", $absoluteImagePath);	// "Attempting to load an image of unsupported type: image/x-ms-bmp"
			}
			if ($opts['crop'] === true)
			{
				$rw = $w;
				$rh = $h;
				if ($width/$height < $rw/$rh) {
					$rw = $w;
					$rh = ($rw / $width) * $height;
				}
				else {
					$rh = $h;
					$rw = ($rh / $height) * $width;
				}
				$resizedImage = $image->resize($rw, $rh)->crop($w, $h);
			}
			else
			{
				$resizedImage = $image->resize($w, $h);
			}
			
			$properties = JImage::getImageFileProperties($absoluteImagePath);
			// fix compression level must be 0 through 9 (in case of png)
			$quality = $opts['quality'];
			if ($properties->type == IMAGETYPE_PNG)
			{
				$quality = round(9 - $quality * 9/100);	// 100 quality = 0 compression, 0 quality = 9 compression
			}

			//echo '<pre>';var_dump($properties);die;
			
			$resizedImage->toFile($absoluteImagePathNew, $properties->type, array('quality' => $quality));
		}

		//echo $relativeImagePathNew;die;
		# return cache file path
		return $relativeImagePathNew;
	
	}
}

function cleanPath($path){
	$path = trim($path , "/");
	$pos = stripos(JURI::root(true), $path);
	if($pos !== false){
		return substr($path, substr(JURI::root(true)));
	}else{
		return $path;
	}
}
