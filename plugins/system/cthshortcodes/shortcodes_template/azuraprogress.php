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

$classes = "azp_progress-bar progress-bar azp_font_edit";

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

if(!empty($type)){
	$classes .= ' progress-bar-'.strtolower($type);
}
if($striped !== '0'){
	$classes .= ' progress-bar-striped';
}

if($striped !== '0' && $animated !== '0'){
	$classes .= ' active';
}

if(!empty($class)){
	$classes .= ' '.$class;
}

$classes = 'class="'.$classes.'"';
 
if(!empty($id)){
	$id = 'id="'.$id.'"';
}

$style = 'width:'.(int)$value.'%;';

if(!empty($customstyle)){
	$style .= ' '.$customstyle;
}

$style = 'style="'.$style.'"';

$srtitle = '<span class="sr-only">'.(int)$value.'% Complete (success)</span>';
// if(!empty($title)){
// 	$srtitle = $title;
// }
?>
<?php if(!empty($title)) :?>
<h3><?php echo $title;?></h3>
<?php endif;?>
<?php if($aschild !== '1') :?>
<div class="progress" <?php echo $progressstyle;?>>
<?php endif;?>
  <div <?php echo $id.' '.$classes.' '.$animationData;?> role="progressbar" aria-valuenow="<?php echo (int)$value;?>" aria-valuemin="0" aria-valuemax="100" <?php echo $style;?>>
    	<?php echo $srtitle;?>
  </div>
<?php if($aschild !== '1') :?>
</div>
<?php endif;?>
