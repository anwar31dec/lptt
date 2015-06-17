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

$classes = 'item';
if(!empty($class)){
	$classes .= ' '.$class;
}


if($bsCarouselItem == 0){
	$classes .= ' active';
}

$classes = 'class="'.$classes.'"';
 
if(!empty($id)){
	$id = 'id="'.$id.'"';
}


?>
<div <?php echo $id.' '.$classes;?>>
	<?php if(!empty($image)) : ?>
	<img src="<?php echo JURI::root(true).'/'.$image;?>" alt="Image">
	<?php endif;?>
	<?php if(!empty($content)) : ?>
	<div class="carousel-caption">
		<?php echo nl2br(do_shortcode($content));?>
	</div>
	<?php endif;?>
</div>