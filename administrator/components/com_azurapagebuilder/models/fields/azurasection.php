<?php
/**
 * @package Azura Joomla Pagebuilder
 * @author Cththemes - www.cththemes.com
 * @date: 15-07-2014
 *
 * @copyright  Copyright ( C ) 2014 cththemes.com . All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');
 
jimport('joomla.form.formfield');
 
class JFormFieldAzuraSection extends JFormField {
 
        protected $type = 'AzuraSection';
 
        // getLabel() left out
 
        public function getInput() {
                return '<div class="width100 azura-element-block" data-typeName="AzuraSection">
							<div class="width100 azura-element azura-element-type-azurasection" data-typeName="AzuraSection">

								<span class="azura-element-title"><i class="fa fa-archive"></i> '.$this->element['name'].'</span>

								<div class="azura-element-tools">
									<i class="fa fa-arrow-up azura-element-tools-levelup"></i>
			<i class="fa fa-eye azura-element-tools-showhide"></i>
									<i class="fa fa-edit azura-element-tools-configs"></i>
									<i class="fa fa-copy azura-element-tools-copy"></i>
									<i class="fa fa-times azura-element-tools-remove"></i>
								</div>

							</div>

							<div class="azura-element-type-azurasection-container">
								<div class="azura-sortable  elementchildren clearfix" >
								</div>
							</div>
							<!-- /.azura-element-type-azurasection-container -->
							<div class="azuraAddElementWrapper hide-in-elements"  style="text-align: center; vertical-align: bottom; background-color:#f5f5f5;"><i class="fa fa-plus azuraAddElement"  title="Add element to section"   style="color: rgb(204, 204, 204); margin: 0px auto; font-size: 16px; cursor: pointer;"></i></div>
							
							<div class="azura-element-settings-saved saved" data="'.rawurlencode('{"type":"AzuraSection","id": "0","published":"1","language":"*", "content":"","attrs":{}}').'"></div>
						</div>';
        }
}