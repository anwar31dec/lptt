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

$classes = 'azp_panel panel panel-default';
if(!empty($class)){
	$classes .= ' '.$class;
}

$classes = 'class="'.$classes.'"';
 
if(empty($id)){
	$id = uniqid('AccordionID');
}

//$id = 'id="'.$id.'"';
$accordionItem
?>

<div <?php echo $classes.' '.$accordionitemstyle;?>>
    <div class="panel-heading">
      <div class="panel-title">
        <a class="azp_accordion-toggle <?php if($accordionItem == 0) echo ' active';?>"  href="#<?php echo $id;?>">
          <h3 class="montserrat">

  			     <?php echo preg_replace('/--([^-]*)--/', '<span class="serifItalic elegantBrown">$1</span>', $title);?>
          </h3>
              
            <?php if(!empty($subtitle)):?>
              <p class="serifItalic"><?php echo $subtitle;?></p>
            <?php endif;?>

          
        </a>
      </div>
    </div>
    <div id="<?php echo $id;?>" class="azp_panel-collapse azp_hidden panel-collapse">
      <div class="panel-body">
        <p><?php echo nl2br(do_shortcode($content));?></p>
      </div>
    </div>
</div>
