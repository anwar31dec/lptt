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
    ?>
    <?php if($key == 0):?>
        <div class="one animate" data-anim-type="fadeInUp" data-anim-delay="200">

            <a href="<?php echo $item->link;?>">
                <img src="<?php echo JURI::base(true).$resizer->resize(str_replace(JURI::base(true),'',$item->imageXLarge),array('w'=>260,'h'=>192,'crop'=>true)); ?>" class="img_left"  alt="<?php echo $item->title;?>" >
                <p><?php echo JHtml::_('string.truncate',strip_tags($item->introtext),'100'); ?> <strong>- <?php echo JHtml::_('date',$item->created,'F d, Y');?></strong></p>
            </a>
            
        </div><!-- end section -->
    <?php elseif($key == 1) :?>

        <div class="two animate" data-anim-type="fadeInUp" data-anim-delay="250">
    
            <a href="<?php echo $item->link;?>">
                <img src="<?php echo JURI::base(true).$resizer->resize(str_replace(JURI::base(true),'',$item->imageXLarge),array('w'=>618,'h'=>192,'crop'=>true)); ?>" class="img_left"  alt="<?php echo $item->title;?>" >
                <p><?php echo JHtml::_('string.truncate',strip_tags($item->introtext),'100'); ?> <strong>- <?php echo JHtml::_('date',$item->created,'F d, Y');?></strong></p>
            </a>
            
        </div><!-- end section -->
    <?php elseif($key == 2) :?>
        <div class="three lessmar animate" data-anim-type="fadeInUp" data-anim-delay="300">
    
            <a href="<?php echo $item->link;?>">
                <img src="<?php echo JURI::base(true).$resizer->resize(str_replace(JURI::base(true),'',$item->imageXLarge),array('w'=>260,'h'=>192,'crop'=>true)); ?>" class="img_left"  alt="<?php echo $item->title;?>" >
                <p><?php echo JHtml::_('string.truncate',strip_tags($item->introtext),'100'); ?> <strong>- <?php echo JHtml::_('date',$item->created,'F d, Y');?></strong></p>
            </a>
            
        </div><!-- end section -->
    <?php elseif($key == 3) :?>

        <div class="three animate" data-anim-type="fadeInUp" data-anim-delay="350">
    
            <a href="<?php echo $item->link;?>">
                <img src="<?php echo JURI::base(true).$resizer->resize(str_replace(JURI::base(true),'',$item->imageXLarge),array('w'=>256,'h'=>192,'crop'=>true)); ?>" class="img_left"  alt="<?php echo $item->title;?>" >
                <p><?php echo JHtml::_('string.truncate',strip_tags($item->introtext),'100'); ?> <strong>- <?php echo JHtml::_('date',$item->created,'F d, Y');?></strong></p>
            </a>
            
        </div><!-- end section -->
    <?php elseif($key == 4) :?>

        <div class="three animate" data-anim-type="fadeInUp" data-anim-delay="400">
    
            <a href="<?php echo $item->link;?>">
                <img src="<?php echo JURI::base(true).$resizer->resize(str_replace(JURI::base(true),'',$item->imageXLarge),array('w'=>256,'h'=>192,'crop'=>true)); ?>" class="img_left"  alt="<?php echo $item->title;?>" >
                <p><?php echo JHtml::_('string.truncate',strip_tags($item->introtext),'100'); ?> <strong>- <?php echo JHtml::_('date',$item->created,'F d, Y');?></strong></p>
            </a>
            
        </div><!-- end section -->
    <?php elseif($key == 5) :?>

        <div class="four animate" data-anim-type="fadeInUp" data-anim-delay="450">
    
            <a href="<?php echo $item->link;?>">
                <img src="<?php echo JURI::base(true).$resizer->resize(str_replace(JURI::base(true),'',$item->imageXLarge),array('w'=>350,'h'=>192,'crop'=>true)); ?>" class="img_left"  alt="<?php echo $item->title;?>" >
                <p><?php echo JHtml::_('string.truncate',strip_tags($item->introtext),'100'); ?> <strong>- <?php echo JHtml::_('date',$item->created,'F d, Y');?></strong></p>
            </a>
            
        </div><!-- end section -->
    <?php else :?>
        <div class="one animate" data-anim-type="fadeInUp" data-anim-delay="200">
    
            <a href="<?php echo $item->link;?>">
                <img src="<?php echo JURI::base(true).$resizer->resize(str_replace(JURI::base(true),'',$item->imageXLarge),array('w'=>260,'h'=>192,'crop'=>true)); ?>" class="img_left"  alt="<?php echo $item->title;?>" >
                <p><?php echo JHtml::_('string.truncate',strip_tags($item->introtext),'100'); ?> <strong>- <?php echo JHtml::_('date',$item->created,'F d, Y');?></strong></p>
            </a>
            
        </div><!-- end section -->
    <?php endif;?>

        <?php endforeach;?>

<?php endif;?>
