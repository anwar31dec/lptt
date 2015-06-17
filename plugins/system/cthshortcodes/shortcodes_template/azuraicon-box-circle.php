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

$class .=' azp_icon_box-circle azp_font_edit'; // class for circle box style

$animationData = '';
if($animationArgs['animation'] == '1'){
	if($animationArgs['trigger'] == 'animate-in'){
		$class .= ' '.$animationArgs['trigger'];
		$animationData = 'data-anim-type="'.$animationArgs['animationtype'].'" data-anim-delay="'.$animationArgs['animationdelay'].'"';
	}else{
		$class .= ' '.$animationArgs['trigger'].'-'.$animationArgs['hoveranimationtype'];
		if($animationArgs['infinite'] != '0'){
			$class .= ' infinite';
		}
	}
	
	
}

if(!empty($class)){
	$class = 'class="'.$class.'"';
}
 
if(!empty($id)){
	$id = 'id="'.$id.'"';
}


?>
<div <?php echo $id.' '.$class.' '.$iconstyle.' '.$animationData;?> >

	<i class="<?php echo $iconclass;?>"></i>
</div>       
	<strong class="azp_strong"><?php echo $title;?></strong>

	<p><?php echo nl2br(do_shortcode($content));?></p>
