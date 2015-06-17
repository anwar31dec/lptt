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

$classes = 'azp_k2category cbp-l-filters-button azp_font_edit';

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

?>
<?php if(count($items)) : ?>
<!-- portfolio filters -->
    <?php if($showfilter == '1') :?>
    <div class="container">
        <div id="filters-container" <?php echo $classes.' '.$k2categorystyle.' '.$animationData;?>>
            <div data-filter="*" class="cbp-filter-item-active cbp-filter-item"><?php echo JText::_('TPL_HOXA_FILTER_ALL_TEXT');?></div>
            <?php if(isset($tagsFilter) && count($tagsFilter)): 
                foreach($tagsFilter as $key=>$tag): 
            ?>
            <div class="cbp-filter-item" data-filter=".<?php echo strtolower(str_replace(" ","-",$tag)); ?>"><?php echo ucfirst($tag); ?></div>
            <?php endforeach; endif;?>
        </div>
    </div>
    <?php endif;?>

    <div class="container_full animate" data-anim-type="fadeInUp">
        <div id="grid-container" class="cbp-l-grid-fullScreen">
            <ul>
                <?php foreach ($items as $key => $item) : //echo'<pre>',var_dump($item);die;

                    $extraFields = json_decode($item->extra_fields);
                ?>
                <li class="cbp-item <?php echo CthShortcodes::getK2ItemTagsFilter($item);?>">
                <?php if($extraFields[0]->value == '1'): ?>
                    <a href="<?php echo JURI::root(true).$extraFields[1]->value;?>" class="cbp-caption cbp-lightbox" data-title="<?php echo $item->title;?><br><?php echo JText::_('TPL_HOXA_BY_TEXT');?> <?php echo (!empty($item->created_by_alias)? $item->created_by_alias : $item->created_by);?>">
                <?php elseif($extraFields[0]->value == '2'|| $extraFields[0]->value == '3'): ?>
                    <a href="<?php echo $extraFields[1]->value;?>" class="cbp-caption cbp-lightbox" data-title="<?php echo $item->title;?><br><?php echo JText::_('TPL_HOXA_BY_TEXT');?> <?php echo (!empty($item->created_by_alias)? $item->created_by_alias : $item->created_by);?>">
                <?php endif;?>
                        <div class="cbp-caption-defaultWrap">
                            <img src="<?php echo JURI::root(true).$extraFields[2]->value;?>" alt="<?php echo $item->title;?>" />
                        </div>
                        <div class="cbp-caption-activeWrap">
                            <div class="cbp-l-caption-alignLeft">
                                <div class="cbp-l-caption-body">
                                    <div class="cbp-l-caption-title"><?php echo $item->title;?></div>
                                    <div class="cbp-l-caption-desc"><?php echo JText::_('TPL_HOXA_BY_TEXT');?> <?php echo (!empty($item->created_by_alias)? $item->created_by_alias : $item->created_by);?></div>
                                </div>
                            </div>
                        </div>
                    </a>
                </li>

                    
                    <?php endforeach;?>

                </ul>
            </div>
        <div class="cbp-l-loadMore-text">
            <div data-href="#" class="cbp-l-loadMore-text-link"></div>
        </div>
    </div>
<?php endif;?>
