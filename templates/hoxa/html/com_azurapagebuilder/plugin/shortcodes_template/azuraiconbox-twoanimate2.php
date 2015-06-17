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
$class .=' rcont';
$animationData = '';
if($animationArgs['animation'] == '1'){
	$iconclass .= ' '.$animationArgs['trigger'];
	if(!empty($animationArgs['animationtype'])){
		$animationData .= 'data-anim-type="'.$animationArgs['animationtype'].'"';
	}
	

	$class .=' animate';
	$anidata2 = ' data-anim-type="fadeInRight"';
	if(!empty($animationArgs['animationdelay'])){
		$anidata2 .= ' data-anim-delay="'.$animationArgs['animationdelay'].'"';
	}
}

if(!empty($class)){
	$class = 'class="'.$class.'"';
}
 
if(!empty($id)){
	$id = 'id="'.$id.'"';
}


?>
<i class="<?php echo $iconclass;?>" <?php echo $animationData.' '.$iconboxstyle;?>></i> <strong <?php echo $id.' '.$class.$anidata2;?>><?php echo $title;?></strong>
        
<p><?php echo nl2br(do_shortcode($content));?></p>
