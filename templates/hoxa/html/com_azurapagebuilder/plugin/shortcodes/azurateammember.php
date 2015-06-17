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

//[Team Member]
if(!function_exists('azurateammember_sc')) {

	function azurateammember_sc( $atts, $content="" ) {
	
		extract(shortcode_atts(array(
			   'id' => '',
			   'class'=>'',
			   'name'=>'',
			   'position'=>'',
			   'photo'=>'',
               'introduction'=>'',
               'twitter' => '',
			   'facebook'=>'',
			   'dribbble'=>'',
			   'linkedin'=>'',
			   'googleplus'=>''
		 ), $atts));


		$shortcodeTemp = false;

		$shortcodeTemp = CthShortcodes::addShortcodeTemplate('azurateammember');

		
		$buffer = ob_get_clean();
		
		ob_start();
		
		if($shortcodeTemp != false) require $shortcodeTemp;
		
		$content = ob_get_clean();
		
		ob_start();
		
		echo $buffer;
		
		return $content;

	 
	}
		
	add_shortcode( 'AzuraTeamMember', 'azurateammember_sc' );
}