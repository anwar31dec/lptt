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
 
class JFormFieldAzuraElement extends JFormField {
 
        protected $type = 'AzuraElement';
 
        // getLabel() left out
 
        public function getInput() {
        		if($this->element['hasownchildren'] == 'yes'){
        			$addownchild = '<div class="hide-in-elements" style="text-align: center; vertical-align: bottom; background-color:#f5f5f5;"><i class="fa fa-plus '.$this->element['addownchildclass'].'"  title="Add child"   style="color: rgb(204, 204, 204); margin: 0px auto; font-size: 20px; cursor: pointer;"></i></div>';
        			$addnormalele = '';
        		}else{
        			$addownchild = '';
        			$addnormalele = '<div class="azuraAddElementWrapper hide-in-elements"  style="text-align: center; vertical-align: bottom; background-color:#f5f5f5;"><i class="fa fa-plus azuraAddElement"  title="Add element to '.strtolower(substr($this->element['typename'],5)).'"   style="color: rgb(204, 204, 204); margin: 0px auto; font-size: 16px; cursor: pointer;"></i></div>';
        		}
        		if($this->element['haschildren'] == 'yes'){
        			$showhide = '<i class="fa fa-eye azura-element-tools-showhide"></i>';
        			$childrencontainer = '<div class="azura-element-type-'.strtolower($this->element['typename']).'-container">
								<div class="azura-sortable  elementchildren clearfix" >'.
								$addownchild.'
								</div>
							</div>
							<!-- /.azura-element-type-'.strtolower($this->element['typename']).'-container -->'.
							$addnormalele.'
							
							';

							
        		}else{
        			$showhide = '';
        			$childrencontainer ='';
        		}

        		if($this->element['typename'] == 'AzuraRow'){
        			$rowLayout = '<span class="azura-row-layout">
									<a class="set-width l_1" data-layout="11" href="#" title="1"></a>
									<a class="set-width l_12_12" href="#" data-layout="12_12" title="1/2+1/2"></a>
									<a class="set-width l_23_13" href="#" data-layout="23_13" title="2/3+1/3"></a>
									<a class="set-width l_13_13_13" href="#" data-layout="13_13_13" title="1/3+1/3+1/3"></a>
									<a class="set-width l_14_14_14_14" href="#" data-layout="14_14_14_14" title="1/4+1/4+1/4+1/4"></a>
									<a class="set-width l_14_34" href="#" data-layout="14_34" title="1/4+3/4"></a>
									<a class="set-width l_14_12_14" href="#" data-layout="14_12_14" title="1/4+1/2+1/4"></a>
									<a class="set-width l_56_16" href="#" data-layout="56_16" title="5/6+1/6"></a>
									<a class="set-width l_16_16_16_16_16_16" data-layout="16_16_16_16_16_16" href="#" title="1/6+1/6+1/6+1/6+1/6+1/6"></a>
									<a class="set-width l_16_46_16" data-layout="16_46_16" href="#" title="1/6+4/6+1/6"></a>
									<a class="set-width l_16_16_16_12" data-layout="16_16_16_12" href="#" title="1/6+1/6+1/6+1/2"></a>
									<span class="custom_column_layout">
										<span>Custom Layout</span>&nbsp;&nbsp;
										<input type="text" class="set-width-column" placeholder="5/12+7/12">
										<button class="btn btn-small set-width-custom-button">Set</button>
									</span>
								</span>';
					$childrencontainer = '<div class="azura-element-type-'.strtolower($this->element['typename']).'-container">
								<div class="azura-sortable  elementchildren clearfix" >
								</div>
							</div>
							<!-- /.azura-element-type-'.strtolower($this->element['typename']).'-container -->';

        		}else{
        			$rowLayout = '';
        		}
                return '<div class="width100 azura-element-block" data-typeName="'.$this->element['typename'].'">
							<div class="width100 azura-element azura-element-type-'.strtolower($this->element['typename']).'" data-typeName="'.$this->element['typename'].'">
								
								'.$rowLayout.'

								<span class="azura-element-title"><i class="'.$this->element['iconclass'].'"></i> '.$this->element['name'].'</span>

								<div class="azura-element-tools">
									<i class="fa fa-arrow-up azura-element-tools-levelup"></i>
									'.$showhide.'
									<i class="fa fa-edit azura-element-tools-configs"></i>
									<i class="fa fa-copy azura-element-tools-copy"></i>
									<i class="fa fa-times azura-element-tools-remove"></i>
								</div>

							</div>
							
							'.$childrencontainer.'
							
							<div class="azura-element-settings-saved saved" data="'.rawurlencode('{"type":"'.$this->element['typename'].'","id": "0","published":"1","language":"*", "content":"","attrs":{}}').'"></div>
						</div>';
        }
}