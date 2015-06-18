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

//[Accordion Item]
if(!function_exists('azuraaccordionitem_sc')) {

	$accordionGroupID;
	$accordionItem = null;

	function azuraaccordionitem_sc( $atts, $content="" ) {
		global $accordionGroupID;
		global $accordionItem;
	
		extract(shortcode_atts(array(
			   'id' => '',
			   'class' => '',
			   'title'=>'',
			   'subtitle'=>'',
			   'layout'=>''
		 ), $atts));

		$styleArr = shortcode_atts(array(

               'margin_top'=>'',
               'margin_right' => '',
			   'margin_bottom'=>'',
               'margin_left'=>'',

               'border_top_width'=>'',
               'border_right_width' => '',
			   'border_bottom_width'=>'',
               'border_left_width'=>'',

               'padding_top'=>'',
               'padding_right' => '',
			   'padding_bottom'=>'',
               'padding_left'=>'',

               'border_color'=>'',
               'border_style' => '',

			   'background_color'=>'',
               'background_image'=>'',
               'background_repeat'=>'',
               'background_attachment'=>'',
               'background_size'=>'',
               'additional_style'=>'',
               'simplified'=>''

		 ), $atts);

		$styleTextArr = CthShortcodes::parseStyle($styleArr);

		$accordionitemstyle = '';

		$styleText = implode(" ", $styleTextArr);
		

		$styleTextTest = trim($styleText);
		if(!empty($styleTextTest)){
			$accordionitemstyle .= trim($styleText);
		}

		if(!empty($accordionitemstyle)){
			$accordionitemstyle = 'style="'.$accordionitemstyle.'"';
		}

		$animationArgs = shortcode_atts(array(

               'animation'=>'0',
               'trigger' => 'animate',
			   'animationtype'=>'',
               'animationdelay'=>'',

		 ), $atts);


		if(!isset($accordionGroupID)){
			$accordionGroupID = uniqid('AccordionGroupID');
		}

		if(!isset($accordionItem)){
			$accordionItem = 0;
		}

		$shortcodeTemp = false;

		if(stripos($layout, '_:') !== false){
			$shortcodeTemp = JPATH_PLUGINS . '/system/cthshortcodes/shortcodes_template/'.substr($layout, 2).'.php';
		}else{
			if(stripos($layout, ':') !== false){
				$shortcodeTemp = CthShortcodes::templatePath(). '/html/com_azurapagebuilder/plugin/shortcodes_template/'.substr($layout, stripos($layout, ':')+1).'.php';
			}else{
				$shortcodeTemp = CthShortcodes::addShortcodeTemplate('azuraaccordionitem');
			}
		}


		
		
		$buffer = ob_get_clean();
		
		ob_start();
		
		if($shortcodeTemp !== false) require $shortcodeTemp;
		
		$content = ob_get_clean();
		
		ob_start();
		
		echo $buffer;

		$accordionItem++;
		
		return $content;

		
	}
		
	add_shortcode( 'AzuraAccordionItem', 'azuraaccordionitem_sc' );
}