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

//[bxslider]
if(!function_exists('azurabxslider_sc')) {

	function azurabxslider_sc( $atts, $content="" ){
		
		extract(shortcode_atts(array(
			  'id' => '',
			  'class'=>'bxslider',
			  'mode'=>'horizontal',
			  'speed'=>'500',
			  'slidemargin'=>'0',
			  'startslide'=>'0',
			  'randomstart'=>'0',
			  'slideselector'=>'',
			  'infiniteloop'=>'1',
			  'hidecontrolonend'=>'0',
			  'easing'=>'null',
			  'captions'=>'0',
			  'ticker'=>'0',
			  'tickerhover'=>'0',
			  'adaptiveheight'=>'0',

			  'adaptiveheightspeed' => '500',
			  'video'=>'0',
			  'responsive'=>'1',
			  'usecss'=>'1',
			  'preloadimages'=>'visible',
			  'touchenabled'=>'1',
			  'swipethreshold'=>'50',
			  'onetoonetouch'=>'1',
			  'preventdefaultswipex'=>'1',
			  'preventdefaultswipey'=>'0',
			  'pager'=>'1',
			  'pagertype'=>'full',
			  'pagershortseparator'=>'/',
			  'pagerselector'=>'',
			  'pagercustom'=>'null',
			  'buildpager'=>'null',

			  'controls' => '1',
			  'nexttext'=>'Next',
			  'prevtext'=>'Prev',
			  'nextselector'=>'null',
			  'prevselector'=>'null',
			  'autocontrols'=>'0',
			  'starttext'=>'Start',
			  'stoptext'=>'Stop',
			  'autocontrolscombine'=>'0',
			  'autocontrolsselector'=>'null',

			  'auto'=>'0',
			  'pause'=>'4000',
			  'autostart'=>'1',
			  'autodirection'=>'next',
			  'autohover'=>'0',
			  'autodelay'=>'0',
			  'minslides'=>'1',
			  'maxslides'=>'1',
			  'moveslides'=>'0',
			  'slidewidth'=>'0',

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

        $bxsliderstyle = '';

        $styleText = implode(" ", $styleTextArr);
        
        $styleTextTest = trim($styleText);
        if(!empty($styleTextTest)){
            $bxsliderstyle .= trim($styleText);
        }

        if(!empty($bxsliderstyle)){
            $bxsliderstyle = 'style="'.$bxsliderstyle.'"';
        }

        $animationArgs = shortcode_atts(array(

               'animation'=>'0',
               'trigger' => 'animate-in',
			   'animationtype'=>'',
			   'hoveranimationtype'=>'',
			   'infinite'=>'0',
               'animationdelay'=>'0',

		 ), $atts);

		$shortcodeTemp = false;

		if(stripos($layout, '_:') !== false){
			$shortcodeTemp = JPATH_PLUGINS . '/system/cthshortcodes/shortcodes_template/'.substr($layout, 2).'.php';
		}else{
			if(stripos($layout, ':') !== false){
				$shortcodeTemp = JPATH_THEMES .'/'.JFactory::getApplication()->getTemplate(). '/html/com_azurapagebuilder/plugin/shortcodes_template/'.substr($layout, stripos($layout, ':')+1).'.php';
			}else{
				$shortcodeTemp = CthShortcodes::addShortcodeTemplate('azurabxslider');
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
	
	add_shortcode( 'AzuraBxSlider', 'azurabxslider_sc' );
		
			
}