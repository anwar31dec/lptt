<?php 
/**
 * @package Azura Joomla Pagebuilder
 * @author Cththemes - www.cththemes.com
 * @date: 15-07-2014
 *
 * @copyright  Copyright ( C ) 2014 cththemes.com . All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;

$columnwidthclass .= ' azp_font_edit';

$animationData = '';
if($animationArgs['animation'] == '1'){
	if($animationArgs['trigger'] == 'animate-in'){
		$columnwidthclass .= ' '.$animationArgs['trigger'];
		$animationData = 'data-anim-type="'.$animationArgs['animationtype'].'" data-anim-delay="'.$animationArgs['animationdelay'].'"';
	}else{
		$columnwidthclass .= ' '.$animationArgs['trigger'].'-'.$animationArgs['hoveranimationtype'];
		if($animationArgs['infinite'] != '0'){
			$columnwidthclass .= ' infinite';
		}
	}
	
	
}


if(!empty($class)){
	$columnwidthclass .= ' '.$class;
}

$columnwidthclass = 'class="'.$columnwidthclass.'"';
 
if(!empty($id)){
	$id = 'id="'.$id.'"';
}


?>

<div <?php echo $id . ' ' .$columnwidthclass.' '.$columnstyle.' '.$animationData;?>><?php echo do_shortcode($content);?></div>