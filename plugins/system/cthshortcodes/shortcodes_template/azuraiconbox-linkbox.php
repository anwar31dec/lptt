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
$animationData = '';
if($animationArgs['animation'] == '1'){
	$class .= ' '.$animationArgs['trigger'];
	if(!empty($animationArgs['animationtype'])){
		$animationData .= 'data-anim-type="'.$animationArgs['animationtype'].'"';
	}
	if(!empty($animationArgs['animationdelay'])){
		$animationData .= ' data-anim-delay="'.$animationArgs['animationdelay'].'"';
	}
}

if(!empty($class)){
	$class = 'class="'.$class.'"';
}
 
if(!empty($id)){
	$id = 'id="'.$id.'"';
}


?>
<?php if(!empty($link)):?>
<a href="<?php echo $link;?>" <?php echo $id.' '.$class.' '.$iconboxstyle.' '.$animationData;?>>
<?php endif;?>
    <div class="cbp-caption-defaultWrap">
        <div class="cibox"><i class="<?php echo $iconclass;?>"></i> <h5><?php echo $title;?></h5></div>
    </div>
    <div class="cbp-caption-activeWrap">
        <div class="cbp-l-caption-alignLeft">
            <div class="cbp-l-caption-body">
                <div class="cibox act">
                <i class="<?php echo $iconclass;?>"></i>
                <h5><?php echo $title;?></h5>
                <?php echo nl2br(do_shortcode($content));?>
                </div>
            </div>
        </div>
    </div>
<?php if(!empty($link)):?>
</a>
<?php endif;?>