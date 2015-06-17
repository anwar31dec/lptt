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

//[carousel item]
if(!function_exists('azuracarouselslider_item_sc')) {
	function azuracarouselslider_item_sc( $atts, $content="" ){
		
		extract(shortcode_atts(array(
			  'id' => '',
			  'class'=>'',
			  'slideimage'=>'',
			  //'layout'=>''
		 ), $atts));

		$shortcodeTemp = false;

		$shortcodeTemp = CthShortcodes::addShortcodeTemplate('azuracarouselslideritem');
		


		
		
		$buffer = ob_get_clean();
		
		ob_start();
		
		if($shortcodeTemp !== false) require $shortcodeTemp;
		
		$content = ob_get_clean();
		
		ob_start();
		
		echo $buffer;
		
		return $content;

	}

	add_shortcode( 'AzuraCarouselSliderItem', 'azuracarouselslider_item_sc' );	


}