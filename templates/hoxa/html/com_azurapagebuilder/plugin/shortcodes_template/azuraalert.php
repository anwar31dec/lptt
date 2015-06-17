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

//$classes = "azp_alert alert";
$classes = strtolower($type);

if(!empty($class)){
	$classes .= ' '.$class;
}

// if($fadeeffect !== '0'){
// 	$classes .= ' fade in';
// }

if($closebtn == '1'){
	$classes .= ' style-box';
}

$classes = 'class="'.$classes.'"';
 
if(!empty($id)){
	$id = 'id="'.$id.'"';
}
?>
<div <?php echo $id.' '.$classes;?>>
    <div class="message-box-wrap">
    <?php if($closebtn !== '0'): ?>
	   	<button class="close-but">close</button>
	<?php endif;?> <?php echo nl2br(do_shortcode($content));?>
	</div>
</div>