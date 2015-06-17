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

//[Html]
if(!function_exists('azurahtml_sc')) {

	function azurahtml_sc( $atts, $content="" ) {
	
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

		$htmlstyle = '';

		$styleText = implode(" ", $styleTextArr);
		
		$styleTextTest = trim($styleText);
		if(!empty($styleTextTest)){
			$htmlstyle .= trim($styleText);
		}

		if(!empty($htmlstyle)){
			$htmlstyle = 'style="'.$htmlstyle.'"';
		}

		$animationArgs = shortcode_atts(array(

               'animation'=>'0',
               'trigger' => 'animate-in',
			   'animationtype'=>'',
			   'hoveranimationtype'=>'',
			   'infinite'=>'0',
               'animationdelay'=>'0',

		 ), $atts);

		$animationData = '';
		if($animationArgs['animation'] == '1'){
			if($animationArgs['trigger'] == 'animate-in'){
				$class = $animationArgs['trigger'];
				$animationData = 'data-anim-type="'.$animationArgs['animationtype'].'" data-anim-delay="'.$animationArgs['animationdelay'].'"';
			}else{
				$classes = $animationArgs['trigger'].'-'.$animationArgs['hoveranimationtype'];
				if($animationArgs['infinite'] != '0'){
					$class .= ' infinite';
				}
			}
			
			
		}

		if(!empty($class)){
			$class = 'class="'.$class.'"';
		}

		if(empty($htmlstyle)&& empty($class)&&empty($animationData)){

			return do_shortcode($content);
		}else{
			return '<div '.$class.' '.$htmlstyle.' '.$animationData.'>'.do_shortcode($content).'</div>';
		}
	 
	}
		
	add_shortcode( 'AzuraHtml', 'azurahtml_sc' );
}