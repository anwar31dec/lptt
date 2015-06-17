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


?>
<li class="cbp-item web-design">
    <div class="cbp-item-wrapper">
        <div class="cbp-caption">
            <div class="cbp-caption-defaultWrap">
                <img src="<?php echo JURI::root(true).$extraFields[0]->value;?>" alt="<?php echo $this->item->title;?>" />
            </div>
            <div class="cbp-caption-activeWrap">
                <div class="cbp-l-caption-alignCenter">
                    <div class="cbp-l-caption-body">
                        <div class="cbp-l-caption-title"><?php echo $this->item->title;?></div>
                        <div class="cbp-l-caption-desc"><?php echo JText::_('TPL_HOXA_BY_TEXT');?> <?php echo $this->item->author->name;?></div>
                        <br />
                        <a href="<?php echo $this->item->link;?>" class="portfolioicons"><i class="fa fa-link"></i></a>
                        <a href="<?php echo JURI::root(true).$extraFields[3]->value;?>" class="cbp-lightbox portfolioicons" data-title="<?php echo $this->item->title;?><br><?php echo JText::_('TPL_HOXA_BY_TEXT');?> <?php echo $this->item->author->name;?>"><i class="fa fa-search-plus"></i></a>
                    </div>
                </div>
            </div>
        </div>
        
    </div>
</li>


