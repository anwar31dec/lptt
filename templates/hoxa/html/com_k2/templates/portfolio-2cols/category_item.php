<?php
/**
 * @package Hoxa - Responsive Multipurpose Joomla Template
 * @author Cththemes - www.cththemes.com
 * @date: 01-10-2014
 *
 * @copyright  Copyright ( C ) 2014 cththemes.com . All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

// no direct access
defined('_JEXEC') or die;

// Define default image size (do not change)
//K2HelperUtilities::setDefaultImage($this->item, 'itemlist', $this->params);

$extraFields = json_decode($this->item->extra_fields);

$postType = $extraFields[2]->value;
//$postLink = $extraFields[3]->value;

$filter = getItemTagsFilter($this->item);
?>

<li class="cbp-item <?php echo $filter;?>">
    <div class="cbp-caption">
        <div class="cbp-caption-defaultWrap">
            <img src="<?php echo JURI::root(true).$extraFields[0]->value;?>" alt="<?php echo $this->item->title;?>" />
        </div>
        <div class="cbp-caption-activeWrap">
            <div class="cbp-l-caption-alignCenter">
                <div class="cbp-l-caption-body">
                    <a href="<?php echo $this->item->link;?>" class="cbp-l-caption-buttonLeft"><?php echo JText::_('TPL_HOXA_MORE_INFO_TEXT');?></a>
                    <a href="<?php echo JURI::root(true).$extraFields[3]->value;?>" class="cbp-lightbox cbp-l-caption-buttonRight" data-title="<?php echo $this->item->title;?><br><?php echo JText::_('TPL_HOXA_BY_TEXT');?> <?php echo $this->item->author->name;?>"><?php echo JText::_('TPL_HOXA_VIEW_LARGER_TEXT');?></a>
                </div>
            </div>
        </div>
    </div>
    <div class="threeborder"><div class="cbp-l-grid-projects-title"><?php echo $this->item->title;?></div><div class="cbp-l-grid-projects-desc"><?php echo $extraFields[1]->value;?></div></div>
</li>

