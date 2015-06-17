<?php
/**
 * @package Azura Joomla Pagebuilder
 * @author Cththemes - www.cththemes.com
 * @date: 15-07-2014
 *
 * @copyright  Copyright ( C ) 2014 cththemes.com . All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die();

$classes = "azp_container azp_font_edit";
if($usefullwidth === '1'){
	$classes = "azp_container-fluid";
}

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

$classes = 'class="'.$classes.'"';
 
if(!empty($id)){
	$id = 'id="'.$id.'"';
}

$html = '<'.$wrappertag.' '.$id.' '.$classes.' '.$containerstyle.' '.$animationData.'>';
if(!empty($title)){
	$html .='<div class="title">';
		$html .='<h2>'.$title.'</h2>';
	$html .='</div>';
}
	$html .= do_shortcode($content);
$html .= '</'.$wrappertag.'>';

echo $html;

?>