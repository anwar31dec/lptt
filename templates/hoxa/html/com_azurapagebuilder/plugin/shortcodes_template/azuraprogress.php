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


$classes = "azp_progress ui-progress-bar ui-container";
// if(!empty($type)){
// 	$classes .= ' progress-bar-'.strtolower($type);
// }

if(!empty($class)){
	$classes .= ' '.$class;
}

$classes = 'class="'.$classes.'"';
 
if(!empty($id)){
	$id = 'id="'.$id.'"';
}

$prodata = 'data-value="'.$value.'" data-anivalue="'.$anivalue.'" data-speed="'.(int)$speed.'"';

?>
<h5 class="nocaps"><?php echo $title;?></h5>
<div <?php echo $id.' '.$classes;?>>
	<div class="ui-progress <?php echo $type;?>" <?php echo $progressstyle;?> <?php echo $prodata;?>><span class="ui-label"><b class="value"><?php echo $value;?>%</b></span></div>
</div>
