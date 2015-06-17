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
if(!function_exists('azuramodule_sc')) {

	function azuramodule_sc( $atts, $content="" ) {
	
		extract(shortcode_atts(array(
			   'id' => '',
			   'class' => '',
               'moduleid'=>'',
               'showtitle'=>'',
               'style'=>'none',
               'layout'=>''
		 ), $atts));
        if($moduleid == '0' || $moduleid =='') return false;

        $module = CthShortcodes::loadModule($moduleid,$style);
         
        $shortcodeTemp = false;

        if(stripos($layout, '_:') !== false){
            $shortcodeTemp = JPATH_PLUGINS . '/system/cthshortcodes/shortcodes_template/'.substr($layout, 2).'.php';
        }else{
            if(stripos($layout, ':') !== false){
                $shortcodeTemp = CthShortcodes::templatePath(). '/html/com_azurapagebuilder/plugin/shortcodes_template/'.substr($layout, stripos($layout, ':')+1).'.php';
            }else{
                $shortcodeTemp = CthShortcodes::addShortcodeTemplate('azuramodule');
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
		
	add_shortcode( 'AzuraModule', 'azuramodule_sc' );
}