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

require_once JPATH_COMPONENT_ADMINISTRATOR.'/views/page/view.html.php';

class AzuraPagebuilderViewEdit extends AzuraPagebuilderViewPage {

	protected $form;

	protected $item;

	protected $items;

	protected $return_page;

	protected $state;

	public function display($tpl = null)
	{
		$user		= JFactory::getUser();

		// Get model data.
		$this->state		= $this->get('State');
		$this->item			= $this->get('Item');
		$this->form			= $this->get('Form');
		$this->return_page	= $this->get('ReturnPage');

		$this->elements = $this->get('Elements');

		if (empty($this->item->id))
		{
			$authorised = $user->authorise('core.create', 'com_azurapagebuilder');
		}
		else
		{
			$authorised = $user->authorise('core.edit', 'com_azurapagebuilder.page.'.$this->item->id);

		}

		if ($authorised !== true)
		{
			JFactory::getApplication()->redirect(JRoute::_('index.php?option=com_users&view=login'),'You must login as admin account to edit!');
		}

		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			JError::raiseWarning(500, implode("\n", $errors));
			return false;
		}

		// Create a shortcut to the parameters.
		$params	= &$this->state->params;

		//Escape strings for HTML output
		$this->pageclass_sfx = htmlspecialchars($params->get('pageclass_sfx'));

		$this->params	= $params;
		$this->user		= $user;

		$this->setLayout('edit');

		$result = $this->loadTemplate($tpl);

		if ($result instanceof Exception)
		{
			return $result;
		}

