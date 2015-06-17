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
	$id = uniqid('owl-demo');
}


$classes = 'azp-carousel';
if ($class) {
	$classes .= ' '.$class;
}

$classes = 'class="'.$classes.'"';

?>
<!-- Services slider -->
<div class="row relative">
	<div id="<?php echo $id;?>" <?php echo $classes;?>>
		<?php echo do_shortcode($content);?>
	</div>


	<!-- Controls for the services slider -->
	<a class="serv-prev oc-left"><i class="arrow-control fa fa-angle-left"></i></a>
	<a class="serv-next oc-right"><i class="arrow-control fa fa-angle-right"></i></a>

</div>
<script>
jQuery(function($){

	var owl = $('#<?php echo $id;?>');
	
	owl.owlCarousel({	

		autoPlay : <?php echo (($autoplay == '1')? 'true' : 'false');?>,
	
		navigation : <?php echo (($navigation == '1' )? 'true' : 'false');?>,

		pagination:<?php echo (($pagination == '1' )? 'true' : 'false');?>,

	<?php if((int)$slideperview == 1) :?>
		singleItem : true,
	<?php else: ?>
		items : <?php echo (int)$slideperview;?>,
	<?php endif;?>

	<?php if(!empty($itemscustom)): ?>
		itemsCustom : <?php echo $itemscustom;?>,
	<?php endif;?>	

	<?php if(!empty($sliderspeed)): ?>
		slideSpeed : <?php echo $sliderspeed;?>,
	<?php endif;?>	

	<?php if(!empty($paginationspeed)): ?>
		paginationSpeed : <?php echo $paginationspeed;?>,
	<?php endif;?>

	<?php if(!empty($rewindspeed)): ?>
		rewindSpeed : <?php echo $rewindspeed;?>,
	<?php endif;?>

		autoHeight : <?php echo (($autoheight == '1' )? 'true' : 'false');?>,
		mouseDrag:	<?php echo (($mousedrag == '1' )? 'true' : 'false');?>,	
		touchDrag:<?php echo (($touchdrag == '1' )? 'true' : 'false');?>,
	<?php if(!empty($transtyle)): ?>
		transitionStyle : "<?php echo $transtyle;?>"
	<?php endif;?>		   
	});

		// Custom navigation
		$(".serv-next").click(function(){
			owl.trigger('owl.next');
		})
		$(".serv-prev").click(function(){
			owl.trigger('owl.prev');
		})
})
</script>

