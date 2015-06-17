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

$classes = "azp_alert alert azp_font_edit";

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

$classes .= ' alert-'.strtolower($type);

if(!empty($class)){
	$classes .= ' '.$class;
}

if($fadeeffect !== '0'){
	$classes .= ' fade in';
}

$classes = 'class="'.$classes.'"';
 
if(!empty($id)){
	$id = 'id="'.$id.'"';
}
?>

<div <?php echo $id.' '.$classes.' '.$alertstyle.' '.$animationData;?> role="alert">
<?php if($closebtn !== '0'): ?>
   	<button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
<?php endif;?>
  	<?php echo do_shortcode($content);?>
</div>