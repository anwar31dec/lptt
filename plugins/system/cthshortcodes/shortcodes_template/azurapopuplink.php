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

$classes = $popuptype.' azp_font_edit';

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

<a <?php echo $id . ' ' .$classes.' '.$animationData.' '.$popuplinkstyle;?> href="<?php echo JURI::root(true).'/'.$popuplink;?>" title="<?php echo $title;?>">
    <!-- <div class="caption text-center ">
        <div class="captionWrapper valign">

            <div class="caption-heading">
                <h2 class="serif">+</h2>
                <?php if(!empty($title)) :?>
                <h4 class="montserrat"><?php echo $title;?></h4>
            	<?php endif;?>
            	<?php if(!empty($subtitle)) :?>
                <h4 class="serifItalic"><?php echo $subtitle;?></h4>
                <?php endif;?>
            </div>

        </div>
    </div> -->
    <img src="<?php echo JURI::root(true).'/'.$popuplink;?>" class="img-responsive" alt="<?php echo $title;?>">
</a>