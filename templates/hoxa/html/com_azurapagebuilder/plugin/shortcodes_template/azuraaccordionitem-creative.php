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

$classes = 'azp_panel acc-trigger';
if(!empty($class)){
	$classes .= ' '.$class;
}

$animationData = '';
if($animationArgs['animation'] == '1'){
  $classes .= ' '.$animationArgs['trigger'];
  if(!empty($animationArgs['animationtype'])){
    $animationData .= 'data-anim-type="'.$animationArgs['animationtype'].'"';
  }
  if(!empty($animationArgs['animationdelay'])){
    $animationData .= ' data-anim-delay="'.$animationArgs['animationdelay'].'"';
  }
}

if($accordionItem == 0){
  $classes .=' active';
}


$classes = 'class="'.$classes.'"';
 
if(empty($id)){
	$id = uniqid('AccordionID');
}

//$id = 'id="'.$id.'"';
$accordionItem
?>

<span <?php echo $classes.' '.$accordionitemstyle.' '.$animationData;?>><a href="#"><?php echo $title;?></a></span>
<div class="acc-container">
  <div class="content">
      <p><?php echo nl2br(do_shortcode($content));?></p>
  </div>
</div>

