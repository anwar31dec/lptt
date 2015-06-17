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

    <?php $article1 = $article2 = $article3 = $article4 = $article5 = ''; ?>


    <!-- <ul <?php echo $classes.' '.$k2categorystyle.' '.$animationData;?>> -->
    <?php foreach ($items as $key => $item) : //echo'<pre>',var_dump($item);die;

        //$extraFields = json_decode($item->extra_fields);
        // $lastclass = '';
        // if(($key+1) % 3 == 0){
        //     $lastclass = ' last';
        // }
    ?>
    <?php if($key == 0) :?>

    <?php $article1 = '<div class="bnbox">
        
            <h5>'.$item->title.'</h5>
            
            <em>'.JHtml::_('date',$item->created, 'F d, Y').'</em>
            
            <p>'.JHtml::_('string.truncate',strip_tags($item->introtext),'100').'</p>
            <br />
            <a href="'.$item->link.'" class="readmore_but7">'.JText::_('TPL_HOXA_READ_MORE2_TEXT').'</a>
        
        </div><!-- end section -->'; ?>

    <?php elseif($key == 1) :?>

        <?php $article2 = '<div class="bnbox two">
            
            <img src="'.JURI::base(true).$resizer->resize(str_replace(JURI::base(true),'',$item->imageMedium),array('w'=>361,'h'=>180,'crop'=>true)).'" alt="'.$item->title.'" class="img_left" />
            
            <div class="bnbox three">
            
            <h5>'.$item->title.'</h5>
            
            <em>'.JHtml::_('date',$item->created, 'F d, Y').'</em>
            
            <p>'.JHtml::_('string.truncate',strip_tags($item->introtext),'120').'</p>
            <br />
            <a href="'.$item->link.'" class="readmore_but7">'.JText::_('TPL_HOXA_READ_MORE2_TEXT').'</a>
            
            </div>
        
        </div><!-- end section -->'; ?>

    <?php elseif($key == 2) :?>

        <?php $article3 = '<div class="bnbox">
        
            <h5>'.$item->title.'</h5>
            
            <em class="less">'.JHtml::_('date',$item->created, 'F d, Y').'</em>
            
            <br />
            <a href="'.$item->link.'" class="readmore_but7">'.JText::_('TPL_HOXA_READ_MORE2_TEXT').'</a>

        
        </div><!-- end section -->'; ?>

    <?php elseif($key == 3) :?>

        <?php $article4 = '<div class="clearfix margin_top2"></div>
        
        <div class="bnbox">
        
            <h5>'.$item->title.'</h5>
            
            <em class="less">'.JHtml::_('date',$item->created, 'F d, Y').'</em>
            
            <br />
            <a href="'.$item->link.'" class="readmore_but7">'.JText::_('TPL_HOXA_READ_MORE2_TEXT').'</a>
        
        </div><!-- end section -->'; ?>

    <?php elseif($key == 4) :?>

        <?php $article5 = '<div class="clearfix margin_top2"></div>
        
        <div class="bnbox">
        
            <h5>'.$item->title.'</h5>
            
            <em>'.JHtml::_('date',$item->created, 'F d, Y').'</em>
            
            <p>'.JHtml::_('string.truncate',strip_tags($item->introtext),'100').'</p>
            <br />
            <a href="'.$item->link.'" class="readmore_but7">'.JText::_('TPL_HOXA_READ_MORE2_TEXT').'</a>
        
        
        </div><!-- end section -->'; ?>

    <?php endif;?>
    
        <?php endforeach;?>

    <div class="one_third">
        <?php echo $article1;?>
        <?php echo $article4;?>
    </div>

    <div class="one_third">
        <?php echo $article2;?>
    </div>

    <div class="one_third last">
        <?php echo $article3;?>
        <?php echo $article5;?>
    </div>


<?php endif;?>
