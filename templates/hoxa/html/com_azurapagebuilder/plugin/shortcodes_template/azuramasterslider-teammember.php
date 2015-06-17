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
<link href='<?php echo CthShortcodes::templateUri();?>/js/masterslider/style/ms-staff-style.css' rel='stylesheet' type='text/css'>
<script src="<?php echo CthShortcodes::templateUri();?>/js/masterslider/jquery.easing.min.js"></script>
<script src="<?php echo CthShortcodes::templateUri();?>/js/masterslider/masterslider.min.js"></script>

<div class="ms-staff-carousel ms-round">
    <!-- masterslider -->
    <div <?php echo $classes.' '.$mastersliderstyle.' '.$animationData;?> id="<?php echo $id;?>">

        <?php echo do_shortcode($content);?>
     
    </div>
    <!-- end of masterslider -->
    <div class="ms-staff-info" id="staff-info"></div>
</div>


<script>
(function($) {
 "use strict";

    var slider5 = new MasterSlider();
    slider5.setup('<?php echo $id;?>' , {
        loop:true,
        width:240,
        height:240,
        speed:20,
        view:'focus',
        preload:0,
        space:35,
        viewOptions:{centerSpace:1.6}
    });
    slider5.control('arrows');
    slider5.control('slideinfo',{insertTo:'#staff-info'});

})(jQuery);

</script>

