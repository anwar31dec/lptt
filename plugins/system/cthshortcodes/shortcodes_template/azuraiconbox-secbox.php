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

$class .' secbox';

if(!empty($class)){
	$class = 'class="'.$class.'"';
}
 
if(!empty($id)){
	$id = 'id="'.$id.'"';
}


?>
<div <?php echo $id.' '.$class.' '.$iconboxstyle.' '.$animationData;?> >
        
    <i class="<?php echo $iconclass;?>"></i>
    <div class="clearfix"></div>
    <h4><?php echo $title;?></h4>
    <?php echo nl2br(do_shortcode($content));?>
        
</div>