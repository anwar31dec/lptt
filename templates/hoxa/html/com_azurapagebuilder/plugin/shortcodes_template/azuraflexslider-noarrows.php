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

if(empty($id)){
	$id = uniqid('flexslider');
}

$classes = 'azp-flexslider';
if ($class) {
	$classes .= ' '.$class;
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

?>
<section class="slider nosidearrows">
	<div id="<?php echo $id;?>" <?php echo $classes.' '.$flexsliderstyle.' '.$animationData;?> >
		<ul class="slides">
			<?php echo do_shortcode($content);?>
		</ul>
	</div>
</section>