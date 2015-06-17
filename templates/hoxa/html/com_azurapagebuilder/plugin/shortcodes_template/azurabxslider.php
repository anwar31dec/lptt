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
	$id = uniqid('bxslider');
}

$classes = 'azp-bxslider';
if ($class) {
	$classes .= ' '.$class;
}

$classes = 'class="'.$classes.'"';

?>

<ul id="<?php echo $id;?>" <?php echo $classes;?>>
	<?php echo do_shortcode($content);?>
</ul>

<script>
jQuery(function($){

	var azp_bxslider = $('#<?php echo $id;?>');

	azp_bxslider.bxSlider({
		<?php if($mode != 'horizontal'): ?>
			mode : '<?php echo $mode;?>',
		<?php endif;?>	

		<?php if($speed != '500'): ?>
			speed : <?php echo $speed;?>,
		<?php endif;?>	

		<?php if($slidemargin != '0'): ?>
			slideMargin : <?php echo $slidemargin;?>,
		<?php endif;?>	

		<?php if($startslide != '0'): ?>
			startSlide : <?php echo $startslide;?>,
		<?php endif;?>	

		<?php if($randomstart != '0'): ?>
			randomStart : true,
		<?php endif;?>	

		<?php if(!empty($slideselector)): ?>
			slideSelector : '<?php echo $slideselector;?>',
		<?php endif;?>	

		<?php if($infiniteloop != '1'): ?>
			infiniteLoop : false,
		<?php endif;?>	

		<?php if($hidecontrolonend != '0'): ?>
			hideControlOnEnd : true,
		<?php endif;?>	

		<?php if($easing != 'null'): ?>
			easing : '<?php echo $easing;?>',
		<?php endif;?>	

		<?php if($captions != '0'): ?>
			captions : true,
		<?php endif;?>	

		<?php if($ticker != '0'): ?>
			ticker : true,
		<?php endif;?>	

		<?php if($tickerhover != '0'): ?>
			tickerHover : true,
		<?php endif;?>	

		<?php if($adaptiveheight != '0'): ?>
			adaptiveHeight : true,
		<?php endif;?>	

		<?php if($adaptiveheightspeed != '500'): ?>
			adaptiveHeightSpeed : <?php echo $adaptiveheightspeed;?>,
		<?php endif;?>	

		<?php if($video != '0'): ?>
			video : true,
		<?php endif;?>	

		<?php if($responsive != '1'): ?>
			responsive : false,
		<?php endif;?>	

		<?php if($usecss != '1'): ?>
			useCSS : false,
		<?php endif;?>	

		<?php if($preloadimages != 'visible'): ?>
			preloadImages : '<?php echo $preloadimages;?>',
		<?php endif;?>	

		<?php if($touchenabled != '1'): ?>
			touchEnabled : false,
		<?php endif;?>

		<?php if($swipethreshold != '50'): ?>
			swipeThreshold : <?php echo $swipethreshold;?>,
		<?php endif;?>	

		<?php if($onetoonetouch != '1'): ?>
			oneToOneTouch : false,
		<?php endif;?>	

		<?php if($preventdefaultswipex != '1'): ?>
			preventDefaultSwipeX : false,
		<?php endif;?>	

		<?php if($preventdefaultswipey != '0'): ?>
			preventDefaultSwipeY : true,
		<?php endif;?>

		// Pager	

		<?php if($pager != '1'): ?>
			pager : false,
		<?php endif;?>	

		<?php if($pagertype != 'full'): ?>
			pagerType : '<?php echo $pagertype;?>',
		<?php endif;?>	

		<?php if($pagershortseparator != '/'): ?>
			pagerShortSeparator : '<?php echo $pagershortseparator;?>',
		<?php endif;?>	

		<?php if(!empty($pagerselector)): ?>
			pagerSelector : '<?php echo $pagerselector;?>',
		<?php endif;?>	

		<?php if($pagercustom != 'null'): ?>
			pagerCustom : '<?php echo $pagercustom;?>',
		<?php endif;?>	

		<?php if($buildpager != 'null'): ?>
			buildPager : '<?php echo $buildpager;?>',
		<?php endif;?>

		// Control	

		<?php if($controls != '1'): ?>
			controls : false,
		<?php endif;?>	

		<?php if($nexttext != 'Next'): ?>
		<?php if(stripos($nexttext, 'fa') !== false || stripos($nexttext, 'icon') !== false || stripos($nexttext, 'glyphicon') !== false) $nexttext = '<i class="'.$nexttext.'"></i>';?>
			nextText : '<?php echo $nexttext;?>',
		<?php endif;?>	

		<?php if($prevtext != 'Prev'): ?>
		<?php if(stripos($prevtext, 'fa') !== false || stripos($prevtext, 'icon') !== false || stripos($prevtext, 'glyphicon') !== false) $prevtext = '<i class="'.$prevtext.'"></i>';?>
			prevText : '<?php echo $prevtext;?>',
		<?php endif;?>	

		<?php if($nextselector != 'null'): ?>
			nextSelector : '<?php echo $nextselector;?>',
		<?php endif;?>	

		<?php if($prevselector != 'null'): ?>
			prevSelector : '<?php echo $prevselector;?>',
		<?php endif;?>

		<?php if($autocontrols != '0'): ?>
			autoControls : true,
		<?php endif;?>	

		<?php if($starttext != 'Start'): ?>
			startText : '<?php echo $starttext;?>',
		<?php endif;?>	

		<?php if($stoptext != 'Stop'): ?>
			stopText : '<?php echo $stoptext;?>',
		<?php endif;?>	

		<?php if($autocontrolscombine != '0'): ?>
			autoControlsCombine : true,
		<?php endif;?>

		<?php if($autocontrolsselector != 'null'): ?>
			autoControlsSelector : '<?php echo $autocontrolsselector;?>',
		<?php endif;?>	

		// Auto	

		<?php if($auto != '0'): ?>
			auto : true,
		<?php endif;?>	

		<?php if($pause != '4000'): ?>
			pause : <?php echo $pause;?>,
		<?php endif;?>	

		<?php if($autostart != '1'): ?>
			autoStart : false,
		<?php endif;?>	

		<?php if($autodirection != 'next'): ?>
			autoDirection : 'prev',
		<?php endif;?>

		<?php if($autohover != '0'): ?>
			autoHover : true,
		<?php endif;?>	

		<?php if($autodelay != '0'): ?>
			autoDelay : <?php echo $autodelay;?>,
		<?php endif;?>	


		// Carousel

		<?php if($minslides != '1'): ?>
			minSlides : <?php echo $minslides;?>,
		<?php endif;?>	

		<?php if($maxslides != '1'): ?>
			maxSlides : <?php echo $maxslides;?>,
		<?php endif;?>

		<?php if($moveslides != '0'): ?>
			moveSlides : <?php echo $moveslides;?>,
		<?php endif;?>

		<?php if($slidewidth != '0'): ?>
			slideWidth : <?php echo $slidewidth;?>,
		<?php endif;?>

	});

})
</script>

