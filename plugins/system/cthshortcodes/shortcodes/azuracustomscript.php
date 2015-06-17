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

//[Custom Script]
if(!function_exists('azuracustomscript_sc')) {

	function azuracustomscript_sc( $atts, $content="" ) {
	
		extract(shortcode_atts(array(
			 
		 ), $atts));

		$html = '<script type="text/javascript">';

		$html .= do_shortcode($content);

		$html .='</script>';
		
		return $html;
        
	}
		
	add_shortcode( 'AzuraCustomScript', 'azuracustomscript_sc' );
}