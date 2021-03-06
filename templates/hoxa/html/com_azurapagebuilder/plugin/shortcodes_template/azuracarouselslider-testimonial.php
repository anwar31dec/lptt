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
<!-- Testimonials slider -->
<div id="<?php echo $id;?>" <?php echo $classes;?>>
	<?php echo do_shortcode($content);?>
</div>

<script>
jQuery(function($){

	$("#<?php echo $id;?>").owlCarousel({
        items:1,
        loop:true,
        autoplay:true,
        autoHeight: false,
        autoHeightClass: 'owl-height',
        dots:false,
        nav:true,
        navText:[
            "<i class='fa fa-angle-left fa-2x'></i>",
            "<i class='fa fa-angle-right fa-2x'></i>"
        ]
    });
})
</script>

