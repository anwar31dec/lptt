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
<link href='<?php echo CthShortcodes::templateUri();?>/js/masterslider/ms-laptop-style.css' rel='stylesheet' type='text/css'>
<script src="<?php echo CthShortcodes::templateUri();?>/js/masterslider/jquery.easing.min.js"></script>
<script src="<?php echo CthShortcodes::templateUri();?>/js/masterslider/masterslider.min.js"></script>

<div class="ms-laptop-template">
    <div class="ms-laptop-cont">
        <img src="<?php echo JURI::root(true);?>/images/sliders/master/laptop.png" class="ms-laptop-bg" alt="" />
        <div class="ms-lt-slider-cont">
            <!-- masterslider -->
            <div <?php echo $classes.' '.$mastersliderstyle.' '.$animationData;?> id="<?php echo $id;?>">

                <?php echo do_shortcode($content);?>
             
            </div>
            <!-- end of masterslider -->
        </div>
    </div>
</div>


<script>
(function($) {
 "use strict";

    var slider4 = new MasterSlider();
    slider4.setup('<?php echo $id;?>' , {
        width:530,
        height:335,
        speed:20,
        preload:0,
        space:2,
        view:'mask'
    });
    slider4.control('arrows');  
    slider4.control('bullets',{autohide:false});    

    
    $('#myTab a').click(function (e) {
        e.preventDefault()
        $(this).tab('show')
    });

})(jQuery);

</script>

