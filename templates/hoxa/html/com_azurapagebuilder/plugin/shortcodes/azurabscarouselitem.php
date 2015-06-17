<?php
/**
 * @package Azura Joomla Pagebuilder
 * @author Cththemes - www.cththemes.com
 * @date: 15-07-2014
 *
 * @copyright  Copyright ( C ) 2014 cththemes.com . All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */
//no direct accees
defined ('_JEXEC') or die('resticted aceess');

//[Bs Carousel Item]
if(!function_exists('azurabscarousel_item_sc')) {
	$bsCarouselItemArray = array();
	$bsCarouselItem = null;

	function azurabscarousel_item_sc( $atts, $content="" ) {

		global $bsCarouselItemArray;
		global $bsCarouselItem;
	
		extract(shortcode_atts(array(
			   'id' => '',
			   'class' => '',
			   'image'=>'',
			   'layout'=>''
		 ), $atts));

		$bsCarouselItemArray[] = array('id'=>$id,'class'=>$class,'image'=>$image,'layout'=>$layout,'content'=>$content);

		if(!isset($bsCarouselItem)){
			$bsCarouselItem = 0;
		}

		$shortcodeTemp = false;

		if(stripos($layout, '_:') !== false){
			$shortcodeTemp = JPATH_PLUGINS . '/system/cthshortcodes/shortcodes_template/'.substr($layout, 2).'.php';
		}else{
			if(stripos($layout, ':') !== false){
				$shortcodeTemp = CthShortcodes::templatePath(). '/html/com_azurapagebuilder/plugin/shortcodes_template/'.substr($layout, stripos($layout, ':')+1).'.php';
			}else{
				$shortcodeTemp = CthShortcodes::addShortcodeTemplate('azurabscarouselitem');
			}
		}


		
		
		$buffer = ob_get_clean();
		
		ob_start();
		
		if($shortcodeTemp !== false) require $shortcodeTemp;
		
		$content = ob_get_clean();
		
		ob_start();
		
		echo $buffer;

		$bsCarouselItem++;
		
		return $content;

		
	}
		
	add_shortcode( 'AzuraBsCarouselItem', 'azurabscarousel_item_sc' );
}