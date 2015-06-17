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
<!-- Featured works slider -->
<div class="row" >
    <div id="<?php echo $id;?>" <?php echo $classes;?>>
        <?php echo do_shortcode($content);?>
    </div>
    <script>
    jQuery(function($){

        $("#<?php echo $id;?>").owlCarousel({
            items:4,
            loop:true,
            autoplay:true,
            responsive : {
                // breakpoint from 0 up
                0 : {
                    items:1
                },
                // breakpoint from 480 up
                480 : {
                    items:2
                },
                // breakpoint from 768 up
                768 : {
                    items:3
                }
            },
            nav:false,
            margin:20
        });
    })
    </script>
</div>