		echo $result;
	}

	public function parseElement($element){
		if($element->hasChild == '1' || isset($element->hasChildID)){
			$element->elementChilds = $this->getChildElements($element->hasChildID);
			if(count($element->elementChilds)){
				foreach ($element->elementChilds as $key => $child) {

					$element->content .= do_shortcode($this->parseElement($child));
				}
			}
		}

		$style = 'style="top:50%;left:50%;"';

		

		switch ($element->type) {
			case 'AzuraSection':
				$style = 'style="top:0px;"';
				break;
			case 'AzuraContainer':
				$style = 'style="top:0px;"';
				break;
			case 'AzuraGMap':
				$style = 'style="top:60px;"';
				break;
			case 'AzuraRow':
				$style = '';
				break;
			case 'AzuraColumn':
				$style = 'style="top:30px;left:0px;"';
				break;
			case 'AzuraBsCarousel':
				$style = 'style="top:10px;left:0px;"';
				break;
			case 'AzuraTabToggle':
				$style = 'style="top:30px;left:0px;"';
				break;
			case 'AzuraCarouselSlider':
				$style = 'style="top:0px;left:0px;"';
				break;
			case 'AzuraMasterSlider':
				$style = 'style="top:0px;left:0px;"';
				break;
			case 'AzuraAccordion':
				$style = 'style="top:0px;left:0px;"';
				break;
		}

		$tools = '<div class="azura-element-tools '.strtolower($element->type).'" '.$style.'>
			<span class="azura-element-move">'.($element->name? substr($element->type, 5).'-'.$element->name : substr($element->type, 5)).'</span>
			<i class="fa fa-times azura-element-tools-remove"></i>
			<i class="fa fa-edit azura-element-tools-configs"></i>
			
		</div>';

		switch ($element->type) {
			
			case 'AzuraRow':
				$tools = '<div class="azura-element-tools '.strtolower($element->type).'" '.$style.'>
					<span class="azura-element-move">'.($element->name? substr($element->type, 5).'-'.$element->name : substr($element->type, 5)).'</span>
					<span class="fa fa-columns azura-row-layout">
						<span>
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

						</span>	
					</span>
					<i class="fa fa-edit azura-element-tools-configs"></i>
					<i class="fa fa-times azura-element-tools-remove"></i>
					<!--<i class="fa fa-plus azuraAddElement" ></i>-->
				</div>';
				break;
			case 'AzuraColumn':
			case 'AzuraContainer':
			case 'AzuraSection':
			case 'AzuraCarouselSliderItem':
			case 'AzuraBxSliderItem':
			case 'AzuraFlexSliderItem':
			case 'AzuraMasterSliderItem':
				$tools = '<div class="azura-element-tools '.strtolower($element->type).'" '.$style.'>
					<span class="azura-element-move">'.($element->name? substr($element->type, 5).'-'.$element->name : substr($element->type, 5)).'</span>
					<i class="fa fa-plus azuraAddElement" ></i>
					<i class="fa fa-edit azura-element-tools-configs"></i>
					<i class="fa fa-times azura-element-tools-remove"></i>
					
				</div>';
				break;
			case 'AzuraTabToggle':
				$tools = '<div class="azura-element-tools '.strtolower($element->type).'" '.$style.'>
					<span class="azura-element-move">'.($element->name? substr($element->type, 5).'-'.$element->name : substr($element->type, 5)).'</span>
					<i class="fa fa-plus azuraAddTabElement" ></i>
					<i class="fa fa-edit azura-element-tools-configs"></i>
					<i class="fa fa-times azura-element-tools-remove"></i>
					
				</div>';
				break;
			case 'AzuraAccordion':
				$tools = '<div class="azura-element-tools '.strtolower($element->type).'" '.$style.'>
					<span class="azura-element-move">'.($element->name? substr($element->type, 5).'-'.$element->name : substr($element->type, 5)).'</span>
					<i class="fa fa-plus azuraAddAccElement" ></i>
					<i class="fa fa-edit azura-element-tools-configs"></i>
					<i class="fa fa-times azura-element-tools-remove"></i>
					
				</div>';
				break;
			case 'AzuraCarouselSlider':
				$tools = '<div class="azura-element-tools '.strtolower($element->type).'" '.$style.'>
					<span class="azura-element-move">'.($element->name? substr($element->type, 5).'-'.$element->name : substr($element->type, 5)).'</span>
					<i class="fa fa-plus azuraAddCarouselElement" ></i>
					<i class="fa fa-edit azura-element-tools-configs"></i>
					<i class="fa fa-times azura-element-tools-remove"></i>
					
				</div>';
				break;
			case 'AzuraBxSlider':
				$tools = '<div class="azura-element-tools '.strtolower($element->type).'" '.$style.'>
					<span class="azura-element-move">'.($element->name? substr($element->type, 5).'-'.$element->name : substr($element->type, 5)).'</span>
					<i class="fa fa-plus azuraAddBxSlideElement" ></i>
					<i class="fa fa-edit azura-element-tools-configs"></i>
					<i class="fa fa-times azura-element-tools-remove"></i>
					
				</div>';
				break;
			case 'AzuraFlexSlider':
				$tools = '<div class="azura-element-tools '.strtolower($element->type).'" '.$style.'>
					<span class="azura-element-move">'.($element->name? substr($element->type, 5).'-'.$element->name : substr($element->type, 5)).'</span>
					<i class="fa fa-plus azuraAddFlexSlideElement" ></i>
					<i class="fa fa-edit azura-element-tools-configs"></i>
					<i class="fa fa-times azura-element-tools-remove"></i>
					
				</div>';
				break;
			case 'AzuraMasterSlider':
				$tools = '<div class="azura-element-tools '.strtolower($element->type).'" '.$style.'>
					<span class="azura-element-move">'.($element->name? substr($element->type, 5).'-'.$element->name : substr($element->type, 5)).'</span>
					<i class="fa fa-plus azuraAddMasterSlideElement" ></i>
					<i class="fa fa-edit azura-element-tools-configs"></i>
					<i class="fa fa-times azura-element-tools-remove"></i>
					
				</div>';
				break;
			case 'AzuraBsCarousel':
				$tools = '<div class="azura-element-tools '.strtolower($element->type).'" '.$style.'>
					<span class="azura-element-move">'.($element->name? substr($element->type, 5).'-'.$element->name : substr($element->type, 5)).'</span>
					<i class="fa fa-plus azuraAddBsCarouselElement" ></i>
					<i class="fa fa-edit azura-element-tools-configs"></i>
					<i class="fa fa-times azura-element-tools-remove"></i>
					
				</div>';
				break;
		}

		$attrs = json_decode($element->attrs);
		$attrsText = '';

		if(count($attrs)){
			foreach ($attrs as $key => $value) {
				$attrsText .=(' '.$key.'="'.$value.'"');
			}
		}

		$addChildTool = '';

		$eleClass = '';

		switch ($element->type) {
			case 'AzuraRow':
				$eleClass = 'azp_row';
				break;
			case 'AzuraColumn':
				if($attrs->columnwidthclass){
					$eleClass = $attrs->columnwidthclass;
				}else{
					$eleClass = 'azp_col';
				}
				break;
			// case 'AzuraCarouselSliderItem':
			// 	$eleClass = 'azp_w-50';
			// 	break;
			case 'AzuraTabToggleItem':
				$eleClass = 'azp_build tab-pane active';
				break;
			case 'AzuraBsCarouselItem':
				$eleClass = 'item';
				break;
		}

		$idID = '';

		switch ($element->type) {
			case 'AzuraTabToggleItem':
				$idID = 'id="'.$attrs->id.'"';
				break;
		}

		// switch ($element->type) {
		// 	case 'AzuraRow':
		// 		$addChildTool = '<div class="azuraAddElementWrapper hide-in-elements"  style="text-align: center; vertical-align: bottom; background-color:#f5f5f5;"><i class="fa fa-plus azuraAddElement"   title="Add element to column" style="color: rgb(204, 204, 204); margin: 0px auto; font-size: 16px; cursor: pointer;"></i></div>';
		// 		break;
		// }

		return '<div '.$idID.' data-level="'.$element->level.'"'.($element->type == 'AzuraColumn'? ' data-columnwidthclass="'.$attrs->columnwidthclass.'"' : '').' data-haschild="'.$element->hasChild.'"'. (($element->hasChild == '1')? ' data-haschildid="'.$element->hasChildID.'"' : '').' data-type="'.$element->type.'" data-id="'.$element->id.'" class="azura-element-content '.strtolower($element->type).' '.$eleClass.'">'.
		$tools
		.'['.$element->type.$attrsText.']'.$element->content.'[/'.$element->type.']'.'</div>'.$addChildTool;
	}

}