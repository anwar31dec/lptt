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
if(!function_exists('azuratext_sc')) {

	function azuratext_sc( $atts, $content="" ) {
	
		extract(shortcode_atts(array(
			   'id' => '',
			   'class' => '',
          'wrapper'=>''
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

    $textstyle = '';

    $styleText = implode(" ", $styleTextArr);
    

    $styleTextTest = trim($styleText);
    if(!empty($styleTextTest)){
      $textstyle .= trim($styleText);
    }

    if(!empty($textstyle)){
      $textstyle = 'style="'.$textstyle.'"';
    }
      
    $animationArgs = shortcode_atts(array(

     'animation'=>'0',
     'trigger' => 'animate-in',
     'animationtype'=>'',
     'hoveranimationtype'=>'',
     'infinite'=>'0',
      'animationdelay'=>'0',

     ), $atts);

    $classes = 'azp_font_edit';

    $animationData = '';
    if($animationArgs['animation'] == '1'){
      if($animationArgs['trigger'] == 'animate-in'){
        $classes .= ' '.$animationArgs['trigger'];
        $animationData = 'data-anim-type="'.$animationArgs['animationtype'].'" data-anim-delay="'.$animationArgs['animationdelay'].'"';
      }else{
        $classes .= ' '.$animationArgs['trigger'].'-'.$animationArgs['hoveranimationtype'];
        if($animationArgs['infinite'] != '0'){
          $classes .= ' infinite';
        }
      }
      
      
    }


    if(!empty($class)){
      $classes .= ' '.$class;
    }

    if(!empty($classes)){
      $classes = 'class="'.$classes.'"';
    }
        
         $html = '';
         if(!empty($wrapper)){
            $html .= '<'.$wrapper.' '.$textstyle.' '.$classes.' '.$animationData;
            
            if(!empty($id)){
                $html .= ' id="'.$id.'"';
            }
            $html .='>';
         }
	       $html .= do_shortcode($content);
        if(!empty($wrapper)){
            $html .= '</'.$wrapper.'>';
         }
	 
        return $html;
	}
		
	add_shortcode( 'AzuraText', 'azuratext_sc' );
}