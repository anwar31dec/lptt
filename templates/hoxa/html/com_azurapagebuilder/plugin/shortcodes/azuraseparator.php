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
if(!function_exists('azuraseparator_sc')) {

	function azuraseparator_sc( $atts, $content="" ) {
	
		extract(shortcode_atts(array(
			   'id' => '',
			   'class'=>'',
               'text'=>'',
               'iconclass'=>'',
			   'wrapper'=>'div'
		 ), $atts));

		
		$classes = "azp_separator";

		if(!empty($class)){
			$classes .= ' '.$class;
		}

		$classes = 'class="'.$classes.'"';
		 
		if(!empty($id)){
			$id = 'id="'.$id.'"';
		}
        
        if($wrapper == 'hr'){
            return '<hr '.$id.' '.$classes.'>';
        }elseif($wrapper == 'br'){
        	return '<br '.$id.' '.$classes.'>';
        }else{
            $html = '<'.$wrapper.' '.$id.' '.$classes.'>';
    			if(!empty($iconclass)){
    			     $html .= '<i class="'.$iconclass.'"></i>';
    			}elseif(!empty($text)){
    			  $html .= $text;
    			}
    		$html .= '</'.$wrapper.'>';
    		
    		return $html;
        }
	 
	}
		
	add_shortcode( 'AzuraSeparator', 'azuraseparator_sc' );
}