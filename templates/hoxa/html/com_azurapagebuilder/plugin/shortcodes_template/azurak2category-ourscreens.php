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

$classes = 'azp_k2category cbp-l-filters-alignCenter azp_font_edit';

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
        <div id="filters-container" <?php echo $classes.' '.$k2categorystyle.' '.$animationData;?>>
            <button data-filter="*" class="cbp-filter-item-active cbp-filter-item"><?php echo JText::_('TPL_HOXA_FILTER_ALL_TEXT');?></button>
            <?php if(isset($tagsFilter) && count($tagsFilter)): 
                foreach($tagsFilter as $key=>$tag): 
            ?>
            <button class="cbp-filter-item" data-filter=".<?php echo strtolower(str_replace(" ","-",$tag)); ?>"><?php echo ucfirst($tag); ?></button>
            <?php endforeach; endif;?>
        </div>
        
    <?php endif;?>

    <div id="grid-container" class="cbp-l-grid-fullScreen">
                <ul>
                <?php foreach ($items as $key => $item) : //echo'<pre>',var_dump($item);die;

                    $extraFields = json_decode($item->extra_fields);
                ?>
                <li class="cbp-item <?php echo CthShortcodes::getK2ItemTagsFilter($item);?>">
                        <div class="cbp-caption">
                            <div class="cbp-caption-defaultWrap">
                                <img src="<?php echo JURI::root(true).$extraFields[0]->value;?>" alt="<?php echo $item->title;?>"  />
                            </div>
                            <div class="cbp-caption-activeWrap">
                                <div class="cbp-l-caption-alignCenter">
                                    <div class="cbp-l-caption-body">
                                        <a href="<?php echo CthShortcodes::getK2ItemLink($item->id,$item->alias,$item->catid,$item->categoryalias);?>?tmpl=component" class="cbp-singlePage cbp-l-caption-buttonLeft"><?php echo JText::_('TPL_HOXA_MORE_INFO_TEXT');?></a>
                                        <a href="<?php echo JURI::root(true).$extraFields[3]->value;?>" class="cbp-lightbox cbp-l-caption-buttonRight" data-title="<?php echo $item->title;?><br><?php echo JText::_('TPL_HOXA_BY_TEXT');?> <?php echo (!empty($item->created_by_alias)? $item->created_by_alias : $item->created_by);?>"><?php echo JText::_('TPL_HOXA_VIEW_LARGER_TEXT');?></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="cbp-l-grid-projects-title"><?php echo $item->title;?></div>
                        <div class="cbp-l-grid-projects-desc"><span class="cbp-l-grid-projects-inlineFilters" data-filter=".web-design">Web Design</span> / <span class="cbp-l-grid-projects-inlineFilters" data-filter=".graphic">Graphic</span></div>
                </li>

                    
                    <?php endforeach;?>

                </ul>
            </div>
          <div class="cbp-l-loadMore-text">
            <div data-href="#" class="cbp-l-loadMore-text-link"></div>
          </div>

<?php endif;?>
