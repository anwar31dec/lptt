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

        $carouselslideritemstyle = '';

        $styleText = implode(" ", $styleTextArr);
        
        $styleTextTest = trim($styleText);
        if(!empty($styleTextTest)){
            $carouselslideritemstyle .= trim($styleText);
        }

        if(!empty($carouselslideritemstyle)){
            $carouselslideritemstyle = 'style="'.$carouselslideritemstyle.'"';
        }

		$shortcodeTemp = false;

    if(stripos($layout, '_:') !== false){
      $shortcodeTemp = JPATH_PLUGINS . '/system/cthshortcodes/shortcodes_template/'.substr($layout, 2).'.php';
    }else{
      if(stripos($layout, ':') !== false){
        $shortcodeTemp = JPATH_THEMES .'/'.JFactory::getApplication()->getTemplate(). '/html/com_azurapagebuilder/plugin/shortcodes_template/'.substr($layout, stripos($layout, ':')+1).'.php';
      }else{
        $shortcodeTemp = CthShortcodes::addShortcodeTemplate('azuracarouselslideritem');
      }
    }


    
    
    $buffer = ob_get_clean();
    
    ob_start();
    
    if($shortcodeTemp !== false) require $shortcodeTemp;
    
    $content = ob_get_clean();
    
    ob_start();
    
    echo $buffer;

    $accordionItem = null;
    
    return $content;
		

	}

	add_shortcode( 'AzuraCarouselSliderItem', 'azuracarouselslider_item_sc' );	


}