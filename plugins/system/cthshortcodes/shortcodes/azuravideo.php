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

//[video]
if(!function_exists('azuravideo_sc')) {
	function azuravideo_sc( $atts, $content="" ){
	
		extract(shortcode_atts(array(
			'id'=>'',
			'class'=>'',
			'autoplay'=>'',
			'loop'=>'',
			'width'=>'',
			'height'=>'',
			'layout' => ''
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

    $videostyle = '';

    $styleText = implode(" ", $styleTextArr);
    

    $styleTextTest = trim($styleText);
    if(!empty($styleTextTest)){
      $videostyle .= trim($styleText);
    }

    if(!empty($videostyle)){
      $videostyle = 'style="'.$videostyle.'"';
    }

    		$animationArgs = shortcode_atts(array(

               'animation'=>'0',
               'trigger' => 'animate-in',
			   'animationtype'=>'',
			   'hoveranimationtype'=>'',
			   'infinite'=>'0',
               'animationdelay'=>'0',

		 ), $atts);

		$video = parse_url($content);
		
		switch($video['host']) {
			case 'youtu.be':
				$id = trim($video['path'],'/');
				$src = 'https://www.youtube.com/embed/' . $id;
			break;
			
			case 'www.youtube.com':
			case 'youtube.com':
				parse_str($video['query'], $query);
				$id = $query['v'];
				$src = 'https://www.youtube.com/embed/' . $id;
			break;
			
			case 'vimeo.com':
			case 'www.vimeo.com':
				$id = trim($video['path'],'/');
				$src = "http://player.vimeo.com/video/{$id}";
		}

		$shortcodeTemp = false;

		if(stripos($layout, '_:') !== false){
			$shortcodeTemp = JPATH_PLUGINS . '/system/cthshortcodes/shortcodes_template/'.substr($layout, 2).'.php';
		}else{
			if(stripos($layout, ':') !== false){
				$shortcodeTemp = JPATH_THEMES .'/'.JFactory::getApplication()->getTemplate(). '/html/com_azurapagebuilder/plugin/shortcodes_template/'.substr($layout, stripos($layout, ':')+1).'.php';
			}else{
				$shortcodeTemp = CthShortcodes::addShortcodeTemplate('azuravideo');
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
	
	add_shortcode( 'AzuraVideo', 'azuravideo_sc' );
}