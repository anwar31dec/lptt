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

//[Icon]
if(!function_exists('azuraimage_sc')) {

	function azuraimage_sc( $atts, $content="" ) {
	
		extract(shortcode_atts(array(
			   'id' => '',
			   'class'=>'',
               'title'=>'',

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

		$imagestyle = '';

		$styleText = implode(" ", $styleTextArr);
		

		$styleTextTest = trim($styleText);
		if(!empty($styleTextTest)){
			$imagestyle .= trim($styleText);
		}

		if(!empty($imagestyle)){
			$imagestyle = 'style="'.$imagestyle.'"';
		}

		$animationArgs = shortcode_atts(array(

               'animation'=>'0',
               'trigger' => 'animate',
			   'animationtype'=>'',
               'animationdelay'=>'',

		 ), $atts);

		
		$classes = "azp_image";

		if(!empty($class)){
			$classes .= ' '.$class;
		}

		$animationData = '';
		if($animationArgs['animation'] == '1'){
			$classes .= ' '.$animationArgs['trigger'];
			if(!empty($animationArgs['animationtype'])){
				$animationData .= 'data-anim-type="'.$animationArgs['animationtype'].'"';
			}
			if(!empty($animationArgs['animationdelay'])){
				$animationData .= ' data-anim-delay="'.$animationArgs['animationdelay'].'"';
			}
		}

		$classes = 'class="'.$classes.'"';
		 
		if(!empty($id)){
			$id = 'id="'.$id.'"';
		}
        
        $html = '<img '.$id.' '.$classes.' '.$imagestyle.' '.$animationData.' alt="'.$title.'" title="'.$title.'" src="'.JURI::root(true).'/'.$content.'">';
		
		return $html;
        
	 
	}
		
	add_shortcode( 'AzuraImage', 'azuraimage_sc' );
}