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

$app = JFactory::getApplication();
?>
<div class="azp_col-md-6 azura-element-block" data-typeName="<?php echo $element->type;?>">
	<div class="width100 azura-element azura-element-type-<?php echo strtolower($element->type);?>" data-typeName="<?php echo $element->type;?>">

			<span class="azura-element-title"><?php echo $element->elementTypeName;?><?php if(!empty($element->name)) echo ' - '.$element->name;?></span>

		<div class="azura-element-tools">
			<!--<i class="fa fa-arrow-up azura-element-tools-levelup"></i> -->
			<i class="fa fa-eye azura-element-tools-showhide <?php echo $this->elements_expand;?>"></i>
			<i class="fa fa-edit azura-element-tools-configs"></i>
			<i class="fa fa-copy azura-element-tools-copy"></i>
			<i class="fa fa-times azura-element-tools-remove"></i>
		</div>

	</div>

	<div class="azura-element-type-<?php echo strtolower($element->type);?>-container">
		<div class="azura-sortable elementchildren <?php echo $this->elements_expand;?> clearfix">
		<?php
		if(isset($element->elementChilds) && count($element->elementChilds)) {
			foreach ($element->elementChilds as $child) {
				$this->parseElement($child);
			}
		}
		  ?>
		</div>
	</div>
	<!-- /.azura-element-type-azuracarouselslider-container -->
	<div class="azuraAddElementWrapper hide-in-elements <?php echo $this->elements_expand;?>"  style="text-align: center; vertical-align: bottom; background-color:#f5f5f5;"><i class="fa fa-plus azuraAddElement"  title="Add element to slide"  style="color: rgb(204, 204, 204); margin: 0px auto; font-size: 16px; cursor: pointer;"></i></div>
	

	<div class="azura-element-settings-saved saved" data="<?php echo $element->elementData;?>"></div>

</div>

