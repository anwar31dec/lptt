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
        if($key % 2 == 1){
            $lastclass = ' last';
        }
    ?>
        <div class="one_half<?php echo $lastclass;?>">
            
            <ul class="blogs2">
                <li><a href="<?php echo $item->link;?>"><img src="<?php echo JURI::base(true).$resizer->resize(str_replace(JURI::base(true),'',$item->imageMedium),array('w'=>270,'h'=>210,'crop'=>true)); ?>" alt="<?php echo $item->title;?>" /></a></li>
                <li><h5><a href="<?php echo $item->link;?>" class="two"><?php echo $item->title;?></a></h5></li>
                <li><i class="fa fa-clock-o"></i> <?php echo JHtml::_('date',$item->created,'M d, Y');?> &nbsp;&nbsp;<i class="fa fa-user"></i> <?php echo (!empty($item->created_by_alias)? $item->created_by_alias : $item->created_by);?> &nbsp;&nbsp;<i class="fa fa-comment"></i> <?php echo $item->numOfComments;?></li>
            </ul>
              
        </div>
         
        <?php endforeach;?>

    <!-- </ul> -->

<?php endif;?>
