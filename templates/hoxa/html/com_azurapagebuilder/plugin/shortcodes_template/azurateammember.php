<?php
/**
 * @package Azura Joomla Pagebuilder
 * @author Cththemes - www.cththemes.com
 * @date: 15-07-2014
 *
 * @copyright  Copyright ( C ) 2014 cththemes.com . All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die();

$classes = "azp_member box";

if(!empty($class)){
	$classes .= ' '.$class;
}

$classes = 'class="'.$classes.'"';
 
if(!empty($id)){
	$id = 'id="'.$id.'"';
}

?>

<div <?php echo $id.' '.$classes;?>>
    <img src="<?php echo JURI::root(true).'/'.$photo;?>" alt="<?php echo $name;?>" class="rimg">      
    
    <h4><?php echo $name;?></h4>
    <h6 class="nocaps"><?php echo $position;?></h6>
    
    <p><?php echo nl2br($introduction);?></p>
    <br />
    <?php if(!empty($twitter)||!empty($facebook)||!empty($dribbble)||!empty($linkedin)||!empty($googleplus)) :?>
    <ul>
        <?php if(!empty($facebook)) :?>
            <li><a href="<?php echo $facebook;?>"><i class="fa fa-facebook"></i></a></li>
        <?php endif;?>
        <?php if(!empty($twitter)) :?>
            <li><a href="<?php echo $twitter;?>"><i class="fa fa-twitter"></i></a></li>
        <?php endif;?>
        <?php if(!empty($googleplus)) :?>
            <li><a href="<?php echo $googleplus;?>"><i class="fa fa-google-plus"></i></a></li>
        <?php endif;?>
        <?php if(!empty($dribbble)) :?>
            <li><a href="<?php echo $dribbble;?>"><i class="fa fa-dribbble"></i></a></li>
        <?php endif;?>
        <?php if(!empty($linkedin)) :?>
            <li><a href="<?php echo $linkedin;?>"><i class="fa fa-linkedin"></i></a></li>
        <?php endif;?>
    </ul>
    <?php endif;?> 
    
</div>