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

$classes = 'azp-flexslider_infinite azp_font_edit';
if ($class) {
	$classes .= ' '.$class;
}

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

?>
<div id="<?php echo $id;?>" <?php echo $classes.' '.$flexsliderstyle.' '.$animationData;?> >
	<ul class="slides">
		<?php echo do_shortcode($content);?>
	</ul>
</div>
<?php if($usejsplugin == '1'):?>
	<script src="<?php echo JURI::root(true).'/components/com_azurapagebuilder/assets/js/jquery.flexslider.js';?>"></script>
<?php endif;?>
<script>

(function($) {
"use strict";

	$(window).load(function(){
	  	$('#<?php echo $id;?>').flexslider({
	    	<?php if($namespace != 'flex-'): ?>
				namespace : '<?php echo $namespace;?>',
			<?php endif;?>

			<?php if($selector != '.slides > li'): ?>
				selector : '<?php echo $selector;?>',
			<?php endif;?>

			<?php if($animation != 'fade'): ?>
				animation : '<?php echo $animation;?>',
			<?php endif;?>

			<?php if($easing != 'swing'): ?>
				easing : '<?php echo $easing;?>',
			<?php endif;?>

			<?php if($direction != 'horizontal'): ?>
				direction : '<?php echo $direction;?>',
			<?php endif;?>

			<?php if($reverse != '0'): ?>
				reverse : true,
			<?php endif;?>

			<?php if($animationloop != '1'): ?>
				animationLoop : false,
			<?php endif;?>

			<?php if($smoothheight != '0'): ?>
				smoothHeight : true,
			<?php endif;?>

			<?php if($startat != '0'): ?>
				startAt : <?php echo $startat;?>,
			<?php endif;?>

			<?php if($slideshow != '1'): ?>
				slideshow : false,
			<?php endif;?>

			<?php if($slideshowspeed != '7000'): ?>
				slideshowSpeed : <?php echo $slideshowspeed;?>,
			<?php endif;?>

			<?php if($animationspeed != '600'): ?>
				animationSpeed : <?php echo $animationspeed;?>,
			<?php endif;?>

			<?php if($initdelay != '0'): ?>
				initDelay : <?php echo $initdelay;?>,
			<?php endif;?>

			<?php if($randomize != '0'): ?>
				randomize : true,
			<?php endif;?>

			<?php if($pauseonaction != '1'): ?>
				pauseOnAction : false,
			<?php endif;?>

			<?php if($pauseonhover != '0'): ?>
				pauseOnHover : true,
			<?php endif;?>

			<?php if($usecss != '1'): ?>
				useCSS : false,
			<?php endif;?>

			<?php if($touch != '1'): ?>
				touch : false,
			<?php endif;?>

			<?php if($video != '0'): ?>
				video : true,
			<?php endif;?>

			<?php if($controlnav != '1'): ?>
				controlNav : false,
			<?php endif;?>

			<?php if($directionnav != '1'): ?>
				directionNav : false,
			<?php endif;?>

			<?php if($nexttext != 'Next'): ?>
			<?php if(stripos($nexttext, 'fa') !== false || stripos($nexttext, 'icon') !== false || stripos($nexttext, 'glyphicon') !== false) $nexttext = '<i class="'.$nexttext.'"></i>';?>
				nextText : '<?php echo $nexttext;?>',
			<?php endif;?>	

			<?php if($prevtext != 'Previous'): ?>
			<?php if(stripos($prevtext, 'fa') !== false || stripos($prevtext, 'icon') !== false || stripos($prevtext, 'glyphicon') !== false) $prevtext = '<i class="'.$prevtext.'"></i>';?>
				prevText : '<?php echo $prevtext;?>',
			<?php endif;?>	

			<?php if($keyboard != '1'): ?>
				keyboard : false,
			<?php endif;?>

			<?php if($multiplekeyboard != '0'): ?>
				multipleKeyboard : true,
			<?php endif;?>

			<?php if($mousewheel != '0'): ?>
				mousewheel : true,
			<?php endif;?>

			<?php if($pauseplay != '0'): ?>
				pausePlay : true,
			<?php endif;?>

			<?php if($pausetext != 'Pause'): ?>
			<?php if(stripos($pausetext, 'fa') !== false || stripos($pausetext, 'icon') !== false || stripos($pausetext, 'glyphicon') !== false) $pausetext = '<i class="'.$pausetext.'"></i>';?>
				pauseText : '<?php echo $pausetext;?>',
			<?php endif;?>	

			<?php if($playtext != 'Play'): ?>
			<?php if(stripos($playtext, 'fa') !== false || stripos($playtext, 'icon') !== false || stripos($playtext, 'glyphicon') !== false) $playtext = '<i class="'.$playtext.'"></i>';?>
				playText : '<?php echo $playtext;?>',
			<?php endif;?>	


			<?php if(!empty($sync)): ?>
				sync : '<?php echo $sync;?>',
			<?php endif;?>

			<?php if(!empty($asnavfor)): ?>
				asNavFor : '<?php echo $asnavfor;?>',
			<?php endif;?>

			<?php if($itemwidth != '0'): ?>
				itemWidth : <?php echo $itemwidth;?>,
			<?php endif;?>

			<?php if($itemmargin != '0'): ?>
				itemMargin : <?php echo $itemmargin;?>,
			<?php endif;?>

			<?php if($minitems != '0'): ?>
				minItems : <?php echo $minitems;?>,
			<?php endif;?>

			<?php if($maxitems != '0'): ?>
				maxItems : <?php echo $maxitems;?>,
			<?php endif;?>

			<?php if($move != '0'): ?>
				move : <?php echo $move;?>,
			<?php endif;?>

			start: function() {
	            
	            //jQuery('.azp-flexslider_infinite .animate-in').addClass("in").removeClass("out");
	        },
	        before: function() {
				
	            //jQuery('.azp-flexslider_infinite .in').addClass("out").removeClass("in");
	            //jQuery('.azp-flexslider_infinite *[data-anim-type]').addClass("animate-in");
	        },
	        after: function() {
				
	            //jQuery('.azp-flexslider_infinite *[data-anim-type]').addClass("animate-in");
	        },
	    
	  	});
	});

})(jQuery);
</script>

