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

$classes = "azp_counter onepage-counter";

if(!empty($class)){
	$classes .= ' '.$class;
}

$classes = 'class="'.$classes.'"';
 
if(!empty($id)){
	$id = 'id="'.$id.'"';
}

$ele_data = 'data-countto="'.$stopvalue.'" data-speed="'.$speed.'"';

// $animationData = '';
// if($animationArgs['animation'] == '1'){
// 	$columnwidthclass .= ' '.$animationArgs['trigger'];
// 	$animationData = 'data-anim-type="'.$animationArgs['animationtype'].'" data-anim-delay="'.$animationArgs['animationdelay'].'"';
// }


?>
<span <?php echo $id.' '.$classes.' '.$counterstyle.' '.$ele_data;?>>0</span> <h4><?php echo $unit;?></h4>
