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

//[K2 Category]
if(!function_exists('azurak2category_sc')) {

	function azurak2category_sc( $atts, $content="" ) {
	
		extract(shortcode_atts(array(
			   'id' => '',
			   'class' => '',
         'category'=>'',
         'order'=>'created',
         'orderdir'=>'ASC',
         'limit'=>'All',
         'showfilter'=>'1',
         'fetchchild'=>'0',
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

    $k2categorystyle = '';

    $styleText = implode(" ", $styleTextArr);
    

    $styleTextTest = trim($styleText);
    if(!empty($styleTextTest)){
      $k2categorystyle .= trim($styleText);
    }

    if(!empty($k2categorystyle)){
      $k2categorystyle = 'style="'.$k2categorystyle.'"';
    }

    $animationArgs = shortcode_atts(array(

               'animation'=>'0',
               'trigger' => 'animate-in',
         'animationtype'=>'',
         'hoveranimationtype'=>'',
         'infinite'=>'0',
               'animationdelay'=>'0',

     ), $atts);

      if($category == '0' || $category =='') return false;

      $items = CthShortcodes::getK2Items($category, $limit, $order, $orderdir,'',$fetchchild);

      if($showfilter == '1'){
          $tagsFilter = CthShortcodes::getK2TagsFilter($items);
      }
       
      $shortcodeTemp = false;

      if(stripos($layout, '_:') !== false){
          $shortcodeTemp = JPATH_PLUGINS . '/system/cthshortcodes/shortcodes_template/'.substr($layout, 2).'.php';
      }else{
          if(stripos($layout, ':') !== false){
              $shortcodeTemp = JPATH_THEMES .'/'.JFactory::getApplication()->getTemplate(). '/html/com_azurapagebuilder/plugin/shortcodes_template/'.substr($layout, stripos($layout, ':')+1).'.php';
          }else{
              $shortcodeTemp = CthShortcodes::addShortcodeTemplate('azurak2category');
          }
      }
      
      $buffer = ob_get_clean();
      
      ob_start();
      
      if($shortcodeTemp !== false) require $shortcodeTemp;
      
      $content = ob_get_clean();
      
      ob_start();
      
      echo $buffer;
      
      return $content;
	}
		
	add_shortcode( 'AzuraK2Category', 'azurak2category_sc' );
}