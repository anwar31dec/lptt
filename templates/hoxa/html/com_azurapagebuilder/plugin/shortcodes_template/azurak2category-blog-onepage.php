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

$classes = 'azp_k2category azp_font_edit';

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


if(!empty($class)){
	$classes .= ' '.$class;
}

$classes = 'class="'.$classes.'"';

require_once JPATH_ROOT.'/plugins/system/cthshortcodes/core/cthimageresizer.php';
$resizer = CTHImageOptimizerHelper::getInstance();

?>
<?php if(count($items)) : ?>
<?php //echo $classes.' '.$k2categorystyle.' '.$animationData;?>
    
    <?php foreach ($items as $key => $item) : //echo'<pre>',var_dump($item);die;

        //$extraFields = json_decode($item->extra_fields);
        $lastclass = '';
        if(($key+1) % 3 == 0){
            $lastclass = ' last';
        }
    ?>

        <div class="one_third<?php echo $lastclass;?>">
        
        <img src="<?php echo JURI::base(true).$resizer->resize(str_replace(JURI::base(true),'',$item->imageXLarge),array('w'=>359,'h'=>300,'crop'=>true)); ?>"  alt="<?php echo $item->title;?>" class="wres" />
        
        <div class="cont">
        
        <h4><?php echo $item->title;?></h4>
        
        <p><?php echo JHtml::_('string.truncate',strip_tags($item->introtext),'75'); ?></p>
        <br />
        <a href="<?php echo $item->link;?>"><i class="fa fa-file-text"></i> <?php echo JHtml::_('date',$item->created,'d, F Y');?></a> &nbsp; <a href="<?php echo $item->link;?>#itemCommentsAnchor"><i class="fa fa-comment"></i> <?php echo $item->numOfComments;?> <?php echo ($item->numOfComments>1) ? JText::_('K2_COMMENTS') : JText::_('K2_COMMENT'); ?></a>
        
        </div>
    
    </div><!-- end section -->

        <?php endforeach;?>

<?php endif;?>
