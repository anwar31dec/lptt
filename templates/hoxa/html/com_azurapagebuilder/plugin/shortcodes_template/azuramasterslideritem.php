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



$classes = 'azp-masterslideritem ms-slide';
$animationData = '';
if($animationArgs['animation'] == '1'){
	$classes .= ' '.$animationArgs['trigger'];
	$animationData = 'data-anim-type="'.$animationArgs['animationtype'].'" data-anim-delay="'.$animationArgs['animationdelay'].'"';
}
if (!empty($class)) {
	$classes .= ' '.$class;
}

$classes = 'class="'.$classes.'"';

if(empty($id)){
	$id = 'id="'.$id.'"';
}

?>

<!-- new slide -->
<div <?php echo $id.' '.$classes.' '.$masterslideritemstyle.' '.$animationData;?>>
     
    <?php if(!empty($slidebackground)) :?>
    <!-- slide background -->
    <img src="<?php echo CthShortcodes::templateUri();?>/js/masterslider/blank.gif" data-src="<?php echo JURI::root(true).'/'.$slidebackground;?>" alt=""/>     
	<?php endif;?>
    <?php echo do_shortcode($content);?>

</div>
<!-- end of slide -->

