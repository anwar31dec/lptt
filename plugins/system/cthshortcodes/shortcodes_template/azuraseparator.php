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

$classes = "azp_separator azp_font_edit";

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

<?php if($wrapper =='hr') :?>
    <hr <?php echo $id. ' '.$classes.' '.$separatorstyle.' '.$animationData;?>>
<?php else: ?>
    <<?php echo $wrapper.' '.$id.' '.$classes.' '.$separatorstyle.' '.$animationData;?>>
    <?php if(!empty($iconclass)) :?>
        <i class="<?php echo $iconclass;?>" ></i>
    <?php elseif(!empty($text)) :?>
        <?php echo $text;?>
    <?php endif;?>
    <<?php echo $wrapper;?>

<?php endif;?>