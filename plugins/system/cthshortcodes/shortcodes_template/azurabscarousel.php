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

$classes = 'carousel azp_font_edit';

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
 
if(empty($id)){
	$id = uniqid('bs-carousel');
}

//$id = 'id="'.$id.'"';
	$actived = false;
	$indicator = 0;
	
$wrapBool = 'true';
if($wrap != '1'){
	$wrapBool = 'false';
}
?>
  <div id="<?php echo $id;?>" <?php echo $classes.' '.$bscarouselstyle.' '.$animationData;?>>
  <?php if($pagination == '1') :?>
     <!-- Indicators -->
  	<?php if(count($bsCarouselItemArray)):?>
	  	<ol class="azp_carousel-indicators carousel-indicators">
	  	<?php foreach ($bsCarouselItemArray as $key => $carouselItem) :?>
	    	<li data-target="#<?php echo $id;?>" data-slide-to="<?php echo $key;?>" <?php if($key == 0) echo 'class="active"';?>></li>
	    <?php endforeach;?>
	  	</ol>	
	<?php endif;?>
  <?php endif;?>
    <!-- Wrapper for slides -->
    <div class="carousel-inner">
	    <?php echo do_shortcode($content); ?>
    </div>
	
	<?php if($navigation == '1') :?>
    	<!-- Controls -->
	  	<a class="left carousel-control" href="#<?php echo $id;?>" role="button" data-slide="prev">
	    	<span class="glyphicon glyphicon-chevron-left"></span>
	  	</a>
	  	<a class="right carousel-control" href="#<?php echo $id;?>" role="button" data-slide="next">
	    	<span class="glyphicon glyphicon-chevron-right"></span>
	  	</a>
	<?php endif;?>
  </div>
  <script type="text/javascript">
  		jQuery(document).ready(function($) {
  			$('#<?php echo $id;?>').carousel({
			  interval: <?php echo $interval;?>,
			  pause: '<?php echo $pause;?>',
			  wrap: <?php echo $wrapBool;?>
			})
  		});
  </script>