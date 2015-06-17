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

$classes = "azp_member azp_font_edit";

$animationData = '';
if($animationArgs['animation'] == '1'){
    if($animationArgs['trigger'] == 'animate-in'){
        $classes .= ' '.$animationArgs['trigger'];
        $animationData = 'data-anim-type="'.$animationArgs['animationtype'].'" data-anim-delay="'.$animationArgs['animationdelay'].'"';
    }else{
        $classes .= ' '.$animationArgs['trigger'].'-'.$animationArgs['hoveranimationtype'];
        if($animationArgs['infinite'] != '0'){
            $classes .= ' infinite';
        }
    }
    
    
}

if(!empty($class)){
	$classes .= ' '.$class;
}

$classes = 'class="'.$classes.'"';
 
if(!empty($id)){
	$id = 'id="'.$id.'"';
}

?>

<div <?php echo $id.' '.$classes.' '.$teammemberstyle.' '.$animationData;?>>
    <img src="<?php echo JURI::root(true).'/'.$photo;?>" alt="team member" class="azp_responsive-img">
    <div class="azp_member_caption text-center">
        <h3 ><?php echo $name;?></h3>
        <?php if(!empty($position)) :?>
        <p>
            <span class="azp_member_position"><?php echo $position;?></span>
        </p>
    	<?php endif;?>

        <?php if(!empty($introduction)) :?>

        <p>
            <?php echo nl2br($introduction);?>
        </p>
    	<?php endif;?>
    	<?php if(!empty($twitter)||!empty($facebook)||!empty($dribbble)||!empty($linkedin)||!empty($googleplus)) :?>
        <ul class="azp_member_socials list-inline">
        <?php if(!empty($twitter)) :?>
            <li class="azp_member_social">
                <a href="<?php echo $twitter;?>"><i class="fa fa-twitter"></i></a>
            </li>
            
        <?php endif;?>
        <?php if(!empty($facebook)) :?>
            <li class="azp_member_social">
                <a href="<?php echo $facebook;?>"><i class="fa fa-facebook"></i></a>
            </li>
            
        <?php endif;?>
        <?php if(!empty($dribbble)) :?>
            <li class="azp_member_social">
                <a href="<?php echo $dribbble;?>"><i class="fa fa-dribbble"></i></a>
            </li>
            
        <?php endif;?>
        <?php if(!empty($linkedin)) :?>
            <li class="azp_member_social">
                <a href="<?php echo $linkedin;?>"><i class="fa fa-linkedin"></i></a>
            </li>
            
        <?php endif;?>
        <?php if(!empty($googleplus)) :?>
            <li class="azp_member_social">
                <a href="<?php echo $googleplus;?>"><i class="fa fa-google-plus"></i></a>
            </li>
            
        <?php endif;?>
        </ul>
        <?php endif;?>
    </div>
</div>