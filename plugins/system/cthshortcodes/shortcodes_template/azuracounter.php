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

// must add azp_counter_wrap to warpper element to have appear counter
		
$classes = "azp_counter-circle azp_font_edit";

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
?>

<div <?php echo $id.' '.$classes.' '.$counterstyle.' '.$animationData;?>>
    <strong class="azp_timer" data-from="<?php echo $startvalue;?>" data-to="<?php echo $stopvalue;?>"
data-speed="<?php echo $speed;?>" 
	<?php if(!empty($refreshinterval)): ?>
	data-refresh-interval="<?php echo $refreshinterval;?>"
	<?php endif;?>></strong>
	<?php echo $unit;?>
</div>
<p><?php echo nl2br(do_shortcode($content));?></p>

