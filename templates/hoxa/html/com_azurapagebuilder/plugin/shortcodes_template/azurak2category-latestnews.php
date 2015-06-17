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

$classes = 'azp_k2category recentnews azp_font_edit';

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

// require_once JPATH_ROOT.'/plugins/system/cthshortcodes/core/cthimageresizer.php';
// $resizer = CTHImageOptimizerHelper::getInstance();

?>
<?php if(count($items)) : ?>

    <ul <?php echo $classes.' '.$k2categorystyle.' '.$animationData;?>>
    <?php foreach ($items as $key => $item) : //echo'<pre>',var_dump($item);die;

        //$extraFields = json_decode($item->extra_fields);
    ?>
        <ul class="news2">
            <li class="date"><strong><?php echo JHtml::_('date',$item->created,'d');?></strong> <?php echo JHtml::_('date',$item->created,'M');?></li>
            <li class="text"><h5><?php echo $item->title;?></h5> 
            <?php if($item->numOfComments > 0): ?>
            <a href="<?php echo $this->item->link; ?>#itemCommentsAnchor">
                <?php echo $item->numOfComments; ?> <?php echo ($item->numOfComments>1) ? JText::_('K2_COMMENTS') : JText::_('K2_COMMENT'); ?>
                </a>
            <?php else: ?>
            <a href="<?php echo $item->link; ?>#itemCommentsAnchor">
                <?php echo JText::_('K2_BE_THE_FIRST_TO_COMMENT'); ?>
            </a>
            <?php endif; ?>
            <p><?php echo JHtml::_('string.truncate',strip_tags($item->introtext),'150');?></p>
            <p><a href="<?php echo $item->link; ?>"><?php echo JText::_('TPL_HOXA_READ_MORE_TEXT');?></a></p>
            </li>
        </ul>
         
        <?php endforeach;?>

    </ul>

<?php endif;?>
