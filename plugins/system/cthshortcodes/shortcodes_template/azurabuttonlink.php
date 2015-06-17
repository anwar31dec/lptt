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

if(!empty($id)) {
	$id = 'id="'.$id.'"';
}
$classes = 'azp_btn azp_font_edit';

if(!empty($class)) {
	$classes .= ' '.$class;
}
//animation
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

$classes = 'class="'.$classes.'"';

if(!empty($target)) {
	$target = 'target="'.$target.'"';
}
if(!empty($title)) {
	$title = 'title="'.$title.'"';
}
if(!empty($link)) {
	$link = 'href="'.$link.'"';
}
?>

<a <?php echo $id.' '.$classes.' '.$link.' '.$target.' '.$title.' '.$buttonlinkstyle.' '.$animationData;?> >
	<?php if(!empty($image)):?>
		<img src="<?php echo JURI::base(true).'/'.$image;?>" >
	<?php else :?>
		<?php echo $text;?>
	<?php endif;?>
</a>