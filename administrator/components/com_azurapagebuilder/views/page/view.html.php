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

require_once JPATH_COMPONENT_ADMINISTRATOR.'/helpers/pages.php';
class AzuraPagebuilderViewPage extends JViewLegacy
{
	protected $state;

	protected $item;

	protected $form;

	/**
	 * Display the view
	 */
	public function display($tpl = null)
	{
		$this->state	= $this->get('State');
		$this->item		= $this->get('Item');
		$this->form		= $this->get('Form');
		//die('this here');
		$this->canDo		= PagesHelper::getActions('com_azurapagebuilder', 'page', $this->item->id);
		
		$this->elements = $this->get('Elements');

		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			JError::raiseError(500, implode("\n", $errors));
			return false;
		}

		$comParams = JComponentHelper::getParams('com_azurapagebuilder');
		$elements_expand = $comParams->get('elements_expand');
		$this->elements_expand = 'ishide';
		if($elements_expand == '1'){
			$this->elements_expand = '';
		}

		$this->addToolbar();
		parent::display($tpl);
	}

	/**
	* getChildElements($id)
	*/
	public function getChildElements($parentID){
		$model = $this->getModel();

		return $model->getChildElements($parentID);
	}

	/* parseElement() */
	public function parseElement($element){
		$element->attrs = json_decode($element->attrs);

		//$this->element = $element;

		$element->elementData = rawurlencode(json_encode($element));

		//$type = $element->type;
		$element->elementTypeName = 'Text';

		switch ($element->type) {
			case 'AzuraSection':

				if($element->hasChild == '1' || isset($element->hasChildID)){
					$element->elementChilds = $this->getChildElements($element->hasChildID);
				}
				$element->elementTypeName = '<i class="fa fa-archive"></i>  Section';
				break;
			case 'AzuraContainer':

				if($element->hasChild == '1' || isset($element->hasChildID)){
					$element->elementChilds = $this->getChildElements($element->hasChildID);
				}
				$element->elementTypeName = '<i class="fa fa-archive"></i>  Container';
				break;
			case 'AzuraHeader':
				$element->elementTypeName = '<i class="fa fa-header"></i>  Header';
				break;

			case 'AzuraText':
				$element->elementTypeName = '<i class="fa fa-font"></i>  Text';
				
				break;
			case 'AzuraParagraph':
				$element->elementTypeName = '<i class="fa fa-paragraph"></i>  Paragraph';
				
				break;
			case 'AzuraBlockquote':
				$element->elementTypeName = '<i class="fa fa-bold"></i>  Blockquote';
				
				break;
			case 'AzuraHtml':
				$element->elementTypeName = '<i class="fa fa-code"></i>  HTML';
				
				break;
			case 'AzuraSeparator':
				$element->elementTypeName = '<i class="fa fa-minus"></i>  Separator';
				break;
			case 'AzuraImage':
				$element->elementTypeName = '<i class="fa fa-image"></i>  Image';
				
				break;
			case 'AzuraColumn':
				if($element->hasChild == '1' || isset($element->hasChildID)){
					$element->elementChilds = $this->getChildElements($element->hasChildID);
				}
				$element->elementTypeName = '<i class="fa fa-columns"></i>  Column';
				break;
			case 'AzuraGrid':
				if($element->hasChild == '1' || isset($element->hasChildID)){
					$element->elementChilds = $this->getChildElements($element->hasChildID);
				}
				$element->elementTypeName = '<i class="fa fa-columns"></i>  Grid';
				break;
			case 'AzuraVideo':
				$element->elementTypeName = '<i class="fa fa-video-camera"></i>  Video';
				break;
			case 'AzuraTabToggle':
				if($element->hasChild == '1' || isset($element->hasChildID)){
					$element->elementChilds = $this->getChildElements($element->hasChildID);
				}
				$element->elementTypeName = '<i class="fa fa-cube"></i>  Tab & Toggle';
				break;
			case 'AzuraTabToggleItem':
				$element->elementTypeName = '<i class="fa fa-cube"></i>  Tab & Toggle Item';
				break;
			case 'AzuraRow':

				if($element->hasChild == '1' || isset($element->hasChildID)){
					$element->elementChilds = $this->getChildElements($element->hasChildID);
				}
				$element->elementTypeName = '<i class="fa fa-bars"></i>  Row';
				break;
			case 'AzuraAlert':

				$element->elementTypeName = '<i class="fa fa-bullhorn"></i> Alert';
				break;
			case 'AzuraProgress':

				$element->elementTypeName = '<i class="fa fa-tachometer"></i> Progress';
				break;
			case 'AzuraCarouselSlider':

				if($element->hasChild == '1' || isset($element->hasChildID)){
					$element->elementChilds = $this->getChildElements($element->hasChildID);
				}
				$element->elementTypeName = '<i class="fa fa-sliders"></i>  Carousel Slider';
				break;
			case 'AzuraCarouselSliderItem':

				if($element->hasChild == '1' || isset($element->hasChildID)){
					$element->elementChilds = $this->getChildElements($element->hasChildID);
				}
				$element->elementTypeName = '<i class="fa fa-sliders"></i>  Slide';
				break;
			
			case 'AzuraTeam':

				if($element->hasChild == '1' || isset($element->hasChildID)){
					$element->elementChilds = $this->getChildElements($element->hasChildID);
				}
				$element->elementTypeName = '<i class="fa fa-users"></i>  Team';
				break;
			case 'AzuraTeamMember':
				$element->elementTypeName = '<i class="fa fa-user"></i> Team Member';
				break;
			case 'AzuraStat':
				$element->elementTypeName = '<i class="fa fa-eye"></i>  Stat Counter';
				break;
			case 'AzuraServicesSlider':

				if($element->hasChild == '1' || isset($element->hasChildID)){
					$element->elementChilds = $this->getChildElements($element->hasChildID);
				}
				$element->elementTypeName = '<i class="fa fa-cogs"></i>  Services Slider';
				break;
			case 'AzuraServicesSliderItem':

				if($element->hasChild == '1' || isset($element->hasChildID)){
					$element->elementChilds = $this->getChildElements($element->hasChildID);
				}
				$element->elementTypeName = '<i class="fa fa-cogs"></i>  Service';
				break;
			case 'AzuraContactForm':

				$element->elementTypeName = '<i class="fa fa-paper-plane-o"></i>  Contact Form';
				break;
			case 'AzuraGMap':

				$element->elementTypeName = '<i class="fa fa-map-marker"></i>  Google Map';
				break;
			case 'AzuraSuperSlides':

				if($element->hasChild == '1' || isset($element->hasChildID)){
					$element->elementChilds = $this->getChildElements($element->hasChildID);
				}
				$element->elementTypeName = '<i class="fa fa-sliders"></i>  SuperSlides';
				break;
			case 'AzuraSuperSlidesItem':

				if($element->hasChild == '1' || isset($element->hasChildID)){
					$element->elementChilds = $this->getChildElements($element->hasChildID);
				}
				$element->elementTypeName = '<i class="fa fa-sliders"></i>  Slide';
				break;
			case 'AzuraModule':
				$element->elementTypeName = '<i class="fa fa-qrcode"></i>  Module';
				break;
			case 'AzuraPortfolio':
				$element->elementTypeName = '<i class="fa fa-briefcase"></i>  Portfolio';
				break;
			case 'AzuraPortfolioArticle':
				$element->elementTypeName = '<i class="fa fa-briefcase"></i>  Portfolio Article';
				break;
			case 'AzuraAccordion':
				if($element->hasChild == '1' || isset($element->hasChildID)){
					$element->elementChilds = $this->getChildElements($element->hasChildID);
				}
				$element->elementTypeName = '<i class="fa fa-cogs"></i>  Accordion';
				break;
			case 'AzuraAccordionItem':
				$element->elementTypeName = '<i class="fa fa-cogs"></i>  Accordion Item';
				break;
			case 'AzuraPopupLink':
				$element->elementTypeName = '<i class="fa fa-plus-squre-o"></i>  Popup Link';
				break;
			case 'AzuraTestimonial':
				$element->elementTypeName = '<i class="fa fa-comment-o"></i> Testimonial';
				break;
			case 'AzuraSocialButtons':
				if($element->hasChild == '1' || isset($element->hasChildID)){
					$element->elementChilds = $this->getChildElements($element->hasChildID);
				}
				$element->elementTypeName = '<i class="fa fa-comments-o"></i>  Social Buttons';
				break;
			case 'AzuraSocialButtonsButton':
				$element->elementTypeName = '<i class="fa fa-comments-o"></i>  Button';
				break;
			case 'AzuraButtonLink':
				$element->elementTypeName = '<i class="fa fa-link"></i>  Link Button';
				break;
			case 'AzuraBsCarousel':

				if($element->hasChild == '1' || isset($element->hasChildID)){
					$element->elementChilds = $this->getChildElements($element->hasChildID);
				}
				$element->elementTypeName = '<i class="fa fa-sliders"></i> Bootstrap Carousel';
				break;
			case 'AzuraBsCarouselItem':
				$element->elementTypeName = '<i class="fa fa-sliders"></i>  Slide';
				break;
			case 'AzuraBxSlider':

				if($element->hasChild == '1' || isset($element->hasChildID)){
					$element->elementChilds = $this->getChildElements($element->hasChildID);
				}
				$element->elementTypeName = '<i class="fa fa-sliders"></i> BxSlider';
				break;
			case 'AzuraBxSliderItem':

				if($element->hasChild == '1' || isset($element->hasChildID)){
					$element->elementChilds = $this->getChildElements($element->hasChildID);
				}
				$element->elementTypeName = '<i class="fa fa-sliders"></i> Slide';
				break;
			case 'AzuraFlexSlider':

				if($element->hasChild == '1' || isset($element->hasChildID)){
					$element->elementChilds = $this->getChildElements($element->hasChildID);
				}
				$element->elementTypeName = '<i class="fa fa-sliders"></i> FlexSlider';
				break;
			case 'AzuraFlexSliderItem':

				if($element->hasChild == '1' || isset($element->hasChildID)){
					$element->elementChilds = $this->getChildElements($element->hasChildID);
				}
				$element->elementTypeName = '<i class="fa fa-sliders"></i> Slide';
				break;
			case 'AzuraCounter':
				$element->elementTypeName = '<i class="fa fa-eye"></i> Counter';
				break;
			case 'AzuraCustomScript':
				$element->elementTypeName = '<i class="fa fa-subscript"></i> Custom Script';
				break;
			case 'AzuraK2Category':
				$element->elementTypeName = '<i class="fa fa-tag"></i> K2 Category';
				break;
			case 'AzuraIcon':
				$element->elementTypeName = '<i class="fa fa-eye"></i> Awesome Icon';
				break;
			case 'AzuraWork':
				$element->elementTypeName = '<i class="fa fa-briefcase"></i> Work Item';
				break;
			case 'AzuraFeatureBox':
				$element->elementTypeName = '<i class="fa fa-magic"></i> Feature Box';
				break;
			case 'AzuraPieChart':
				$element->elementTypeName = '<i class="fa fa-pie-chart"></i> Pie Chart';
				break;
			case 'AzuraVideoBackground':
				$element->elementTypeName = '<i class="fa fa-youtube-play"></i> Video Background';
				break;
			case 'AzuraIconBox':
				$element->elementTypeName = '<i class="fa fa-eye"></i> Icon Box';
				break;
			case 'AzuraMasterSlider':

				if($element->hasChild == '1' || isset($element->hasChildID)){
					$element->elementChilds = $this->getChildElements($element->hasChildID);
				}
				$element->elementTypeName = '<i class="fa fa-sliders"></i> Master Slider';
				break;
			case 'AzuraMasterSliderItem':

				if($element->hasChild == '1' || isset($element->hasChildID)){
					$element->elementChilds = $this->getChildElements($element->hasChildID);
				}
				$element->elementTypeName = '<i class="fa fa-sliders"></i>  Slide';
				break;
			default:
				$element->elementTypeName = '<i class="fa fa-bars"></i> Element';
				break;
		}

	switch ($element->type) {
		case 'AzuraImage':
			require(JPATH_ADMINISTRATOR.DIRECTORY_SEPARATOR.'components/com_azurapagebuilder/views/page/elementConfig/edit_configImage.php');
			break;
		case 'AzuraSection':
			require(JPATH_ADMINISTRATOR.DIRECTORY_SEPARATOR.'components/com_azurapagebuilder/views/page/elementConfig/edit_configSection.php');
			break;
		case 'AzuraContainer':
			require(JPATH_ADMINISTRATOR.DIRECTORY_SEPARATOR.'components/com_azurapagebuilder/views/page/elementConfig/edit_configContainer.php');
			break;
		case 'AzuraRow':
			require(JPATH_ADMINISTRATOR.DIRECTORY_SEPARATOR.'components/com_azurapagebuilder/views/page/elementConfig/edit_configRow.php'); 
			break;
		case 'AzuraHeader':
			require(JPATH_ADMINISTRATOR.DIRECTORY_SEPARATOR.'components/com_azurapagebuilder/views/page/elementConfig/edit_configHeader.php');
			break;
		case 'AzuraText':
			require(JPATH_ADMINISTRATOR.DIRECTORY_SEPARATOR.'components/com_azurapagebuilder/views/page/elementConfig/edit_configText.php');
			break;
		case 'AzuraHtml':
			require(JPATH_ADMINISTRATOR.DIRECTORY_SEPARATOR.'components/com_azurapagebuilder/views/page/elementConfig/edit_configHtml.php');
			break;
		case 'AzuraSeparator':
			require(JPATH_ADMINISTRATOR.DIRECTORY_SEPARATOR.'components/com_azurapagebuilder/views/page/elementConfig/edit_configSeparator.php');
			break;
		case 'AzuraColumn':
			require(JPATH_ADMINISTRATOR.DIRECTORY_SEPARATOR.'components/com_azurapagebuilder/views/page/elementConfig/edit_configColumn.php');
			break;
		case 'AzuraGrid':
			require(JPATH_ADMINISTRATOR.DIRECTORY_SEPARATOR.'components/com_azurapagebuilder/views/page/elementConfig/edit_configGrid.php');
			break;
		case 'AzuraVideo':
			require(JPATH_ADMINISTRATOR.DIRECTORY_SEPARATOR.'components/com_azurapagebuilder/views/page/elementConfig/edit_configVideo.php');
			break;
		case 'AzuraTabToggle':
			require(JPATH_ADMINISTRATOR.DIRECTORY_SEPARATOR.'components/com_azurapagebuilder/views/page/elementConfig/edit_configTabToggle.php');
			break;
		case 'AzuraTabToggleItem':
			require(JPATH_ADMINISTRATOR.DIRECTORY_SEPARATOR.'components/com_azurapagebuilder/views/page/elementConfig/edit_configTabToggleItem.php');
			break;
		case 'AzuraCarouselSlider':
			require(JPATH_ADMINISTRATOR.DIRECTORY_SEPARATOR.'components/com_azurapagebuilder/views/page/elementConfig/edit_configCarouselSlider.php');
			break;
		case 'AzuraCarouselSliderItem':
			require(JPATH_ADMINISTRATOR.DIRECTORY_SEPARATOR.'components/com_azurapagebuilder/views/page/elementConfig/edit_configCarouselSliderItem.php');
			break;
		case 'AzuraTeam':
			require(JPATH_ADMINISTRATOR.DIRECTORY_SEPARATOR.'components/com_azurapagebuilder/views/page/elementConfig/edit_configTeam.php');
			break;
		// case 'AzuraTeamMember':
		// 	require(JPATH_ADMINISTRATOR.DIRECTORY_SEPARATOR.'components/com_azurapagebuilder/views/page/elementConfig/edit_configTeamMember.php');
		// 	break;
		case 'AzuraStat':
			require(JPATH_ADMINISTRATOR.DIRECTORY_SEPARATOR.'components/com_azurapagebuilder/views/page/elementConfig/edit_configImage.php'); 
			break;
		case 'AzuraServicesSlider':
			require(JPATH_ADMINISTRATOR.DIRECTORY_SEPARATOR.'components/com_azurapagebuilder/views/page/elementConfig/edit_configServicesSlider.php');
			break;
		case 'AzuraServicesSliderItem':
			require(JPATH_ADMINISTRATOR.DIRECTORY_SEPARATOR.'components/com_azurapagebuilder/views/page/elementConfig/edit_configServicesSliderItem.php');
			break;
		case 'AzuraSuperSlides':
			require(JPATH_ADMINISTRATOR.DIRECTORY_SEPARATOR.'components/com_azurapagebuilder/views/page/elementConfig/edit_configSuperSlides.php');
			break;
		case 'AzuraSuperSlidesItem':
			require(JPATH_ADMINISTRATOR.DIRECTORY_SEPARATOR.'components/com_azurapagebuilder/views/page/elementConfig/edit_configSuperSlidesItem.php');
			break;
		case 'AzuraAccordion':
			require(JPATH_ADMINISTRATOR.DIRECTORY_SEPARATOR.'components/com_azurapagebuilder/views/page/elementConfig/edit_configAccordion.php');
			break;
		case 'AzuraAccordionItem':
			require(JPATH_ADMINISTRATOR.DIRECTORY_SEPARATOR.'components/com_azurapagebuilder/views/page/elementConfig/edit_configAccordionItem.php');
			break;
		case 'AzuraSocialButtons':
			require(JPATH_ADMINISTRATOR.DIRECTORY_SEPARATOR.'components/com_azurapagebuilder/views/page/elementConfig/edit_configSocialButtons.php');
			break;
		case 'AzuraSocialButtonsButton':
			require(JPATH_ADMINISTRATOR.DIRECTORY_SEPARATOR.'components/com_azurapagebuilder/views/page/elementConfig/edit_configSocialButtonsButton.php');
			break;
		// case 'AzuraGMap':
		// 	require(JPATH_ADMINISTRATOR.DIRECTORY_SEPARATOR.'components/com_azurapagebuilder/views/page/elementConfig/edit_configGMap.php');
		// 	break;
		case 'AzuraButtonLink':
			require(JPATH_ADMINISTRATOR.DIRECTORY_SEPARATOR.'components/com_azurapagebuilder/views/page/elementConfig/edit_configButtonLink.php');
			break;
		case 'AzuraBsCarousel':
			require(JPATH_ADMINISTRATOR.DIRECTORY_SEPARATOR.'components/com_azurapagebuilder/views/page/elementConfig/edit_configBsCarousel.php');
			break;
		case 'AzuraBsCarouselItem':
			require(JPATH_ADMINISTRATOR.DIRECTORY_SEPARATOR.'components/com_azurapagebuilder/views/page/elementConfig/edit_configBsCarouselItem.php');
			break;
		case 'AzuraBxSlider':
			require(JPATH_ADMINISTRATOR.DIRECTORY_SEPARATOR.'components/com_azurapagebuilder/views/page/elementConfig/edit_configBxSlider.php');
			break;
		case 'AzuraBxSliderItem':
			require(JPATH_ADMINISTRATOR.DIRECTORY_SEPARATOR.'components/com_azurapagebuilder/views/page/elementConfig/edit_configBxSliderItem.php');
			break;
		case 'AzuraFlexSlider':
			require(JPATH_ADMINISTRATOR.DIRECTORY_SEPARATOR.'components/com_azurapagebuilder/views/page/elementConfig/edit_configFlexSlider.php');
			break;
		case 'AzuraFlexSliderItem':
			require(JPATH_ADMINISTRATOR.DIRECTORY_SEPARATOR.'components/com_azurapagebuilder/views/page/elementConfig/edit_configFlexSliderItem.php');
			break;
		case 'AzuraWork':
			require(JPATH_ADMINISTRATOR.DIRECTORY_SEPARATOR.'components/com_azurapagebuilder/views/page/elementConfig/edit_configWork.php');
			break;
		case 'AzuraMasterSlider':
			require(JPATH_ADMINISTRATOR.DIRECTORY_SEPARATOR.'components/com_azurapagebuilder/views/page/elementConfig/edit_configMasterSlider.php');
			break;
		case 'AzuraMasterSliderItem':
			require(JPATH_ADMINISTRATOR.DIRECTORY_SEPARATOR.'components/com_azurapagebuilder/views/page/elementConfig/edit_configMasterSliderItem.php');
			break;
		default :
			require(JPATH_ADMINISTRATOR.DIRECTORY_SEPARATOR.'components/com_azurapagebuilder/views/page/elementConfig/edit_configImage.php'); 
			break;
	}

}
	/**
	 * Add the page title and toolbar.
	 *
	 * @since   1.6
	 */
	protected function addToolbar()
	{
		JFactory::getApplication()->input->set('hidemainmenu', true);

		$user		= JFactory::getUser();
		$isNew		= ($this->item->id == 0);
		$checkedOut	= !($this->item->checked_out == 0 || $this->item->checked_out == $user->get('id'));

		// Since we don't track these assets at the item level, use the category id.
		$canDo		= $this->canDo;

		JToolbarHelper::title(JText::_('Pages Manager: Build Page'), '');

		// If not checked out, can save the item.
		if (!$checkedOut && ($canDo->get('core.edit')||($user->authorise('core.create', 'com_azurapagebuilder'))))
		{
			JToolbarHelper::apply('page.apply');
			JToolbarHelper::save('page.save');
		}
		if (!$checkedOut && ($user->authorise('core.create', 'com_azurapagebuilder')))
		{
			JToolbarHelper::save2new('page.save2new');
		}
		// If an existing item, can save to a copy.
		if (!$isNew && ($user->authorise('core.create', 'com_azurapagebuilder')))
		{
			JToolbarHelper::save2copy('page.save2copy');
		}
		if (empty($this->item->id))
		{
			JToolbarHelper::cancel('page.cancel');
		}
		else
		{
			if ($this->state->params->get('save_history', 0) && $user->authorise('core.edit'))
			{
				JToolbarHelper::versions('com_azurapagebuilder.page', $this->item->id);
			}

			JToolbarHelper::cancel('page.cancel', 'JTOOLBAR_CLOSE');
		}
	}
}
