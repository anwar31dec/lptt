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


//[Tab]
if(!function_exists('azuratabtoggle_item_sc')) {

	//$azuraTabToggleArray = array();
	//Tab Items
	function azuratabtoggle_item_sc( $atts, $content="" ){
		global $azuraTabToggleArray;
		global $azuraTabItem;
		//$azuraTabToggleArray[] = array('id'=>(!empty($atts['id'])? $atts['id'] : ''), 'class'=>(!empty($atts['class'])? $atts['class'] : ''),'title'=>(!empty($atts['title'])? $atts['title'] : ''), 'iconclass'=>(!empty($atts['iconclass'])? $atts['iconclass'] : ''), 'content'=>$content);

		extract(shortcode_atts(array(
			  'id' => '',
			  'class'=>'',
			  'title'=>'First Tab',
			  'iconclass'=>'',
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

		$tabtoggleitemstyle = '';

		$styleText = implode(" ", $styleTextArr);
		

		$styleTextTest = trim($styleText);
		if(!empty($styleTextTest)){
			$tabtoggleitemstyle .= trim($styleText);
		}

		if(!empty($tabtoggleitemstyle)){
			$tabtoggleitemstyle = 'style="'.$tabtoggleitemstyle.'"';
		}

		$animationArgs = shortcode_atts(array(

               'animation'=>'0',
               'trigger' => 'animate',
			   'animationtype'=>'',
               'animationdelay'=>'',

		 ), $atts);

		if(!isset($azuraTabItem)){
			$azuraTabItem = 0;
		}


		if(empty($id)){
			$id = uniqid('TabItem');
		}

		$azuraTabToggleArray[] = array('id'=>$id, 'class'=>$class,'title'=>$title, 'iconclass'=>$iconclass, 'animationargs'=>$animationArgs, 'content'=>$content);


		$shortcodeTemp = false;

		if(stripos($layout, '_:') !== false){
			$shortcodeTemp = JPATH_PLUGINS . '/system/cthshortcodes/shortcodes_template/'.substr($layout, 2).'.php';
		}else{
			if(stripos($layout, ':') !== false){
				$shortcodeTemp = CthShortcodes::templatePath(). '/html/com_azurapagebuilder/plugin/shortcodes_template/'.substr($layout, stripos($layout, ':')+1).'.php';
			}else{
				$shortcodeTemp = CthShortcodes::addShortcodeTemplate('azuratabtoggleitem');
			}
		}


		
		
		$buffer = ob_get_clean();
		
		ob_start();
		
		if($shortcodeTemp !== false) require $shortcodeTemp;
		
		$content = ob_get_clean();
		
		ob_start();
		
		echo $buffer;

		$azuraTabItem++;
		
		return $content;
	}

	add_shortcode( 'AzuraTabToggleItem', 'azuratabtoggle_item_sc' );

		
}