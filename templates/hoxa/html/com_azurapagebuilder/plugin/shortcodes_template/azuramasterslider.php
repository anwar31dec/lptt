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
	$id = uniqid('masterslider');
}

$classes = 'azp-masterslider master-slider';
$animationData = '';
if($animationArgs['animation'] == '1'){
	$classes .= ' '.$animationArgs['trigger'];
	$animationData = 'data-anim-type="'.$animationArgs['animationtype'].'" data-anim-delay="'.$animationArgs['animationdelay'].'"';
}
if (!empty($class)) {
	$classes .= ' '.$class;
}
if(!empty($skin)&&$skin != 'custom'){
	$classes .= ' '.$skin;
}

$classes = 'class="'.$classes.'"';

?>

<!-- masterslider -->
<div <?php echo $classes.' '.$mastersliderstyle.' '.$animationData;?> id="<?php echo $id;?>">
    

    <?php echo do_shortcode($content);?>
 
</div>
<!-- end of masterslider -->

<script src="<?php echo CthShortcodes::templateUri();?>/js/masterslider/masterslider.min.js"></script>

<script>
    var <?php echo $id;?>_slider = new MasterSlider();
    <?php echo $id;?>_slider.setup('<?php echo $id;?>' , {
            width:800,    // slider standard width
            height:350,   // slider standard height
            space:5
            // more slider options goes here...
            // check slider options section in documentation for more options.
        });
    // adds Arrows navigation control to the slider.
    <?php echo $id;?>_slider.control('arrows');
</script>

