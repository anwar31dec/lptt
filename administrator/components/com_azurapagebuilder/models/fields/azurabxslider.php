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
 
class JFormFieldAzuraBxSlider extends JFormField {
 
        protected $type = 'AzuraBxSlider';
 
        // getLabel() left out
 
        public function getInput() {
                return '<div class="width100 azura-element-block" data-typeName="AzuraBxSlider">
							<div class="width100 azura-element azura-element-type-azurabxslider" data-typeName="AzuraBxSlider">

								<span class="azura-element-title"><i class="fa fa-sliders"></i> '.$this->element['name'].'</span>

								<div class="azura-element-tools">
									<i class="fa fa-arrow-up azura-element-tools-levelup"></i>
									<i class="fa fa-eye azura-element-tools-showhide"></i>
									<i class="fa fa-edit azura-element-tools-configs"></i>
									<i class="fa fa-copy azura-element-tools-copy"></i>
									<i class="fa fa-times azura-element-tools-remove"></i>
								</div>

							</div>

							<div class="azura-element-type-azurabxslider-container">
                                
                                <div class="azura-sortable elementchildren clearfix">
									<div class="hide-in-elements" style="text-align: center; vertical-align: bottom; background-color:#f5f5f5;"><i class="fa fa-plus bxAddSlider"  title="Add slide"   style="color: rgb(204, 204, 204); margin: 0px auto; font-size: 20px; cursor: pointer;"></i></div>
                                </div>
							</div>
							<!-- /.azura-element-type-azurasection-container -->

						
							<div class="azura-element-settings-saved saved" data="'.rawurlencode('{"type":"AzuraBxSlider","id": "0","published":"1","language":"*", "content":"","attrs":{}}').'"></div>
						</div>';
        }
}