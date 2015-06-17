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

    <!-- <ul <?php echo $classes.' '.$k2categorystyle.' '.$animationData;?>> -->
    <?php foreach ($items as $key => $item) : //echo'<pre>',var_dump($item);die;

        //$extraFields = json_decode($item->extra_fields);
        $lastclass = '';
        if(($key+1) % 3 == 0){
            $lastclass = ' last';
        }
    ?>
    <div class="one_third <?php echo $lastclass;?> animate" data-anim-type="fadeInUp">
    
        <h4 class="white"><?php echo $item->title;?></h4>
        
        <img src="<?php echo JURI::base(true).$resizer->resize(str_replace(JURI::base(true),'',$item->imageMedium),array('w'=>361,'h'=>180,'crop'=>true)); ?>" alt="<?php echo $item->title;?>" class="img_left1" />
        
        <p><?php echo JHtml::_('string.truncate',strip_tags($item->introtext),'150');?></p>
        
        <br />
        
        <a href="<?php echo $item->link;?>" class="lfour"><i class="fa fa-chevron-circle-right"></i>&nbsp; <?php echo JText::_('TPL_HOXA_READ_MORE2_TEXT');?></a>
        
    </div>
        
         
        <?php endforeach;?>

    <!-- </ul> -->

<?php endif;?>
