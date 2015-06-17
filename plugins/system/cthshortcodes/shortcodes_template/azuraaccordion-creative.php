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

$classes = 'azp_panel-group';
if(!empty($class)){
	$classes .= ' accrodation';
}

$animationData = '';
if($animationArgs['animation'] == '1'){
	$classes .= ' '.$animationArgs['trigger'];
	if(!empty($animationArgs['animationtype'])){
		$animationData .= 'data-anim-type="'.$animationArgs['animationtype'].'"';
	}
	if(!empty($animationArgs['animationdelay'])){
		$animationData .= ' data-anim-delay="'.$animationArgs['animationdelay'].'"';
	}
}


$classes = 'class="'.$classes.'"';
 
if(!empty($id)){
	$id = 'id="'.$id.'"';
}

$rel = '1';
if((int)$defaultactive){
	$rel = (int)$defaultactive;
}

?>
<?php if($acctype == 'accordion'): ?>
<div id="<?php echo $accordionGroupID;?>" <?php echo $classes.' '.$accordionstyle.' '.$animationData;?>>
<?php endif;?>

	<?php echo do_shortcode($content);?>

<?php if($acctype == 'accordion'): ?>
</div>
<?php endif;?>
