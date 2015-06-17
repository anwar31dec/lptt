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
require_once JPATH_ROOT.'/administrator/components/com_azurapagebuilder/helpers/awesomefont.php';

class AzuraPagebuilderControllerElement extends JControllerLegacy
{
	public function __construct($config = array())
	{

		JForm::addFieldPath(JPATH_ROOT.'/administrator/components/com_azurapagebuilder/models/fields');
		//JForm::addFormPath(JPATH_ROOT.'/templates/protostar/html/com_azurapagebuilder/forms');
		JForm::addFormPath(JPATH_ROOT.'/administrator/components/com_azurapagebuilder/models/forms');
		JForm::addFormPath(JPATH_THEMES.'/'.JFactory::getApplication()->getTemplate().'/html/com_azurapagebuilder/forms');
		parent::__construct();
	}
	public function iconfonts(){
		$awesomefont = ebor_icons_list();

		$q = $this->input->get('q');
		$html = array();

		if($q !== ""){
			$q = strtolower($q);
			$len = strlen($q);
			foreach ($awesomefont as $font => $name) {
				if (stristr($q, substr($font, 3, $len))) {
					$html[] = '<div class="icon-select">';
					$html[] = '<i data-font="'.$font.'" class="fa '.$font.' fa-2x"></i>';
					$html[] = '</div>';
				}
			}
		}else{
			foreach ($awesomefont as $font => $name) {
				$html[] = '<div class="icon-select">';
				$html[] = '<i data-font="'.$font.'" class="fa '.$font.' fa-2x"></i>';
				$html[] = '</div>';
			}
		}

		echo (empty($html)? "Not Found!" : implode("\n", $html));

		exit();
	}
	public function config(){
		$type = $this->input->get('eletype','azuratext');
		$data = $this->input->get('data','','raw');

	    // $dataObject = json_decode($data);
	    // echo '<pre>';var_dump($dataObject);die;
	    // $data = rawurlencode($data);

		// fix 414 error
	    $dataObject = json_decode(rawurldecode($data));
	    //echo '<pre>';var_dump($dataObject);die;
	    //$data = rawurlencode($data);

	    if($type == 'azurahtml'){
	    	$app = JFactory::getApplication();
	    	$app->setUserState('com_azurapagebuilder.element.html.data',$data);
	    	//echo $this->configAzurahtml($data,$dataObject);
	    }//else{
	    	$optionFormName = 'formoption'.substr($type, 5);

	    	echo CthShortcodes::renderElementOptions($optionFormName,$dataObject,$data);
	    //}

	    //echo $this->{'config'.ucfirst($type)}($data,$dataObject);

	    exit;
	}

	public function edit(){
		$type = $this->input->get('eletype','AzuraText');
		$id = $this->input->get('id','','int');
		//echo'<pre>';var_dump($type);exit;
		if($id > 0){
			$model = $this->getModel('Element');
			$element = $model->getItem($id);
			$element->attrs = json_decode($element->attrs);
			$element->content = rawurlencode($element->content);
			$element->shortcode = '';
		    $data = rawurlencode(json_encode($element));
		}else{
			$element = new stdClass;
			$element->type = $type;
			$element->attrs = new stdClass;
			$element->content = '';
			$data = rawurlencode(json_encode($element));
		}

	    if($type == 'AzuraHtml'){
	    	$app = JFactory::getApplication();
	    	$app->setUserState('com_azurapagebuilder.element.html.data',$data);
	    	//echo $this->configAzurahtml($data,$element);
	    }//else{
	    	$type = strtolower($type);
	    	$optionFormName = 'formoption'.substr($type, 5);

	    	echo CthShortcodes::renderElementOptions($optionFormName,$element,$data);
	    //}

	    exit;
	}

	public function reloadEle(){
		$id = $this->input->getInt('id');

		if($id > 0){
			$model = $this->getModel('Element');
			$element = $model->getItem($id);
			$result = $this->parseElement($element);
			echo do_shortcode($result);
		}else{
			echo 'false';
		}

		exit();
	}

	public function saveedit(){
		$data = $this->input->get('eledata','','raw');

		$dataObject = json_decode(rawurldecode($data));

		if($dataObject->id > 0){
			$model = $this->getModel('Element');
			$result = $model->updateElement($dataObject);
		}else{
			$element = get_object_vars($dataObject);

			$element['attrs'] = json_encode($element['attrs']);
			//echo json_encode($element);
			$model = $this->getModel('Page');
			$result = $model->savePageElement($element,true);
			if($element['type'] == 'AzuraTabToggle'){
				$defaultTab = new stdClass;
				$defaultTab->id = 0;
				$defaultTab->name = '';
				$defaultTab->published = 1;
				//$defaultTab->hasChild = 1;
				$defaultTab->type = 'AzuraTabToggleItem';
				$defaultTab->attrs = new stdClass;
				$defaultTab->attrs->title = 'First Tab';
				$defaultTab->attrs->id = 'TabID';
				$defaultTab->content = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Proin a tellus eu dui euismod laoreet. Duis faucibus ornare erat.';
				$elemodel = $this->getModel('Element');
				$element = $elemodel->getItem($result);

				$defaultTab->level = (int) $element->level + 1;
				$defaultTab->hasParentID = $element->hasChildID;
				$defaultTab->pageID = $element->pageID;


				$tabEle = get_object_vars($defaultTab);

				$tabEle['attrs'] = json_encode($tabEle['attrs']);

				//echo json_encode($tabEle);
				$model = $this->getModel('Page');
				$model->savePageElement($tabEle,true);
			}elseif($element['type'] == 'AzuraAccordion'){
				$defaultAcc = new stdClass;
				$defaultAcc->id = 0;
				$defaultAcc->name = '';
				$defaultAcc->published = 1;
				//$defaultAcc->hasChild = 1;
				$defaultAcc->type = 'AzuraAccordionItem';
				$defaultAcc->attrs = new stdClass;
				$defaultAcc->attrs->title = 'First Item';
				$defaultAcc->attrs->id = 'AccordionID';
				$defaultAcc->content = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Proin a tellus eu dui euismod laoreet. Duis faucibus ornare erat.';
				$elemodel = $this->getModel('Element');
				$element = $elemodel->getItem($result);

				$defaultAcc->level = (int) $element->level + 1;
				$defaultAcc->hasParentID = $element->hasChildID;
				$defaultAcc->pageID = $element->pageID;


				$tabEle = get_object_vars($defaultAcc);

				$tabEle['attrs'] = json_encode($tabEle['attrs']);

				//echo json_encode($tabEle);
				$model = $this->getModel('Page');
				$model->savePageElement($tabEle,true);
			}
		}

		if($dataObject->id > 0){
			$id = $dataObject->id;
		}else{
			$id = $result;
		}

		if($result === false){
			echo 'false';
		}else{
			$model = $this->getModel('Element');
			$element = $model->getItem($id);
			$result = $this->parseElement($element);
			echo do_shortcode($result);
		}

		exit();
	}

	public function savecol(){
		$data = $this->input->get('eledata','','raw');

		$dataObject = json_decode(rawurldecode($data));

		if($dataObject->id > 0){
			$model = $this->getModel('Element');
			$result = $model->updateElement($dataObject);
		}else{
			$element = get_object_vars($dataObject);

			$element['attrs'] = json_encode($element['attrs']);

			$model = $this->getModel('Page');
			$result = $model->savePageElement($element,true);
		}

		if($dataObject->id > 0){
			$id = $dataObject->id;
		}else{
			$id = $result;
		}

		if($result === false){
			echo json_encode(array("info"=>'error',"msg"=>'There was a error'));
		}else{
			$model = $this->getModel('Element');
			$element = $model->getItem($id);
			echo json_encode(array("info"=>'success',"level"=>$element->level,"haschildid"=>$element->hasChildID,"msg"=>'There was a success'));
		}

		exit();
	}

	public function updateele(){
		$data = $this->input->get('eledata','','raw');

		$dataObject = json_decode(rawurldecode($data));

		$result = false;

		if($dataObject->id > 0){
			$model = $this->getModel('Element');
			$result = $model->updateElement($dataObject);
		}

		if($result === false){
			echo json_encode(array("info"=>'error',"msg"=>'There was a error'));
		}else{
			echo json_encode(array("info"=>'success',"msg"=>'There was a success'));
		}

		exit();
	}

	public function parseElement($element){
		if($element->hasChild == '1' || isset($element->hasChildID)){
			$model = $this->getModel('Page');
			$element->elementChilds = $model->getChildElements($element->hasChildID,$element->pageID);
			foreach ($element->elementChilds as $key => $child) {

				$element->content .= do_shortcode($this->parseElement($child));
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
			case 'AzuraMasterSlider':
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
			case 'AzuraMasterSlider':
				$tools = '<div class="azura-element-tools '.strtolower($element->type).'" '.$style.'>
					<span class="azura-element-move">'.($element->name? substr($element->type, 5).'-'.$element->name : substr($element->type, 5)).'</span>
					<i class="fa fa-plus azuraAddMasterSlideElement" ></i>
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


	public function deleteele(){
		$id = $this->input->getInt('id','0');

		if($id > 0){
			$model = $this->getModel('Element');
			$result = $model->deleteElement($id);
		}else{
			$result = false;
		}

		if($result !== false){
			echo json_encode(array("info"=>'success',"msg"=>'item was deleted'));
		}else{
			echo json_encode(array("info"=>'error',"msg"=>'item was not deleted'));
		}
		exit();
	}


	protected function configAzurahtml($data,$dataObject){
		$html = '
			<h2><i class="fa fa-code"></i> HTML'.(!empty($dataObject->name)? ' - '.$dataObject->name : '').'</h2>
			<div class="row-fluid" style="padding-top:20px;">
				<div class="span5">
					<div class="input-prepend">
						<span class="add-on">Name</span>
						<input class="inputbox" name="elementName" placeholder="Element name" type="text" value="'.(isset($dataObject->name)? $dataObject->name : '').'">
					</div>
				</div>
				<div class="span7">
					<div class="form-horizontal">
						<div class="control-group elementPubLang">
							<div class="control-label">
								<label id="elementPubLang_published-lbl" for="elementPubLang_published">Published</label>
							</div>
							<div class="controls">
								<fieldset id="elementPubLang_published" class="radio btn-group btn-group-yesno">
									<input id="elementPubLang_published1" name="elementPubLang[published]" value="1" '.((isset($dataObject->published) && $dataObject->published == '1')? 'checked="checked"' : '').' type="radio">
									<label  for="elementPubLang_published1">Yes</label>
									<input id="elementPubLang_published0" name="elementPubLang[published]" value="0" '.((isset($dataObject->published) && $dataObject->published == '0')? 'checked="checked"' : '').' type="radio">
									<label for="elementPubLang_published0">No</label>
								</fieldset>
							</div>
						</div>
					</div>

				</div>
			</div>
			<!-- /.row-fluid -->
			<hr>
			<div class="row-fluid">
				<div class="span12">

					<iframe class="AzuraHtml-editor" src="'.JURI::base().'index.php?option=com_azurapagebuilder&task=edit.getEditor&tmpl=component" width="100%" height="370"></iframe>
				</div>
			</div>
			<!-- /.row-fluid -->
			
			<div class="row-fluid" style="text-align: center;">
				<hr>
				<a href="#" id="azura-setting-btn-save" class="btn btn-primary azp_btn-primary azura-setting-btn-save">Save</a>
				<a href="#" id="azura-setting-btn-cancel" class="btn btn-default azp_btn-default azura-setting-btn-cancel">Cancel</a>
			</div>';

			$html .='
			<script>
				jQuery(function($){

					// Turn radios into btn-group
					$(\'.radio.btn-group label\').addClass(\'btn btn-small\');
					$(\'body\').on(\'click\',\'.btn-group label:not(.active)\',function()
					{
						var label = $(this);
						var input = $(\'#\' + label.attr(\'for\'));

						if (!input.prop(\'checked\')) {
							label.closest(\'.btn-group\').find(\'label\').removeClass(\'active btn-success btn-danger btn-primary\');
							if (input.val() == \'\') {
								label.addClass(\'active btn-primary\');
							} else if (input.val() == 0) {
								label.addClass(\'active btn-danger\');
							} else {
								label.addClass(\'active btn-success\');
							}
							input.prop(\'checked\', \'checked\');
						}
					});
					$(\'.btn-group input[checked=checked]\').each(function()
					{
						if ($(this).val() == \'\') {
							$(\'label[for=\' + $(this).attr(\'id\') + \']\').addClass(\'active btn-primary\');
						} else if ($(this).val() == 0) {
							$(\'label[for=\' + $(this).attr(\'id\') + \']\').addClass(\'active btn-danger\');
						} else {
							$(\'label[for=\' + $(this).attr(\'id\') + \']\').addClass(\'active btn-success\');
						}
					});
				});
            
            </script>';
			return $html;
	}

	protected function configAzurasection($data,$dataObject){
		

		$dataObject->formOption = JForm::getInstance('com_azurapagebuilder.page.optionsection', 'formoptionsection');
		$elementAttrsFields = array("id","class","sectionBgImage","wrapperTag","usesubwrapper","subWrapperClass");
		foreach ($elementAttrsFields as $key => $attr) {
			$value = null;
			if(isset($dataObject->attrs->{$attr})){
				$value = $dataObject->attrs->{$attr};
			}
			$dataObject->formOption->setValue("{$attr}","elementAttrs", $value);
		}

		$html = '
			<h2><i class="fa fa-archive"></i> Section'.(!empty($dataObject->name)? ' - '.$dataObject->name : '').'</h2>
			<div class="row-fluid" style="padding-top:20px;">
				<div class="span5">
					<div class="input-prepend">
						<span class="add-on">Name</span>
						<input class="inputbox" name="elementName" placeholder="Element name" type="text" value="'.(isset($dataObject->name)? $dataObject->name : '').'">
					</div>
				</div>
				<div class="span7">
					<div class="form-horizontal">
						<div class="control-group elementPubLang">
							<div class="control-label">
								<label id="elementPubLang_published-lbl" for="elementPubLang_published">Published</label>
							</div>
							<div class="controls">
								<fieldset id="elementPubLang_published" class="radio btn-group btn-group-yesno">
									<input id="elementPubLang_published1" name="elementPubLang[published]" value="1" '.((isset($dataObject->published) && $dataObject->published == '1')? 'checked="checked"' : '').' type="radio">
									<label  for="elementPubLang_published1">Yes</label>
									<input id="elementPubLang_published0" name="elementPubLang[published]" value="0" '.((isset($dataObject->published) && $dataObject->published == '0')? 'checked="checked"' : '').' type="radio">
									<label for="elementPubLang_published0">No</label>
								</fieldset>
							</div>
						</div>
					</div>


				</div>
			</div>
			<!-- /.row-fluid -->
			<hr>
			<div class="row-fluid">
				<div class="span12">
					<div class="form-horizontal">';


					foreach ($dataObject->formOption->getFieldsets('elementAttrs') as $fieldsets => $fieldset) {
						foreach($dataObject->formOption->getFieldset($fieldset->name) as $field){

							if ($field->hidden) {
								$html .= $field->input;
							}else{
								//echo'<pre>';var_dump($field);
								$html .= $field->getControlGroup();
							}
						}
					}
					
					$html .='</div>
					<!-- /.form-horizontal -->
				</div>
				<!-- /.span12 -->

			</div>
			<!-- /.row-fluid -->
			
			<div class="row-fluid" style="text-align: center;">
				<hr>
				<a href="#" id="azura-setting-btn-save" class="btn btn-primary azura-setting-btn-save">Save</a>
				<a href="#" id="azura-setting-btn-cancel" class="btn btn-default azura-setting-btn-cancel">Cancel</a>
			</div>';

			$html .='
			<script>
				function jInsertFieldValue(value, id) {
		        		var old_value = jQuery("#" + id).val();
		        		if (old_value != value) {
		        			var $elem = jQuery("#" + id);
		        			$elem.val(value);
		        			$elem.trigger("change");
		        			if (typeof($elem.get(0).onchange) === "function") {
		        				$elem.get(0).onchange();
		        			}
		        		}
		        }

	            jQuery(function($) {
	    			SqueezeBox.initialize({});
	    			SqueezeBox.assign($(\'a.modal_jform_azuramedia\').get(), {
	    				parse: \'rel\'
	    			});
					
					
	                
	                jQuery(\'body\').on(\'change\',\'#elementAttrs_sectionBgImage\', function(event){
	                    event.preventDefault();
	                    var value = event.currentTarget.value;
	                    
	                    jQuery(\'.fancybox-inner\').find(\'#elementAttrs_sectionBgImage\').val(value);
	                });
	    		});

				jQuery(function($){

					// Turn radios into btn-group
					$(\'.radio.btn-group label\').addClass(\'btn btn-small\');
					$(\'body\').on(\'click\',\'.btn-group label:not(.active)\',function()
					{
						var label = $(this);
						var input = $(\'#\' + label.attr(\'for\'));

						if (!input.prop(\'checked\')) {
							label.closest(\'.btn-group\').find(\'label\').removeClass(\'active btn-success btn-danger btn-primary\');
							if (input.val() == \'\') {
								label.addClass(\'active btn-primary\');
							} else if (input.val() == 0) {
								label.addClass(\'active btn-danger\');
							} else {
								label.addClass(\'active btn-success\');
							}
							input.prop(\'checked\', \'checked\');
						}
					});
					$(\'.btn-group input[checked=checked]\').each(function()
					{
						if ($(this).val() == \'\') {
							$(\'label[for=\' + $(this).attr(\'id\') + \']\').addClass(\'active btn-primary\');
						} else if ($(this).val() == 0) {
							$(\'label[for=\' + $(this).attr(\'id\') + \']\').addClass(\'active btn-danger\');
						} else {
							$(\'label[for=\' + $(this).attr(\'id\') + \']\').addClass(\'active btn-success\');
						}
					});
				});
            
            </script>';
			return $html;
	}
	
	protected function configAzurarow($data,$dataObject){
		$dataObject->formOption = JForm::getInstance('com_azurapagebuilder.page.optionrow', 'formoptionrow');
		$elementAttrsFields = array("id","class","layout");
		foreach ($elementAttrsFields as $key => $attr) {
			$value = null;
			if(isset($dataObject->attrs->{$attr})){
				$value = $dataObject->attrs->{$attr};
			}
			$dataObject->formOption->setValue("{$attr}","elementAttrs", $value);
		}

		$html = '
			<h2><i class="fa fa-align-justify"></i> Row'.(!empty($dataObject->name)? ' - '.$dataObject->name : '').'</h2>
			<div class="row-fluid" style="padding-top:20px;">
				<div class="span5">
					<div class="input-prepend">
						<span class="add-on">Name</span>
						<input class="inputbox" name="elementName" placeholder="Element name" type="text" value="'.(isset($dataObject->name)? $dataObject->name : '').'">
					</div>
				</div>
				<div class="span7">
					<div class="form-horizontal">
						<div class="control-group elementPubLang">
							<div class="control-label">
								<label id="elementPubLang_published-lbl" for="elementPubLang_published">Published</label>
							</div>
							<div class="controls">
								<fieldset id="elementPubLang_published" class="radio btn-group btn-group-yesno">
									<input id="elementPubLang_published1" name="elementPubLang[published]" value="1" '.((isset($dataObject->published) && $dataObject->published == '1')? 'checked="checked"' : '').' type="radio">
									<label  for="elementPubLang_published1">Yes</label>
									<input id="elementPubLang_published0" name="elementPubLang[published]" value="0" '.((isset($dataObject->published) && $dataObject->published == '0')? 'checked="checked"' : '').' type="radio">
									<label for="elementPubLang_published0">No</label>
								</fieldset>
							</div>
						</div>
					</div>

				</div>
			</div>
			<!-- /.row-fluid -->
			<hr>
			<div class="row-fluid">
				<div class="span12">
					<div class="form-horizontal">';


					foreach ($dataObject->formOption->getFieldsets('elementAttrs') as $fieldsets => $fieldset) {
						foreach($dataObject->formOption->getFieldset($fieldset->name) as $field){

							if ($field->hidden) {
								$html .= $field->input;
							}else{
								//echo'<pre>';var_dump($field);
								$html .= $field->getControlGroup();
							}
						}
					}
					
					$html .='</div>
					<!-- /.form-horizontal -->
				</div>
				<!-- /.span12 -->

			</div>
			<!-- /.row-fluid -->
			
			<div class="row-fluid" style="text-align: center;">
				<hr>
				<a href="#" id="azura-setting-btn-save" class="btn btn-primary azura-setting-btn-save">Save</a>
				<a href="#" id="azura-setting-btn-cancel" class="btn btn-default azura-setting-btn-cancel">Cancel</a>
			</div>';

			$html .='
			<script>
				jQuery(function($){

					// Turn radios into btn-group
					$(\'.radio.btn-group label\').addClass(\'btn btn-small\');
					$(\'body\').on(\'click\',\'.btn-group label:not(.active)\',function()
					{
						var label = $(this);
						var input = $(\'#\' + label.attr(\'for\'));

						if (!input.prop(\'checked\')) {
							label.closest(\'.btn-group\').find(\'label\').removeClass(\'active btn-success btn-danger btn-primary\');
							if (input.val() == \'\') {
								label.addClass(\'active btn-primary\');
							} else if (input.val() == 0) {
								label.addClass(\'active btn-danger\');
							} else {
								label.addClass(\'active btn-success\');
							}
							input.prop(\'checked\', \'checked\');
						}
					});
					$(\'.btn-group input[checked=checked]\').each(function()
					{
						if ($(this).val() == \'\') {
							$(\'label[for=\' + $(this).attr(\'id\') + \']\').addClass(\'active btn-primary\');
						} else if ($(this).val() == 0) {
							$(\'label[for=\' + $(this).attr(\'id\') + \']\').addClass(\'active btn-danger\');
						} else {
							$(\'label[for=\' + $(this).attr(\'id\') + \']\').addClass(\'active btn-success\');
						}
					});
				});
            
            </script>';
			return $html;
	}


	protected function configAzuracontainer($data,$dataObject){
		

		$dataObject->formOption = JForm::getInstance('com_azurapagebuilder.page.optioncontainer', 'formoptioncontainer');
		$elementAttrsFields = array("id","class","bgImage","wrapperTag","usefullwidth");
		foreach ($elementAttrsFields as $key => $attr) {
			$value = null;
			if(isset($dataObject->attrs->{$attr})){
				$value = $dataObject->attrs->{$attr};
			}
			$dataObject->formOption->setValue("{$attr}","elementAttrs", $value);
		}

		$html = '
			<h2><i class="fa fa-archive"></i> Container'.(!empty($dataObject->name)? ' - '.$dataObject->name : '').'</h2>
			<div class="row-fluid" style="padding-top:20px;">
				<div class="span5">
					<div class="input-prepend">
						<span class="add-on">Name</span>
						<input class="inputbox" name="elementName" placeholder="Element name" type="text" value="'.(isset($dataObject->name)? $dataObject->name : '').'">
					</div>
				</div>
				<div class="span7">
					<div class="form-horizontal">
						<div class="control-group elementPubLang">
							<div class="control-label">
								<label id="elementPubLang_published-lbl" for="elementPubLang_published">Published</label>
							</div>
							<div class="controls">
								<fieldset id="elementPubLang_published" class="radio btn-group btn-group-yesno">
									<input id="elementPubLang_published1" name="elementPubLang[published]" value="1" '.((isset($dataObject->published) && $dataObject->published == '1')? 'checked="checked"' : '').' type="radio">
									<label  for="elementPubLang_published1">Yes</label>
									<input id="elementPubLang_published0" name="elementPubLang[published]" value="0" '.((isset($dataObject->published) && $dataObject->published == '0')? 'checked="checked"' : '').' type="radio">
									<label for="elementPubLang_published0">No</label>
								</fieldset>
							</div>
						</div>
					</div>


				</div>
			</div>
			<!-- /.row-fluid -->
			<hr>
			<div class="row-fluid">
				<div class="span12">
					<div class="form-horizontal">';


					foreach ($dataObject->formOption->getFieldsets('elementAttrs') as $fieldsets => $fieldset) {
						foreach($dataObject->formOption->getFieldset($fieldset->name) as $field){

							if ($field->hidden) {
								$html .= $field->input;
							}else{
								//echo'<pre>';var_dump($field);
								$html .= $field->getControlGroup();
							}
						}
					}
					
					$html .='</div>
					<!-- /.form-horizontal -->
				</div>
				<!-- /.span12 -->

			</div>
			<!-- /.row-fluid -->
			
			<div class="row-fluid" style="text-align: center;">
				<hr>
				<a href="#" id="azura-setting-btn-save" class="btn btn-primary azura-setting-btn-save">Save</a>
				<a href="#" id="azura-setting-btn-cancel" class="btn btn-default azura-setting-btn-cancel">Cancel</a>
			</div>';

			

			$html .='
			<script>
				function jInsertFieldValue(value, id) {
		        		var old_value = jQuery("#" + id).val();
		        		if (old_value != value) {
		        			var $elem = jQuery("#" + id);
		        			$elem.val(value);
		        			$elem.trigger("change");
		        			if (typeof($elem.get(0).onchange) === "function") {
		        				$elem.get(0).onchange();
		        			}
		        		}
		        }

	            jQuery(function($) {
	    			SqueezeBox.initialize({});
	    			SqueezeBox.assign($(\'a.modal_jform_azuramedia\').get(), {
	    				parse: \'rel\'
	    			});
					
					
	                
	                jQuery(\'body\').on(\'change\',\'#elementAttrs_bgImage\', function(event){
	                    event.preventDefault();
	                    var value = event.currentTarget.value;
	                    
	                    jQuery(\'.fancybox-inner\').find(\'#elementAttrs_bgImage\').val(value);
	                });
	    		});

				jQuery(function($){

					// Turn radios into btn-group
					$(\'.radio.btn-group label\').addClass(\'btn btn-small\');
					$(\'body\').on(\'click\',\'.btn-group label:not(.active)\',function()
					{
						var label = $(this);
						var input = $(\'#\' + label.attr(\'for\'));

						if (!input.prop(\'checked\')) {
							label.closest(\'.btn-group\').find(\'label\').removeClass(\'active btn-success btn-danger btn-primary\');
							if (input.val() == \'\') {
								label.addClass(\'active btn-primary\');
							} else if (input.val() == 0) {
								label.addClass(\'active btn-danger\');
							} else {
								label.addClass(\'active btn-success\');
							}
							input.prop(\'checked\', \'checked\');
						}
					});
					$(\'.btn-group input[checked=checked]\').each(function()
					{
						if ($(this).val() == \'\') {
							$(\'label[for=\' + $(this).attr(\'id\') + \']\').addClass(\'active btn-primary\');
						} else if ($(this).val() == 0) {
							$(\'label[for=\' + $(this).attr(\'id\') + \']\').addClass(\'active btn-danger\');
						} else {
							$(\'label[for=\' + $(this).attr(\'id\') + \']\').addClass(\'active btn-success\');
						}
					});
				});
            
            </script>';
			return $html;
	}

	protected function configAzuratext($data,$dataObject){
		

		$dataObject->formOption = JForm::getInstance('com_azurapagebuilder.page.optiontext', 'formoptiontext');

		// set content
		$elementContentFields = array("textContent");
		if(count($elementContentFields) == 1) {
			$content = $elementContentFields[0];
			$value = '';
			if(isset($dataObject->content)){
				$value = $dataObject->content;
			}
			$dataObject->formOption->setValue("{$content}","elementContent", $value);
		}elseif(count($elementContentFields) > 1){
			foreach ($elementContentFields as $key => $content) {
				$value = null;
				if(isset($dataObject->content->{$attr})){
					$value = $dataObject->content->{$attr};
				}
				$dataObject->formOption->setValue("{$content}","elementContent", $value);
			}

		}
		// set attrs
		$elementAttrsFields = array("wrapper","id","class");
		foreach ($elementAttrsFields as $key => $attr) {
			$value = null;
			if(isset($dataObject->attrs->{$attr})){
				$value = $dataObject->attrs->{$attr};
			}
			$dataObject->formOption->setValue("{$attr}","elementAttrs", $value);
		}

		$html = '
			<h2><i class="fa fa-font"></i> Text'.(!empty($dataObject->name)? ' - '.$dataObject->name : '').'</h2>
			<div class="row-fluid" style="padding-top:20px;">
				<div class="span5">
					<div class="input-prepend">
						<span class="add-on">Name</span>
						<input class="inputbox" name="elementName" placeholder="Element name" type="text" value="'.(isset($dataObject->name)? $dataObject->name : '').'">
					</div>
				</div>
				<div class="span7">
					<div class="form-horizontal">
						<div class="control-group elementPubLang">
							<div class="control-label">
								<label id="elementPubLang_published-lbl" for="elementPubLang_published">Published</label>
							</div>
							<div class="controls">
								<fieldset id="elementPubLang_published" class="radio btn-group btn-group-yesno">
									<input id="elementPubLang_published1" name="elementPubLang[published]" value="1" '.((isset($dataObject->published) && $dataObject->published == '1')? 'checked="checked"' : '').' type="radio">
									<label  for="elementPubLang_published1">Yes</label>
									<input id="elementPubLang_published0" name="elementPubLang[published]" value="0" '.((isset($dataObject->published) && $dataObject->published == '0')? 'checked="checked"' : '').' type="radio">
									<label for="elementPubLang_published0">No</label>
								</fieldset>
							</div>
						</div>
					</div>


				</div>
			</div>
			<!-- /.row-fluid -->
			<hr>
			<div class="row-fluid">
				<div class="span12">
					<div class="form-horizontal">';

					$html .= '<div class="row-fluid">';
						$html .='<div class="span6">';
						// attrs

					foreach ($dataObject->formOption->getFieldsets('elementAttrs') as $fieldsets => $fieldset) {
						foreach($dataObject->formOption->getFieldset($fieldset->name) as $field){

							if ($field->hidden) {
								$html .= $field->input;
							}else{
								//echo'<pre>';var_dump($field);
								$html .= $field->getControlGroup();
							}
						}
					}

					$html .='</div><div class="span6">';

					// content
					foreach ($dataObject->formOption->getFieldsets('elementContent') as $fieldsets => $fieldset) {
						foreach($dataObject->formOption->getFieldset($fieldset->name) as $field){

							if ($field->hidden) {
								$html .= $field->input;
							}else{
								//echo'<pre>';var_dump($field);
								$html .= $field->getControlGroup();
							}
						}
					}

					$html .='</div>';

				$html .='</div>';
					
					$html .='</div>
					<!-- /.form-horizontal -->
				</div>
				<!-- /.span12 -->

			</div>
			<!-- /.row-fluid -->
			
			<div class="row-fluid" style="text-align: center;">
				<hr>
				<a href="#" id="azura-setting-btn-save" class="btn btn-primary azura-setting-btn-save">Save</a>
				<a href="#" id="azura-setting-btn-cancel" class="btn btn-default azura-setting-btn-cancel">Cancel</a>
			</div>';

			$html .='
			<script>
				jQuery(function($){

					// Turn radios into btn-group
					$(\'.radio.btn-group label\').addClass(\'btn btn-small\');
					$(\'body\').on(\'click\',\'.btn-group label:not(.active)\',function()
					{
						var label = $(this);
						var input = $(\'#\' + label.attr(\'for\'));

						if (!input.prop(\'checked\')) {
							label.closest(\'.btn-group\').find(\'label\').removeClass(\'active btn-success btn-danger btn-primary\');
							if (input.val() == \'\') {
								label.addClass(\'active btn-primary\');
							} else if (input.val() == 0) {
								label.addClass(\'active btn-danger\');
							} else {
								label.addClass(\'active btn-success\');
							}
							input.prop(\'checked\', \'checked\');
						}
					});
					$(\'.btn-group input[checked=checked]\').each(function()
					{
						if ($(this).val() == \'\') {
							$(\'label[for=\' + $(this).attr(\'id\') + \']\').addClass(\'active btn-primary\');
						} else if ($(this).val() == 0) {
							$(\'label[for=\' + $(this).attr(\'id\') + \']\').addClass(\'active btn-danger\');
						} else {
							$(\'label[for=\' + $(this).attr(\'id\') + \']\').addClass(\'active btn-success\');
						}
					});
				});
            
            </script>';
			return $html;
	}

	protected function configAzuraparagraph($data,$dataObject){
		

		$dataObject->formOption = JForm::getInstance('com_azurapagebuilder.page.optionparagraph', 'formoptionparagraph');

		// set content
		$elementContentFields = array("textContent");
		if(count($elementContentFields) == 1) {
			$content = $elementContentFields[0];
			$value = '';
			if(isset($dataObject->content)){
				$value = $dataObject->content;
			}
			$dataObject->formOption->setValue("{$content}","elementContent", $value);
		}elseif(count($elementContentFields) > 1){
			foreach ($elementContentFields as $key => $content) {
				$value = null;
				if(isset($dataObject->content->{$attr})){
					$value = $dataObject->content->{$attr};
				}
				$dataObject->formOption->setValue("{$content}","elementContent", $value);
			}

		}
		// set attrs
		$elementAttrsFields = array("id","class");
		foreach ($elementAttrsFields as $key => $attr) {
			$value = null;
			if(isset($dataObject->attrs->{$attr})){
				$value = $dataObject->attrs->{$attr};
			}
			$dataObject->formOption->setValue("{$attr}","elementAttrs", $value);
		}

		$html = '
			<h2><i class="fa fa-paragraph"></i> Paragraph'.(!empty($dataObject->name)? ' - '.$dataObject->name : '').'</h2>
			<div class="row-fluid" style="padding-top:20px;">
				<div class="span5">
					<div class="input-prepend">
						<span class="add-on">Name</span>
						<input class="inputbox" name="elementName" placeholder="Element name" type="text" value="'.(isset($dataObject->name)? $dataObject->name : '').'">
					</div>
				</div>
				<div class="span7">
					<div class="form-horizontal">
						<div class="control-group elementPubLang">
							<div class="control-label">
								<label id="elementPubLang_published-lbl" for="elementPubLang_published">Published</label>
							</div>
							<div class="controls">
								<fieldset id="elementPubLang_published" class="radio btn-group btn-group-yesno">
									<input id="elementPubLang_published1" name="elementPubLang[published]" value="1" '.((isset($dataObject->published) && $dataObject->published == '1')? 'checked="checked"' : '').' type="radio">
									<label  for="elementPubLang_published1">Yes</label>
									<input id="elementPubLang_published0" name="elementPubLang[published]" value="0" '.((isset($dataObject->published) && $dataObject->published == '0')? 'checked="checked"' : '').' type="radio">
									<label for="elementPubLang_published0">No</label>
								</fieldset>
							</div>
						</div>
					</div>


				</div>
			</div>
			<!-- /.row-fluid -->
			<hr>
			<div class="row-fluid">
				<div class="span12">
					<div class="form-horizontal">';

					$html .= '<div class="row-fluid">';
						$html .='<div class="span6">';
						// attrs

					foreach ($dataObject->formOption->getFieldsets('elementAttrs') as $fieldsets => $fieldset) {
						foreach($dataObject->formOption->getFieldset($fieldset->name) as $field){

							if ($field->hidden) {
								$html .= $field->input;
							}else{
								//echo'<pre>';var_dump($field);
								$html .= $field->getControlGroup();
							}
						}
					}

					$html .='</div><div class="span6">';

					// content
					foreach ($dataObject->formOption->getFieldsets('elementContent') as $fieldsets => $fieldset) {
						foreach($dataObject->formOption->getFieldset($fieldset->name) as $field){

							if ($field->hidden) {
								$html .= $field->input;
							}else{
								//echo'<pre>';var_dump($field);
								$html .= $field->getControlGroup();
							}
						}
					}

					$html .='</div>';

				$html .='</div>';
					
					$html .='</div>
					<!-- /.form-horizontal -->
				</div>
				<!-- /.span12 -->

			</div>
			<!-- /.row-fluid -->
			
			<div class="row-fluid" style="text-align: center;">
				<hr>
				<a href="#" id="azura-setting-btn-save" class="btn btn-primary azura-setting-btn-save">Save</a>
				<a href="#" id="azura-setting-btn-cancel" class="btn btn-default azura-setting-btn-cancel">Cancel</a>
			</div>';

			$html .='
			<script>
				jQuery(function($){

					// Turn radios into btn-group
					$(\'.radio.btn-group label\').addClass(\'btn btn-small\');
					$(\'body\').on(\'click\',\'.btn-group label:not(.active)\',function()
					{
						var label = $(this);
						var input = $(\'#\' + label.attr(\'for\'));

						if (!input.prop(\'checked\')) {
							label.closest(\'.btn-group\').find(\'label\').removeClass(\'active btn-success btn-danger btn-primary\');
							if (input.val() == \'\') {
								label.addClass(\'active btn-primary\');
							} else if (input.val() == 0) {
								label.addClass(\'active btn-danger\');
							} else {
								label.addClass(\'active btn-success\');
							}
							input.prop(\'checked\', \'checked\');
						}
					});
					$(\'.btn-group input[checked=checked]\').each(function()
					{
						if ($(this).val() == \'\') {
							$(\'label[for=\' + $(this).attr(\'id\') + \']\').addClass(\'active btn-primary\');
						} else if ($(this).val() == 0) {
							$(\'label[for=\' + $(this).attr(\'id\') + \']\').addClass(\'active btn-danger\');
						} else {
							$(\'label[for=\' + $(this).attr(\'id\') + \']\').addClass(\'active btn-success\');
						}
					});
				});
            
            </script>';
			return $html;
	}

	protected function configAzurablockquote($data,$dataObject){
		

		$dataObject->formOption = JForm::getInstance('com_azurapagebuilder.page.optionblockquote', 'formoptionblockquote');

		// set content
		$elementContentFields = array("textContent");
		if(count($elementContentFields) == 1) {
			$content = $elementContentFields[0];
			$value = '';
			if(isset($dataObject->content)){
				$value = $dataObject->content;
			}
			$dataObject->formOption->setValue("{$content}","elementContent", $value);
		}elseif(count($elementContentFields) > 1){
			foreach ($elementContentFields as $key => $content) {
				$value = null;
				if(isset($dataObject->content->{$attr})){
					$value = $dataObject->content->{$attr};
				}
				$dataObject->formOption->setValue("{$content}","elementContent", $value);
			}

		}
		// set attrs
		$elementAttrsFields = array("id","class","tooltip","title");
		foreach ($elementAttrsFields as $key => $attr) {
			$value = null;
			if(isset($dataObject->attrs->{$attr})){
				$value = $dataObject->attrs->{$attr};
			}
			$dataObject->formOption->setValue("{$attr}","elementAttrs", $value);
		}

		$html = '
			<h2><i class="fa fa-bold"></i> Blockquote'.(!empty($dataObject->name)? ' - '.$dataObject->name : '').'</h2>
			<div class="row-fluid" style="padding-top:20px;">
				<div class="span5">
					<div class="input-prepend">
						<span class="add-on">Name</span>
						<input class="inputbox" name="elementName" placeholder="Element name" type="text" value="'.(isset($dataObject->name)? $dataObject->name : '').'">
					</div>
				</div>
				<div class="span7">
					<div class="form-horizontal">
						<div class="control-group elementPubLang">
							<div class="control-label">
								<label id="elementPubLang_published-lbl" for="elementPubLang_published">Published</label>
							</div>
							<div class="controls">
								<fieldset id="elementPubLang_published" class="radio btn-group btn-group-yesno">
									<input id="elementPubLang_published1" name="elementPubLang[published]" value="1" '.((isset($dataObject->published) && $dataObject->published == '1')? 'checked="checked"' : '').' type="radio">
									<label  for="elementPubLang_published1">Yes</label>
									<input id="elementPubLang_published0" name="elementPubLang[published]" value="0" '.((isset($dataObject->published) && $dataObject->published == '0')? 'checked="checked"' : '').' type="radio">
									<label for="elementPubLang_published0">No</label>
								</fieldset>
							</div>
						</div>
					</div>


				</div>
			</div>
			<!-- /.row-fluid -->
			<hr>
			<div class="row-fluid">
				<div class="span12">
					<div class="form-horizontal">';

					$html .= '<div class="row-fluid">';
						$html .='<div class="span6">';
						// attrs

					foreach ($dataObject->formOption->getFieldsets('elementAttrs') as $fieldsets => $fieldset) {
						foreach($dataObject->formOption->getFieldset($fieldset->name) as $field){

							if ($field->hidden) {
								$html .= $field->input;
							}else{
								//echo'<pre>';var_dump($field);
								$html .= $field->getControlGroup();
							}
						}
					}

					$html .='</div><div class="span6">';

					// content
					foreach ($dataObject->formOption->getFieldsets('elementContent') as $fieldsets => $fieldset) {
						foreach($dataObject->formOption->getFieldset($fieldset->name) as $field){

							if ($field->hidden) {
								$html .= $field->input;
							}else{
								//echo'<pre>';var_dump($field);
								$html .= $field->getControlGroup();
							}
						}
					}

					$html .='</div>';

				$html .='</div>';
					
					$html .='</div>
					<!-- /.form-horizontal -->
				</div>
				<!-- /.span12 -->

			</div>
			<!-- /.row-fluid -->
			
			<div class="row-fluid" style="text-align: center;">
				<hr>
				<a href="#" id="azura-setting-btn-save" class="btn btn-primary azura-setting-btn-save">Save</a>
				<a href="#" id="azura-setting-btn-cancel" class="btn btn-default azura-setting-btn-cancel">Cancel</a>
			</div>';

			$html .='
			<script>
				jQuery(function($){

					// Turn radios into btn-group
					$(\'.radio.btn-group label\').addClass(\'btn btn-small\');
					$(\'body\').on(\'click\',\'.btn-group label:not(.active)\',function()
					{
						var label = $(this);
						var input = $(\'#\' + label.attr(\'for\'));

						if (!input.prop(\'checked\')) {
							label.closest(\'.btn-group\').find(\'label\').removeClass(\'active btn-success btn-danger btn-primary\');
							if (input.val() == \'\') {
								label.addClass(\'active btn-primary\');
							} else if (input.val() == 0) {
								label.addClass(\'active btn-danger\');
							} else {
								label.addClass(\'active btn-success\');
							}
							input.prop(\'checked\', \'checked\');
						}
					});
					$(\'.btn-group input[checked=checked]\').each(function()
					{
						if ($(this).val() == \'\') {
							$(\'label[for=\' + $(this).attr(\'id\') + \']\').addClass(\'active btn-primary\');
						} else if ($(this).val() == 0) {
							$(\'label[for=\' + $(this).attr(\'id\') + \']\').addClass(\'active btn-danger\');
						} else {
							$(\'label[for=\' + $(this).attr(\'id\') + \']\').addClass(\'active btn-success\');
						}
					});
				});
            
            </script>';
			return $html;
	}
	
	protected function configAzuraheader($data,$dataObject){
		

		$dataObject->formOption = JForm::getInstance('com_azurapagebuilder.page.optionheader', 'formoptionheader');

		// set content
		$elementContentFields = array("headerContent");
		if(count($elementContentFields) == 1) {
			$content = $elementContentFields[0];
			$value = '';
			if(isset($dataObject->content)){
				$value = $dataObject->content;
			}
			$dataObject->formOption->setValue("{$content}","elementContent", $value);
		}elseif(count($elementContentFields) > 1){
			foreach ($elementContentFields as $key => $content) {
				$value = null;
				if(isset($dataObject->content->{$attr})){
					$value = $dataObject->content->{$attr};
				}
				$dataObject->formOption->setValue("{$content}","elementContent", $value);
			}

		}
		// set attrs
		$elementAttrsFields = array("id","class","heading","style");
		foreach ($elementAttrsFields as $key => $attr) {
			$value = null;
			if(isset($dataObject->attrs->{$attr})){
				$value = $dataObject->attrs->{$attr};
			}
			$dataObject->formOption->setValue("{$attr}","elementAttrs", $value);
		}

		$html = '
			<h2><i class="fa fa-header"></i> Header'.(!empty($dataObject->name)? ' - '.$dataObject->name : '').'</h2>
			<div class="row-fluid" style="padding-top:20px;">
				<div class="span5">
					<div class="input-prepend">
						<span class="add-on">Name</span>
						<input class="inputbox" name="elementName" placeholder="Element name" type="text" value="'.(isset($dataObject->name)? $dataObject->name : '').'">
					</div>
				</div>
				<div class="span7">
					<div class="form-horizontal">
						<div class="control-group elementPubLang">
							<div class="control-label">
								<label id="elementPubLang_published-lbl" for="elementPubLang_published">Published</label>
							</div>
							<div class="controls">
								<fieldset id="elementPubLang_published" class="radio btn-group btn-group-yesno">
									<input id="elementPubLang_published1" name="elementPubLang[published]" value="1" '.((isset($dataObject->published) && $dataObject->published == '1')? 'checked="checked"' : '').' type="radio">
									<label  for="elementPubLang_published1">Yes</label>
									<input id="elementPubLang_published0" name="elementPubLang[published]" value="0" '.((isset($dataObject->published) && $dataObject->published == '0')? 'checked="checked"' : '').' type="radio">
									<label for="elementPubLang_published0">No</label>
								</fieldset>
							</div>
						</div>
					</div>

				</div>
			</div>
			<!-- /.row-fluid -->
			<hr>
			<div class="row-fluid">
				<div class="span12">
					<div class="form-horizontal">';

					

					// attrs

					foreach ($dataObject->formOption->getFieldsets('elementAttrs') as $fieldsets => $fieldset) {
						foreach($dataObject->formOption->getFieldset($fieldset->name) as $field){

							if ($field->hidden) {
								$html .= $field->input;
							}else{
								//echo'<pre>';var_dump($field);
								$html .= $field->getControlGroup();
							}
						}
					}

					// content
					foreach ($dataObject->formOption->getFieldsets('elementContent') as $fieldsets => $fieldset) {
						foreach($dataObject->formOption->getFieldset($fieldset->name) as $field){

							if ($field->hidden) {
								$html .= $field->input;
							}else{
								//echo'<pre>';var_dump($field);
								$html .= $field->getControlGroup();
							}
						}
					}
					
					$html .='</div>
					<!-- /.form-horizontal -->
				</div>
				<!-- /.span12 -->

			</div>
			<!-- /.row-fluid -->
			
			<div class="row-fluid" style="text-align: center;">
				<hr>
				<a href="#" id="azura-setting-btn-save" class="btn btn-primary azura-setting-btn-save">Save</a>
				<a href="#" id="azura-setting-btn-cancel" class="btn btn-default azura-setting-btn-cancel">Cancel</a>
			</div>';

			$html .='
			<script>
				jQuery(function($){

					// Turn radios into btn-group
					$(\'.radio.btn-group label\').addClass(\'btn btn-small\');
					$(\'body\').on(\'click\',\'.btn-group label:not(.active)\',function()
					{
						var label = $(this);
						var input = $(\'#\' + label.attr(\'for\'));

						if (!input.prop(\'checked\')) {
							label.closest(\'.btn-group\').find(\'label\').removeClass(\'active btn-success btn-danger btn-primary\');
							if (input.val() == \'\') {
								label.addClass(\'active btn-primary\');
							} else if (input.val() == 0) {
								label.addClass(\'active btn-danger\');
							} else {
								label.addClass(\'active btn-success\');
							}
							input.prop(\'checked\', \'checked\');
						}
					});
					$(\'.btn-group input[checked=checked]\').each(function()
					{
						if ($(this).val() == \'\') {
							$(\'label[for=\' + $(this).attr(\'id\') + \']\').addClass(\'active btn-primary\');
						} else if ($(this).val() == 0) {
							$(\'label[for=\' + $(this).attr(\'id\') + \']\').addClass(\'active btn-danger\');
						} else {
							$(\'label[for=\' + $(this).attr(\'id\') + \']\').addClass(\'active btn-success\');
						}
					});
				});
            
            </script>';
			
			return $html;
	}

	protected function configAzuraseparator($data,$dataObject){
		

		$dataObject->formOption = JForm::getInstance('com_azurapagebuilder.page.optionseparator', 'formoptionseparator');

		
		// set attrs
		$elementAttrsFields = array("wrapper","id","class","text","iconClass");
		foreach ($elementAttrsFields as $key => $attr) {
			$value = null;
			if(isset($dataObject->attrs->{$attr})){
				$value = $dataObject->attrs->{$attr};
			}
			$dataObject->formOption->setValue("{$attr}","elementAttrs", $value);
		}

		$html = '
			<h2><i class="fa fa-minus"></i> Separator'.(!empty($dataObject->name)? ' - '.$dataObject->name : '').'</h2>
			<div class="row-fluid" style="padding-top:20px;">
				<div class="span5">
					<div class="input-prepend">
						<span class="add-on">Name</span>
						<input class="inputbox" name="elementName" placeholder="Element name" type="text" value="'.(isset($dataObject->name)? $dataObject->name : '').'">
					</div>
				</div>
				<div class="span7">
					<div class="form-horizontal">
						<div class="control-group elementPubLang">
							<div class="control-label">
								<label id="elementPubLang_published-lbl" for="elementPubLang_published">Published</label>
							</div>
							<div class="controls">
								<fieldset id="elementPubLang_published" class="radio btn-group btn-group-yesno">
									<input id="elementPubLang_published1" name="elementPubLang[published]" value="1" '.((isset($dataObject->published) && $dataObject->published == '1')? 'checked="checked"' : '').' type="radio">
									<label  for="elementPubLang_published1">Yes</label>
									<input id="elementPubLang_published0" name="elementPubLang[published]" value="0" '.((isset($dataObject->published) && $dataObject->published == '0')? 'checked="checked"' : '').' type="radio">
									<label for="elementPubLang_published0">No</label>
								</fieldset>
							</div>
						</div>
					</div>


				</div>
			</div>
			<!-- /.row-fluid -->
			<hr>
			<div class="row-fluid">
				<div class="span12">
					<div class="form-horizontal">';

					// attrs

					foreach ($dataObject->formOption->getFieldsets('elementAttrs') as $fieldsets => $fieldset) {
						foreach($dataObject->formOption->getFieldset($fieldset->name) as $field){

							if ($field->hidden) {
								$html .= $field->input;
							}else{
								//echo'<pre>';var_dump($field);
								$html .= $field->getControlGroup();
							}
						}
					}
					
					$html .='</div>
					<!-- /.form-horizontal -->
				</div>
				<!-- /.span12 -->

			</div>
			<!-- /.row-fluid -->
			
			<div class="row-fluid" style="text-align: center;">
				<hr>
				<a href="#" id="azura-setting-btn-save" class="btn btn-primary azura-setting-btn-save">Save</a>
				<a href="#" id="azura-setting-btn-cancel" class="btn btn-default azura-setting-btn-cancel">Cancel</a>
			</div>';

			$html .='
			<script>
				function jInsertIconClassValue(value, id) {
		        		var old_value = jQuery("#" + id).val();
		        		if (old_value != value) {
		        			var $elem = jQuery("#" + id);
		        			$elem.val(value);
		        			$elem.trigger("change");
		        			if (typeof($elem.get(0).onchange) === "function") {
		        				$elem.get(0).onchange();
		        			}
		        		}
		        }

	            jQuery(function($) {
	    			SqueezeBox.initialize({});
	    			SqueezeBox.assign($(\'a.modal_jform_azurafont\').get(), {
	    				parse: \'rel\'
	    			});
					
	    		});
            
            </script>';

			$html .='
			<script>
				jQuery(function($){

					$(\'.radio.btn-group label\').addClass(\'btn btn-small\');
					$(\'body\').on(\'click\',\'.btn-group label:not(.active)\',function()
					{
						var label = $(this);
						var input = $(\'#\' + label.attr(\'for\'));

						if (!input.prop(\'checked\')) {
							label.closest(\'.btn-group\').find(\'label\').removeClass(\'active btn-success btn-danger btn-primary\');
							if (input.val() == \'\') {
								label.addClass(\'active btn-primary\');
							} else if (input.val() == 0) {
								label.addClass(\'active btn-danger\');
							} else {
								label.addClass(\'active btn-success\');
							}
							input.prop(\'checked\', \'checked\');
						}
					});
					$(\'.btn-group input[checked=checked]\').each(function()
					{
						if ($(this).val() == \'\') {
							$(\'label[for=\' + $(this).attr(\'id\') + \']\').addClass(\'active btn-primary\');
						} else if ($(this).val() == 0) {
							$(\'label[for=\' + $(this).attr(\'id\') + \']\').addClass(\'active btn-danger\');
						} else {
							$(\'label[for=\' + $(this).attr(\'id\') + \']\').addClass(\'active btn-success\');
						}
					});
				});
            
            </script>';

			return $html;
	}

	protected function configAzuracarouselslideritem($data,$dataObject){
		

		$dataObject->formOption = JForm::getInstance('com_azurapagebuilder.page.optioncarouselslideritem', 'formoptioncarouselslideritem');
		$elementAttrsFields = array("id","class","slideImage");
		foreach ($elementAttrsFields as $key => $attr) {
			$value = null;
			if(isset($dataObject->attrs->{$attr})){
				$value = $dataObject->attrs->{$attr};
			}
			$dataObject->formOption->setValue("{$attr}","elementAttrs", $value);
		}

		$html = '
			<h2><i class="fa fa-sliders"></i> Slide'.(!empty($dataObject->name)? ' - '.$dataObject->name : '').'</h2>
			<div class="row-fluid" style="padding-top:20px;">
				<div class="span5">
					<div class="input-prepend">
						<span class="add-on">Name</span>
						<input class="inputbox" name="elementName" placeholder="Element name" type="text" value="'.(isset($dataObject->name)? $dataObject->name : '').'">
					</div>
				</div>
				<div class="span7">
					<div class="form-horizontal">
						<div class="control-group elementPubLang">
							<div class="control-label">
								<label id="elementPubLang_published-lbl" for="elementPubLang_published">Published</label>
							</div>
							<div class="controls">
								<fieldset id="elementPubLang_published" class="radio btn-group btn-group-yesno">
									<input id="elementPubLang_published1" name="elementPubLang[published]" value="1" '.((isset($dataObject->published) && $dataObject->published == '1')? 'checked="checked"' : '').' type="radio">
									<label  for="elementPubLang_published1">Yes</label>
									<input id="elementPubLang_published0" name="elementPubLang[published]" value="0" '.((isset($dataObject->published) && $dataObject->published == '0')? 'checked="checked"' : '').' type="radio">
									<label for="elementPubLang_published0">No</label>
								</fieldset>
							</div>
						</div>
					</div>

				</div>
			</div>
			<!-- /.row-fluid -->
			<hr>
			<div class="row-fluid">
				<div class="span12">
					<div class="form-horizontal">';


					foreach ($dataObject->formOption->getFieldsets('elementAttrs') as $fieldsets => $fieldset) {
						foreach($dataObject->formOption->getFieldset($fieldset->name) as $field){

							if ($field->hidden) {
								$html .= $field->input;
							}else{
								//echo'<pre>';var_dump($field);
								$html .= $field->getControlGroup();
							}
						}
					}
					
					$html .='</div>
					<!-- /.form-horizontal -->
				</div>
				<!-- /.span12 -->

			</div>
			<!-- /.row-fluid -->
			
			<div class="row-fluid" style="text-align: center;">
				<hr>
				<a href="#" id="azura-setting-btn-save" class="btn btn-primary azura-setting-btn-save">Save</a>
				<a href="#" id="azura-setting-btn-cancel" class="btn btn-default azura-setting-btn-cancel">Cancel</a>
			</div>';

			$html .='
			<script>
				function jInsertFieldValue(value, id) {
		        		var old_value = jQuery("#" + id).val();
		        		if (old_value != value) {
		        			var $elem = jQuery("#" + id);
		        			$elem.val(value);
		        			$elem.trigger("change");
		        			if (typeof($elem.get(0).onchange) === "function") {
		        				$elem.get(0).onchange();
		        			}
		        		}
		        }

	            jQuery(function($) {
	    			SqueezeBox.initialize({});
	    			SqueezeBox.assign($(\'a.modal_jform_azuramedia\').get(), {
	    				parse: \'rel\'
	    			});
					
					
	                
	                jQuery(\'body\').on(\'change\',\'#elementAttrs_slideImage\', function(event){
	                    event.preventDefault();
	                    var value = event.currentTarget.value;
	                    
	                    jQuery(\'.fancybox-inner\').find(\'#elementAttrs_slideImage\').val(value);
	                });
	    		});
            
            </script>';

            $html .='
			<script>
				jQuery(function($){

					// Turn radios into btn-group
					$(\'.radio.btn-group label\').addClass(\'btn btn-small\');
					$(\'body\').on(\'click\',\'.btn-group label:not(.active)\',function()
					{
						var label = $(this);
						var input = $(\'#\' + label.attr(\'for\'));

						if (!input.prop(\'checked\')) {
							label.closest(\'.btn-group\').find(\'label\').removeClass(\'active btn-success btn-danger btn-primary\');
							if (input.val() == \'\') {
								label.addClass(\'active btn-primary\');
							} else if (input.val() == 0) {
								label.addClass(\'active btn-danger\');
							} else {
								label.addClass(\'active btn-success\');
							}
							input.prop(\'checked\', \'checked\');
						}
					});
					$(\'.btn-group input[checked=checked]\').each(function()
					{
						if ($(this).val() == \'\') {
							$(\'label[for=\' + $(this).attr(\'id\') + \']\').addClass(\'active btn-primary\');
						} else if ($(this).val() == 0) {
							$(\'label[for=\' + $(this).attr(\'id\') + \']\').addClass(\'active btn-danger\');
						} else {
							$(\'label[for=\' + $(this).attr(\'id\') + \']\').addClass(\'active btn-success\');
						}
					});
				});
            
            </script>';

			return $html;
	}


	protected function configAzuraimage($data,$dataObject){
		

		$dataObject->formOption = JForm::getInstance('com_azurapagebuilder.page.optionimage', 'formoptionimage');

		// set content
		$elementContentFields = array("src");
		if(count($elementContentFields) == 1) {
			$content = $elementContentFields[0];
			$value = '';
			if(isset($dataObject->content)){
				$value = $dataObject->content;
			}
			$dataObject->formOption->setValue("{$content}","elementContent", $value);
		}elseif(count($elementContentFields) > 1){
			foreach ($elementContentFields as $key => $content) {
				$value = null;
				if(isset($dataObject->content->{$attr})){
					$value = $dataObject->content->{$attr};
				}
				$dataObject->formOption->setValue("{$content}","elementContent", $value);
			}

		}

		// set attrs
		// $elementAttrsFields = array("title","id","class");
		// foreach ($elementAttrsFields as $key => $attr) {
		// 	$value = null;
		// 	if(isset($dataObject->attrs->{$attr})){
		// 		$value = $dataObject->attrs->{$attr};
		// 	}
		// 	$dataObject->formOption->setValue("{$attr}","elementAttrs", $value);
		// }

		

		$html = '
			<h2><i class="fa fa-image"></i> Image'.(!empty($dataObject->name)? ' - '.$dataObject->name : '').'</h2>
			<div class="row-fluid" style="padding-top:20px;">
				<div class="span5">
					<div class="input-prepend">
						<span class="add-on">Name</span>
						<input class="inputbox" name="elementName" placeholder="Element name" type="text" value="'.(isset($dataObject->name)? $dataObject->name : '').'">
					</div>
				</div>
				<div class="span7">
					<div class="form-horizontal">
						<div class="control-group elementPubLang">
							<div class="control-label">
								<label id="elementPubLang_published-lbl" for="elementPubLang_published">Published</label>
							</div>
							<div class="controls">
								<fieldset id="elementPubLang_published" class="radio btn-group btn-group-yesno">
									<input id="elementPubLang_published1" name="elementPubLang[published]" value="1" '.((isset($dataObject->published) && $dataObject->published == '1')? 'checked="checked"' : '').' type="radio">
									<label  for="elementPubLang_published1">Yes</label>
									<input id="elementPubLang_published0" name="elementPubLang[published]" value="0" '.((isset($dataObject->published) && $dataObject->published == '0')? 'checked="checked"' : '').' type="radio">
									<label for="elementPubLang_published0">No</label>
								</fieldset>
							</div>
						</div>
					</div>


				</div>
			</div>
			<!-- /.row-fluid -->

			<div id="azp_tabs">
				 <ul>
					<li><a href="#azp_tab_option">Options</a></li>
					<li><a href="#azp_tab_style">Styles</a></li>
				</ul>
				<div class="row-fluid" id="azp_tab_option">
					<div class="span12">
						<div class="form-horizontal">';

						// content
						foreach ($dataObject->formOption->getFieldsets('elementContent') as $fieldsets => $fieldset) {
							foreach($dataObject->formOption->getFieldset($fieldset->name) as $field){

								// $attr = $field->name;

								// if(isset($dataObject->attrs->{$attr})){
								// 	$value = $dataObject->attrs->{$attr};
								// 	$field->setValue($value);
								// }

								if ($field->hidden) {
									$html .= $field->input;
								}else{
									//echo'<pre>';var_dump($field->name);
									$html .= $field->getControlGroup();
								}
							}
						}

						// attrs

						foreach ($dataObject->formOption->getFieldsets('elementAttrs') as $fieldsets => $fieldset) {
							foreach($dataObject->formOption->getFieldset($fieldset->name) as $field){

								preg_match('/elementAttrs\[(.+)\]/', $field->name, $matches);

								if(count($matches) > 1){
									$attr = $matches[1];

									if(isset($dataObject->attrs->{$attr})){
										$value = $dataObject->attrs->{$attr};
										$field->setValue($value);
									}
								}

								if ($field->hidden) {
									$html .= $field->input;
								}else{
									//echo'<pre>';var_dump($field->name);
									$html .= $field->getControlGroup();
								}
							}
						}
						
						$html .='</div>
						<!-- /.form-horizontal -->
					</div>
					<!-- /.span12 -->

				</div>
				<!-- /.row-fluid -->

				<div class="row-fluid" id="azp_tab_style">
					'.CthShortcodes::renderElementStyle($dataObject->attrs).'

					

				</div>

				<!-- /.row-fluid -->

			</div>

			<!-- /#azp_Tabs -->
			
			<div class="row-fluid" style="text-align: center;">
				<hr>
				<a href="#" id="azura-setting-btn-save" class="btn btn-primary azura-setting-btn-save">Save</a>
				<a href="#" id="azura-setting-btn-cancel" class="btn btn-default azura-setting-btn-cancel">Cancel</a>
			</div>';

			$html .='
			<script>
				function jInsertFieldValue(value, id) {
		        		var old_value = jQuery("#" + id).val();
		        		if (old_value != value) {
		        			var $elem = jQuery("#" + id);
		        			$elem.val(value);
		        			$elem.trigger("change");
		        			if (typeof($elem.get(0).onchange) === "function") {
		        				$elem.get(0).onchange();
		        			}
		        		}
		        }

	            jQuery(function($) {
	    			SqueezeBox.initialize({});
	    			SqueezeBox.assign($(\'a.modal_jform_azuramedia\').get(), {
	    				parse: \'rel\'
	    			});
					
					
	                
	                jQuery(\'body\').on(\'change\',\'#elementAttrs_src\', function(event){
	                    event.preventDefault();
	                    var value = event.currentTarget.value;
	                    
	                    jQuery(\'.fancybox-inner\').find(\'#elementAttrs_src\').val(value);
	                });
	    		});
            
            </script>';

			$html .='
			<script>
				jQuery(function($){
					$( "#azp_tabs" ).tabs();

					$(\'.radio.btn-group label\').addClass(\'btn btn-small\');
					$(\'body\').on(\'click\',\'.btn-group label:not(.active)\',function()
					{
						var label = $(this);
						var input = $(\'#\' + label.attr(\'for\'));

						if (!input.prop(\'checked\')) {
							label.closest(\'.btn-group\').find(\'label\').removeClass(\'active btn-success btn-danger btn-primary\');
							if (input.val() == \'\') {
								label.addClass(\'active btn-primary\');
							} else if (input.val() == 0) {
								label.addClass(\'active btn-danger\');
							} else {
								label.addClass(\'active btn-success\');
							}
							input.prop(\'checked\', \'checked\');
						}
					});
					$(\'.btn-group input[checked=checked]\').each(function()
					{
						if ($(this).val() == \'\') {
							$(\'label[for=\' + $(this).attr(\'id\') + \']\').addClass(\'active btn-primary\');
						} else if ($(this).val() == 0) {
							$(\'label[for=\' + $(this).attr(\'id\') + \']\').addClass(\'active btn-danger\');
						} else {
							$(\'label[for=\' + $(this).attr(\'id\') + \']\').addClass(\'active btn-success\');
						}
					});
				});
            
            </script>';

			return $html;
	}

	protected function configAzuracarouselslider($data,$dataObject){
		

		$dataObject->formOption = JForm::getInstance('com_azurapagebuilder.page.optioncarouselslider', 'formoptioncarouselslider');

		
		// set attrs
		$elementAttrsFields = array("id","class","tranStyle","sliderSpeed","rewindSpeed","slidePerView","itemsCustom","autoPlay","pagination","paginationSpeed","navigation","autoHeight","mouseDrag","touchDrag","layout");
		foreach ($elementAttrsFields as $key => $attr) {
			$value = null;
			if(isset($dataObject->attrs->{$attr})){
				$value = $dataObject->attrs->{$attr};
			}
			$dataObject->formOption->setValue("{$attr}","elementAttrs", $value);
		}

		$html = '
			<h2><i class="fa fa-sliders"></i> Carousel Slider'.(!empty($dataObject->name)? ' - '.$dataObject->name : '').'</h2>
			<div class="row-fluid" style="padding-top:20px;">
				<div class="span5">
					<div class="input-prepend">
						<span class="add-on">Name</span>
						<input class="inputbox" name="elementName" placeholder="Element name" type="text" value="'.(isset($dataObject->name)? $dataObject->name : '').'">
					</div>
				</div>
				<div class="span7">
					<div class="form-horizontal">
						<div class="control-group elementPubLang">
							<div class="control-label">
								<label id="elementPubLang_published-lbl" for="elementPubLang_published">Published</label>
							</div>
							<div class="controls">
								<fieldset id="elementPubLang_published" class="radio btn-group btn-group-yesno">
									<input id="elementPubLang_published1" name="elementPubLang[published]" value="1" '.((isset($dataObject->published) && $dataObject->published == '1')? 'checked="checked"' : '').' type="radio">
									<label  for="elementPubLang_published1">Yes</label>
									<input id="elementPubLang_published0" name="elementPubLang[published]" value="0" '.((isset($dataObject->published) && $dataObject->published == '0')? 'checked="checked"' : '').' type="radio">
									<label for="elementPubLang_published0">No</label>
								</fieldset>
							</div>
						</div>
					</div>


				</div>
			</div>
			<!-- /.row-fluid -->
			<hr>
			<div class="row-fluid">
				<div class="span12">
					<div class="form-horizontal">';

					// attrs

					foreach ($dataObject->formOption->getFieldsets('elementAttrs') as $fieldsets => $fieldset) {
						$html .= '<div class="row-fluid">';
							$html .='<div class="span6">';
							$key = 1;
							$owlfields = $dataObject->formOption->getFieldset($fieldset->name);
						foreach($owlfields as $field){

							if ($field->hidden) {
								$html .= $field->input;
							}else{
								//echo'<pre>';var_dump($field);
								$html .= $field->getControlGroup();
								$key++;
							}

							if($key == 8 && count($owlfields) >8){
								$html .= '</div><div class="span6">';
							}
						}

							$html .='</div>';
						$html .='</div>';
					}
					
					$html .='</div>
					<!-- /.form-horizontal -->
				</div>
				<!-- /.span12 -->

			</div>
			<!-- /.row-fluid -->
			
			<div class="row-fluid" style="text-align: center;">
				<hr>
				<a href="#" id="azura-setting-btn-save" class="btn btn-primary azura-setting-btn-save">Save</a>
				<a href="#" id="azura-setting-btn-cancel" class="btn btn-default azura-setting-btn-cancel">Cancel</a>
			</div>';

			$html .='
			<script>
				jQuery(function($){

					// Turn radios into btn-group
					$(\'.radio.btn-group label\').addClass(\'btn btn-small\');
					$(\'body\').on(\'click\',\'.btn-group label:not(.active)\',function()
					{
						var label = $(this);
						var input = $(\'#\' + label.attr(\'for\'));

						if (!input.prop(\'checked\')) {
							label.closest(\'.btn-group\').find(\'label\').removeClass(\'active btn-success btn-danger btn-primary\');
							if (input.val() == \'\') {
								label.addClass(\'active btn-primary\');
							} else if (input.val() == 0) {
								label.addClass(\'active btn-danger\');
							} else {
								label.addClass(\'active btn-success\');
							}
							input.prop(\'checked\', \'checked\');
						}
					});
					$(\'.btn-group input[checked=checked]\').each(function()
					{
						if ($(this).val() == \'\') {
							$(\'label[for=\' + $(this).attr(\'id\') + \']\').addClass(\'active btn-primary\');
						} else if ($(this).val() == 0) {
							$(\'label[for=\' + $(this).attr(\'id\') + \']\').addClass(\'active btn-danger\');
						} else {
							$(\'label[for=\' + $(this).attr(\'id\') + \']\').addClass(\'active btn-success\');
						}
					});
				});
            
            </script>';
			
			return $html;
	}

	protected function configAzuragrid($data,$dataObject){
		

		$dataObject->formOption = JForm::getInstance('com_azurapagebuilder.page.optiongrid', 'formoptiongrid');

		// set attrs
		$elementAttrsFields = array("id","extraClass","wrapperTag");
		foreach ($elementAttrsFields as $key => $attr) {
			$value = null;
			if(isset($dataObject->attrs->{$attr})){
				$value = $dataObject->attrs->{$attr};
			}
			$dataObject->formOption->setValue("{$attr}","elementAttrs", $value);
		}

		$html = '
			<h2><i class="fa fa-columns"></i> Grid'.(!empty($dataObject->name)? ' - '.$dataObject->name : '').'</h2>
			<div class="row-fluid" style="padding-top:20px;">
				<div class="span5">
					<div class="input-prepend">
						<span class="add-on">Name</span>
						<input class="inputbox" name="elementName" placeholder="Element name" type="text" value="'.(isset($dataObject->name)? $dataObject->name : '').'">
					</div>
				</div>
				<div class="span7">
					<div class="form-horizontal">
						<div class="control-group elementPubLang">
							<div class="control-label">
								<label id="elementPubLang_published-lbl" for="elementPubLang_published">Published</label>
							</div>
							<div class="controls">
								<fieldset id="elementPubLang_published" class="radio btn-group btn-group-yesno">
									<input id="elementPubLang_published1" name="elementPubLang[published]" value="1" '.((isset($dataObject->published) && $dataObject->published == '1')? 'checked="checked"' : '').' type="radio">
									<label  for="elementPubLang_published1">Yes</label>
									<input id="elementPubLang_published0" name="elementPubLang[published]" value="0" '.((isset($dataObject->published) && $dataObject->published == '0')? 'checked="checked"' : '').' type="radio">
									<label for="elementPubLang_published0">No</label>
								</fieldset>
							</div>
						</div>
					</div>


				</div>
			</div>
			<!-- /.row-fluid -->
			<hr>
			<div class="row-fluid">
				<div class="span12">
					<div class="form-horizontal">';
					// attrs

					foreach ($dataObject->formOption->getFieldsets('elementAttrs') as $fieldsets => $fieldset) {
						foreach($dataObject->formOption->getFieldset($fieldset->name) as $field){

							if ($field->hidden) {
								$html .= $field->input;
							}else{
								//echo'<pre>';var_dump($field);
								$html .= $field->getControlGroup();
							}
						}
					}
					
					$html .='</div>
					<!-- /.form-horizontal -->
				</div>
				<!-- /.span12 -->

			</div>
			<!-- /.row-fluid -->
			
			<div class="row-fluid" style="text-align: center;">
				<hr>
				<a href="#" id="azura-setting-btn-save" class="btn btn-primary azura-setting-btn-save">Save</a>
				<a href="#" id="azura-setting-btn-cancel" class="btn btn-default azura-setting-btn-cancel">Cancel</a>
			</div>';

			$html .='
			<script>
				jQuery(function($){

					// Turn radios into btn-group
					$(\'.radio.btn-group label\').addClass(\'btn btn-small\');
					$(\'body\').on(\'click\',\'.btn-group label:not(.active)\',function()
					{
						var label = $(this);
						var input = $(\'#\' + label.attr(\'for\'));

						if (!input.prop(\'checked\')) {
							label.closest(\'.btn-group\').find(\'label\').removeClass(\'active btn-success btn-danger btn-primary\');
							if (input.val() == \'\') {
								label.addClass(\'active btn-primary\');
							} else if (input.val() == 0) {
								label.addClass(\'active btn-danger\');
							} else {
								label.addClass(\'active btn-success\');
							}
							input.prop(\'checked\', \'checked\');
						}
					});
					$(\'.btn-group input[checked=checked]\').each(function()
					{
						if ($(this).val() == \'\') {
							$(\'label[for=\' + $(this).attr(\'id\') + \']\').addClass(\'active btn-primary\');
						} else if ($(this).val() == 0) {
							$(\'label[for=\' + $(this).attr(\'id\') + \']\').addClass(\'active btn-danger\');
						} else {
							$(\'label[for=\' + $(this).attr(\'id\') + \']\').addClass(\'active btn-success\');
						}
					});
				});
            
            </script>';

			return $html;
	}

	protected function configAzuracolumn($data,$dataObject){
		

		$dataObject->formOption = JForm::getInstance('com_azurapagebuilder.page.optioncolumn', 'formoptioncolumn');

		// set attrs
		$elementAttrsFields = array("id","class","customStyle","layout","columnwidthclass");
		foreach ($elementAttrsFields as $key => $attr) {
			$value = null;
			if(isset($dataObject->attrs->{$attr})){
				$value = $dataObject->attrs->{$attr};
			}
			$dataObject->formOption->setValue("{$attr}","elementAttrs", $value);
		}

		$html = '
			<h2><i class="fa fa-columns"></i> Column'.(!empty($dataObject->name)? ' - '.$dataObject->name : '').'</h2>
			<div class="row-fluid" style="padding-top:20px;">
				<div class="span5">
					<div class="input-prepend">
						<span class="add-on">Name</span>
						<input class="inputbox" name="elementName" placeholder="Element name" type="text" value="'.(isset($dataObject->name)? $dataObject->name : '').'">
					</div>
				</div>
				<div class="span7">
					<div class="form-horizontal">
						<div class="control-group elementPubLang">
							<div class="control-label">
								<label id="elementPubLang_published-lbl" for="elementPubLang_published">Published</label>
							</div>
							<div class="controls">
								<fieldset id="elementPubLang_published" class="radio btn-group btn-group-yesno">
									<input id="elementPubLang_published1" name="elementPubLang[published]" value="1" '.((isset($dataObject->published) && $dataObject->published == '1')? 'checked="checked"' : '').' type="radio">
									<label  for="elementPubLang_published1">Yes</label>
									<input id="elementPubLang_published0" name="elementPubLang[published]" value="0" '.((isset($dataObject->published) && $dataObject->published == '0')? 'checked="checked"' : '').' type="radio">
									<label for="elementPubLang_published0">No</label>
								</fieldset>
							</div>
						</div>
					</div>

				</div>
			</div>
			<!-- /.row-fluid -->
			<hr>
			<div class="row-fluid">
				<div class="span12">
					<div class="form-horizontal">';
					// attrs

					foreach ($dataObject->formOption->getFieldsets('elementAttrs') as $fieldsets => $fieldset) {
						foreach($dataObject->formOption->getFieldset($fieldset->name) as $field){

							if ($field->hidden) {
								$html .= $field->input;
							}else{
								//echo'<pre>';var_dump($field);
								$html .= $field->getControlGroup();
							}
						}
					}
					
					$html .='</div>
					<!-- /.form-horizontal -->
				</div>
				<!-- /.span12 -->

			</div>
			<!-- /.row-fluid -->
			
			<div class="row-fluid" style="text-align: center;">
				<hr>
				<a href="#" id="azura-setting-btn-save" class="btn btn-primary azura-setting-btn-save">Save</a>
				<a href="#" id="azura-setting-btn-cancel" class="btn btn-default azura-setting-btn-cancel">Cancel</a>
			</div>';

			$html .='
			<script>
				jQuery(function($){

					// Turn radios into btn-group
					$(\'.radio.btn-group label\').addClass(\'btn btn-small\');
					$(\'body\').on(\'click\',\'.btn-group label:not(.active)\',function()
					{
						var label = $(this);
						var input = $(\'#\' + label.attr(\'for\'));

						if (!input.prop(\'checked\')) {
							label.closest(\'.btn-group\').find(\'label\').removeClass(\'active btn-success btn-danger btn-primary\');
							if (input.val() == \'\') {
								label.addClass(\'active btn-primary\');
							} else if (input.val() == 0) {
								label.addClass(\'active btn-danger\');
							} else {
								label.addClass(\'active btn-success\');
							}
							input.prop(\'checked\', \'checked\');
						}
					});
					$(\'.btn-group input[checked=checked]\').each(function()
					{
						if ($(this).val() == \'\') {
							$(\'label[for=\' + $(this).attr(\'id\') + \']\').addClass(\'active btn-primary\');
						} else if ($(this).val() == 0) {
							$(\'label[for=\' + $(this).attr(\'id\') + \']\').addClass(\'active btn-danger\');
						} else {
							$(\'label[for=\' + $(this).attr(\'id\') + \']\').addClass(\'active btn-success\');
						}
					});
				});
            
            </script>';
			
			return $html;
	}

	protected function configAzurastat($data,$dataObject){
		

		$dataObject->formOption = JForm::getInstance('com_azurapagebuilder.page.optionstat', 'formoptionstat');

		// set attrs
		$elementAttrsFields = array("id","extraClass","title","value","iconClass");
		foreach ($elementAttrsFields as $key => $attr) {
			$value = null;
			if(isset($dataObject->attrs->{$attr})){
				$value = $dataObject->attrs->{$attr};
			}
			$dataObject->formOption->setValue("{$attr}","elementAttrs", $value);
		}

		$html = '
			<h2><i class="fa fa-eye"></i> Stat Counter'.(!empty($dataObject->name)? ' - '.$dataObject->name : '').'</h2>
			<div class="row-fluid" style="padding-top:20px;">
				<div class="span5">
					<div class="input-prepend">
						<span class="add-on">Name</span>
						<input class="inputbox" name="elementName" placeholder="Element name" type="text" value="'.(isset($dataObject->name)? $dataObject->name : '').'">
					</div>
				</div>
				<div class="span7">
					<div class="form-horizontal">
						<div class="control-group elementPubLang">
							<div class="control-label">
								<label id="elementPubLang_published-lbl" for="elementPubLang_published">Published</label>
							</div>
							<div class="controls">
								<fieldset id="elementPubLang_published" class="radio btn-group btn-group-yesno">
									<input id="elementPubLang_published1" name="elementPubLang[published]" value="1" '.((isset($dataObject->published) && $dataObject->published == '1')? 'checked="checked"' : '').' type="radio">
									<label  for="elementPubLang_published1">Yes</label>
									<input id="elementPubLang_published0" name="elementPubLang[published]" value="0" '.((isset($dataObject->published) && $dataObject->published == '0')? 'checked="checked"' : '').' type="radio">
									<label for="elementPubLang_published0">No</label>
								</fieldset>
							</div>
						</div>
					</div>


				</div>
			</div>
			<!-- /.row-fluid -->
			<hr>
			<div class="row-fluid">
				<div class="span12">
					<div class="form-horizontal">';
					// attrs

					foreach ($dataObject->formOption->getFieldsets('elementAttrs') as $fieldsets => $fieldset) {
						foreach($dataObject->formOption->getFieldset($fieldset->name) as $field){

							if ($field->hidden) {
								$html .= $field->input;
							}else{
								//echo'<pre>';var_dump($field);
								$html .= $field->getControlGroup();
							}
						}
					}
					
					$html .='</div>
					<!-- /.form-horizontal -->
				</div>
				<!-- /.span12 -->

			</div>
			<!-- /.row-fluid -->
			
			<div class="row-fluid" style="text-align: center;">
				<hr>
				<a href="#" id="azura-setting-btn-save" class="btn btn-primary azura-setting-btn-save">Save</a>
				<a href="#" id="azura-setting-btn-cancel" class="btn btn-default azura-setting-btn-cancel">Cancel</a>
			</div>';

			$html .='
			<script>
				function jInsertIconClassValue(value, id) {
		        		var old_value = jQuery("#" + id).val();
		        		if (old_value != value) {
		        			var $elem = jQuery("#" + id);
		        			$elem.val(value);
		        			$elem.trigger("change");
		        			if (typeof($elem.get(0).onchange) === "function") {
		        				$elem.get(0).onchange();
		        			}
		        		}
		        }

	            jQuery(function($) {
	    			SqueezeBox.initialize({});
	    			SqueezeBox.assign($(\'a.modal_jform_azurafont\').get(), {
	    				parse: \'rel\'
	    			});
	    		});
            
            </script>';

			$html .='
			<script>
				jQuery(function($){

					// Turn radios into btn-group
					$(\'.radio.btn-group label\').addClass(\'btn btn-small\');
					$(\'body\').on(\'click\',\'.btn-group label:not(.active)\',function()
					{
						var label = $(this);
						var input = $(\'#\' + label.attr(\'for\'));

						if (!input.prop(\'checked\')) {
							label.closest(\'.btn-group\').find(\'label\').removeClass(\'active btn-success btn-danger btn-primary\');
							if (input.val() == \'\') {
								label.addClass(\'active btn-primary\');
							} else if (input.val() == 0) {
								label.addClass(\'active btn-danger\');
							} else {
								label.addClass(\'active btn-success\');
							}
							input.prop(\'checked\', \'checked\');
						}
					});
					$(\'.btn-group input[checked=checked]\').each(function()
					{
						if ($(this).val() == \'\') {
							$(\'label[for=\' + $(this).attr(\'id\') + \']\').addClass(\'active btn-primary\');
						} else if ($(this).val() == 0) {
							$(\'label[for=\' + $(this).attr(\'id\') + \']\').addClass(\'active btn-danger\');
						} else {
							$(\'label[for=\' + $(this).attr(\'id\') + \']\').addClass(\'active btn-success\');
						}
					});
				});
            
            </script>';

			return $html;
	}

	protected function configAzurateam($data,$dataObject){
		

		$dataObject->formOption = JForm::getInstance('com_azurapagebuilder.page.optionteam', 'formoptionteam');

		
		// set attrs
		$elementAttrsFields = array("id","class");
		foreach ($elementAttrsFields as $key => $attr) {
			$value = null;
			if(isset($dataObject->attrs->{$attr})){
				$value = $dataObject->attrs->{$attr};
			}
			$dataObject->formOption->setValue("{$attr}","elementAttrs", $value);
		}

		$html = '
			<h2><i class="fa fa-users"></i> Team'.(!empty($dataObject->name)? ' - '.$dataObject->name : '').'</h2>
			<div class="row-fluid" style="padding-top:20px;">
				<div class="span5">
					<div class="input-prepend">
						<span class="add-on">Name</span>
						<input class="inputbox" name="elementName" placeholder="Element name" type="text" value="'.(isset($dataObject->name)? $dataObject->name : '').'">
					</div>
				</div>
				<div class="span7">
					<div class="form-horizontal">
						<div class="control-group elementPubLang">
							<div class="control-label">
								<label id="elementPubLang_published-lbl" for="elementPubLang_published">Published</label>
							</div>
							<div class="controls">
								<fieldset id="elementPubLang_published" class="radio btn-group btn-group-yesno">
									<input id="elementPubLang_published1" name="elementPubLang[published]" value="1" '.((isset($dataObject->published) && $dataObject->published == '1')? 'checked="checked"' : '').' type="radio">
									<label  for="elementPubLang_published1">Yes</label>
									<input id="elementPubLang_published0" name="elementPubLang[published]" value="0" '.((isset($dataObject->published) && $dataObject->published == '0')? 'checked="checked"' : '').' type="radio">
									<label for="elementPubLang_published0">No</label>
								</fieldset>
							</div>
						</div>
					</div>


				</div>
			</div>
			<!-- /.row-fluid -->
			<hr>
			<div class="row-fluid">
				<div class="span12">
					<div class="form-horizontal">';


					// attrs

					foreach ($dataObject->formOption->getFieldsets('elementAttrs') as $fieldsets => $fieldset) {
						foreach($dataObject->formOption->getFieldset($fieldset->name) as $field){

							if ($field->hidden) {
								$html .= $field->input;
							}else{
								//echo'<pre>';var_dump($field);
								$html .= $field->getControlGroup();
							}
						}
					}
					
					$html .='</div>
					<!-- /.form-horizontal -->
				</div>
				<!-- /.span12 -->

			</div>
			<!-- /.row-fluid -->
			
			<div class="row-fluid" style="text-align: center;">
				<hr>
				<a href="#" id="azura-setting-btn-save" class="btn btn-primary azura-setting-btn-save">Save</a>
				<a href="#" id="azura-setting-btn-cancel" class="btn btn-default azura-setting-btn-cancel">Cancel</a>
			</div>';

			$html .='
			<script>
				jQuery(function($){
					$(\'.radio.btn-group label\').addClass(\'btn btn-small\');
					$(\'body\').on(\'click\',\'.btn-group label:not(.active)\',function()
					{
						var label = $(this);
						var input = $(\'#\' + label.attr(\'for\'));

						if (!input.prop(\'checked\')) {
							label.closest(\'.btn-group\').find(\'label\').removeClass(\'active btn-success btn-danger btn-primary\');
							if (input.val() == \'\') {
								label.addClass(\'active btn-primary\');
							} else if (input.val() == 0) {
								label.addClass(\'active btn-danger\');
							} else {
								label.addClass(\'active btn-success\');
							}
							input.prop(\'checked\', \'checked\');
						}
					});
					$(\'.btn-group input[checked=checked]\').each(function()
					{
						if ($(this).val() == \'\') {
							$(\'label[for=\' + $(this).attr(\'id\') + \']\').addClass(\'active btn-primary\');
						} else if ($(this).val() == 0) {
							$(\'label[for=\' + $(this).attr(\'id\') + \']\').addClass(\'active btn-danger\');
						} else {
							$(\'label[for=\' + $(this).attr(\'id\') + \']\').addClass(\'active btn-success\');
						}
					});
				});
            
            </script>';

			return $html;
	}

	protected function configAzurateammember($data,$dataObject){
		

		$dataObject->formOption = JForm::getInstance('com_azurapagebuilder.page.optionteammember', 'formoptionteammember');

		
		// set attrs
		$elementAttrsFields = array("id","class","memName","memPosition","memPhoto","memIntroduction","memSkillOne","memSkillTwo","memSkillThree","memFacebook","memLinkedIn","memTwitter","memDribbble","memGooglePlus");
		foreach ($elementAttrsFields as $key => $attr) {
			$value = null;
			if(isset($dataObject->attrs->{$attr})){
				$value = $dataObject->attrs->{$attr};
			}
			$dataObject->formOption->setValue("{$attr}","elementAttrs", $value);
		}

		$html = '
			<h2><i class="fa fa-users"></i> Member'.(!empty($dataObject->name)? ' - '.$dataObject->name : '').'</h2>
			<div class="row-fluid" style="padding-top:20px;">
				<div class="span5">
					<div class="input-prepend">
						<span class="add-on">Name</span>
						<input class="inputbox" name="elementName" placeholder="Element name" type="text" value="'.(isset($dataObject->name)? $dataObject->name : '').'">
					</div>
				</div>
				<div class="span7">
					<div class="form-horizontal">
						<div class="control-group elementPubLang">
							<div class="control-label">
								<label id="elementPubLang_published-lbl" for="elementPubLang_published">Published</label>
							</div>
							<div class="controls">
								<fieldset id="elementPubLang_published" class="radio btn-group btn-group-yesno">
									<input id="elementPubLang_published1" name="elementPubLang[published]" value="1" '.((isset($dataObject->published) && $dataObject->published == '1')? 'checked="checked"' : '').' type="radio">
									<label  for="elementPubLang_published1">Yes</label>
									<input id="elementPubLang_published0" name="elementPubLang[published]" value="0" '.((isset($dataObject->published) && $dataObject->published == '0')? 'checked="checked"' : '').' type="radio">
									<label for="elementPubLang_published0">No</label>
								</fieldset>
							</div>
						</div>
					</div>


				</div>
			</div>
			<!-- /.row-fluid -->
			<hr>
			<div class="row-fluid">
				<div class="span12">
					<div class="form-horizontal">';

					// attrs

					foreach ($dataObject->formOption->getFieldsets('elementAttrs') as $fieldsets => $fieldset) {
						$html .= '<div class="row-fluid">';
							$html .='<div class="span6">';
							$key = 1;
							$owlfields = $dataObject->formOption->getFieldset($fieldset->name);
						foreach($owlfields as $field){

							if ($field->hidden) {
								$html .= $field->input;
							}else{
								//echo'<pre>';var_dump($field);
								$html .= $field->getControlGroup();
								$key++;
							}

							if($key == 8 && count($owlfields) >8){
								$html .= '</div><div class="span6">';
							}
						}

							$html .='</div>';
						$html .='</div>';
					}
					
					$html .='</div>
					<!-- /.form-horizontal -->
				</div>
				<!-- /.span12 -->

			</div>
			<!-- /.row-fluid -->
			
			<div class="row-fluid" style="text-align: center;">
				<hr>
				<a href="#" id="azura-setting-btn-save" class="btn btn-primary azura-setting-btn-save">Save</a>
				<a href="#" id="azura-setting-btn-cancel" class="btn btn-default azura-setting-btn-cancel">Cancel</a>
			</div>';

			$html .='
			<script>
				function jInsertFieldValue(value, id) {
		        		var old_value = jQuery("#" + id).val();
		        		if (old_value != value) {
		        			var $elem = jQuery("#" + id);
		        			$elem.val(value);
		        			$elem.trigger("change");
		        			if (typeof($elem.get(0).onchange) === "function") {
		        				$elem.get(0).onchange();
		        			}
		        		}
		        }

	            jQuery(function($) {
	    			SqueezeBox.initialize({});
	    			SqueezeBox.assign($(\'a.modal_jform_azuramedia\').get(), {
	    				parse: \'rel\'
	    			});
					
					
	                
	                jQuery(\'body\').on(\'change\',\'#elementAttrs_memPhoto\', function(event){
	                    event.preventDefault();
	                    var value = event.currentTarget.value;
	                    
	                    jQuery(\'.fancybox-inner\').find(\'#elementAttrs_memPhoto\').val(value);
	                });
	    		});
            
            </script>';

            $html .='
			<script>
				jQuery(function($){
					$(\'.radio.btn-group label\').addClass(\'btn btn-small\');
					$(\'body\').on(\'click\',\'.btn-group label:not(.active)\',function()
					{
						var label = $(this);
						var input = $(\'#\' + label.attr(\'for\'));

						if (!input.prop(\'checked\')) {
							label.closest(\'.btn-group\').find(\'label\').removeClass(\'active btn-success btn-danger btn-primary\');
							if (input.val() == \'\') {
								label.addClass(\'active btn-primary\');
							} else if (input.val() == 0) {
								label.addClass(\'active btn-danger\');
							} else {
								label.addClass(\'active btn-success\');
							}
							input.prop(\'checked\', \'checked\');
						}
					});
					$(\'.btn-group input[checked=checked]\').each(function()
					{
						if ($(this).val() == \'\') {
							$(\'label[for=\' + $(this).attr(\'id\') + \']\').addClass(\'active btn-primary\');
						} else if ($(this).val() == 0) {
							$(\'label[for=\' + $(this).attr(\'id\') + \']\').addClass(\'active btn-danger\');
						} else {
							$(\'label[for=\' + $(this).attr(\'id\') + \']\').addClass(\'active btn-success\');
						}
					});
				});
            
            </script>';

			return $html;
	}

	protected function configAzuraservicesslider($data,$dataObject){
		

		$dataObject->formOption = JForm::getInstance('com_azurapagebuilder.page.optionservicesslider', 'formoptionservicesslider');

		
		// set attrs
		$elementAttrsFields = array("id","class","itemActive");
		foreach ($elementAttrsFields as $key => $attr) {
			$value = null;
			if(isset($dataObject->attrs->{$attr})){
				$value = $dataObject->attrs->{$attr};
			}
			$dataObject->formOption->setValue("{$attr}","elementAttrs", $value);
		}

		$html = '
			<h2><i class="fa fa-cogs"></i> Services'.(!empty($dataObject->name)? ' - '.$dataObject->name : '').'</h2>
			<div class="row-fluid" style="padding-top:20px;">
				<div class="span5">
					<div class="input-prepend">
						<span class="add-on">Name</span>
						<input class="inputbox" name="elementName" placeholder="Element name" type="text" value="'.(isset($dataObject->name)? $dataObject->name : '').'">
					</div>
				</div>
				<div class="span7">
					<div class="form-horizontal">
						<div class="control-group elementPubLang">
							<div class="control-label">
								<label id="elementPubLang_published-lbl" for="elementPubLang_published">Published</label>
							</div>
							<div class="controls">
								<fieldset id="elementPubLang_published" class="radio btn-group btn-group-yesno">
									<input id="elementPubLang_published1" name="elementPubLang[published]" value="1" '.((isset($dataObject->published) && $dataObject->published == '1')? 'checked="checked"' : '').' type="radio">
									<label  for="elementPubLang_published1">Yes</label>
									<input id="elementPubLang_published0" name="elementPubLang[published]" value="0" '.((isset($dataObject->published) && $dataObject->published == '0')? 'checked="checked"' : '').' type="radio">
									<label for="elementPubLang_published0">No</label>
								</fieldset>
							</div>
						</div>
					</div>


				</div>
			</div>
			<!-- /.row-fluid -->
			<hr>
			<div class="row-fluid">
				<div class="span12">
					<div class="form-horizontal">';

					// attrs

					foreach ($dataObject->formOption->getFieldsets('elementAttrs') as $fieldsets => $fieldset) {
							$serfields = $dataObject->formOption->getFieldset($fieldset->name);
						foreach($serfields as $field){

							if ($field->hidden) {
								$html .= $field->input;
							}else{
								//echo'<pre>';var_dump($field);
								$html .= $field->getControlGroup();
								
							}

							
						}

					}
					
					$html .='</div>
					<!-- /.form-horizontal -->
				</div>
				<!-- /.span12 -->

			</div>
			<!-- /.row-fluid -->
			
			<div class="row-fluid" style="text-align: center;">
				<hr>
				<a href="#" id="azura-setting-btn-save" class="btn btn-primary azura-setting-btn-save">Save</a>
				<a href="#" id="azura-setting-btn-cancel" class="btn btn-default azura-setting-btn-cancel">Cancel</a>
			</div>';

			$html .='
			<script>
				jQuery(function($){
					$(\'.radio.btn-group label\').addClass(\'btn btn-small\');
					$(\'body\').on(\'click\',\'.btn-group label:not(.active)\',function()
					{
						var label = $(this);
						var input = $(\'#\' + label.attr(\'for\'));

						if (!input.prop(\'checked\')) {
							label.closest(\'.btn-group\').find(\'label\').removeClass(\'active btn-success btn-danger btn-primary\');
							if (input.val() == \'\') {
								label.addClass(\'active btn-primary\');
							} else if (input.val() == 0) {
								label.addClass(\'active btn-danger\');
							} else {
								label.addClass(\'active btn-success\');
							}
							input.prop(\'checked\', \'checked\');
						}
					});
					$(\'.btn-group input[checked=checked]\').each(function()
					{
						if ($(this).val() == \'\') {
							$(\'label[for=\' + $(this).attr(\'id\') + \']\').addClass(\'active btn-primary\');
						} else if ($(this).val() == 0) {
							$(\'label[for=\' + $(this).attr(\'id\') + \']\').addClass(\'active btn-danger\');
						} else {
							$(\'label[for=\' + $(this).attr(\'id\') + \']\').addClass(\'active btn-success\');
						}
					});
				});
            
            </script>';

			return $html;
	}

	protected function configAzuraservicesslideritem($data,$dataObject){
		

		$dataObject->formOption = JForm::getInstance('com_azurapagebuilder.page.optionservicesslideritem', 'formoptionservicesslideritem');

		
		// set attrs
		$elementAttrsFields = array("id","class","title","iconClass");
		foreach ($elementAttrsFields as $key => $attr) {
			$value = null;
			if(isset($dataObject->attrs->{$attr})){
				$value = $dataObject->attrs->{$attr};
			}
			$dataObject->formOption->setValue("{$attr}","elementAttrs", $value);
		}

		$html = '
			<h2><i class="fa fa-cogs"></i> Service'.(!empty($dataObject->name)? ' - '.$dataObject->name : '').'</h2>
			<div class="row-fluid" style="padding-top:20px;">
				<div class="span5">
					<div class="input-prepend">
						<span class="add-on">Name</span>
						<input class="inputbox" name="elementName" placeholder="Element name" type="text" value="'.(isset($dataObject->name)? $dataObject->name : '').'">
					</div>
				</div>
				<div class="span7">
					<div class="form-horizontal">
						<div class="control-group elementPubLang">
							<div class="control-label">
								<label id="elementPubLang_published-lbl" for="elementPubLang_published">Published</label>
							</div>
							<div class="controls">
								<fieldset id="elementPubLang_published" class="radio btn-group btn-group-yesno">
									<input id="elementPubLang_published1" name="elementPubLang[published]" value="1" '.((isset($dataObject->published) && $dataObject->published == '1')? 'checked="checked"' : '').' type="radio">
									<label  for="elementPubLang_published1">Yes</label>
									<input id="elementPubLang_published0" name="elementPubLang[published]" value="0" '.((isset($dataObject->published) && $dataObject->published == '0')? 'checked="checked"' : '').' type="radio">
									<label for="elementPubLang_published0">No</label>
								</fieldset>
							</div>
						</div>
					</div>


				</div>
			</div>
			<!-- /.row-fluid -->
			<hr>
			<div class="row-fluid">
				<div class="span12">
					<div class="form-horizontal">';

					// attrs

					foreach ($dataObject->formOption->getFieldsets('elementAttrs') as $fieldsets => $fieldset) {
							$serfields = $dataObject->formOption->getFieldset($fieldset->name);
						foreach($serfields as $field){

							if ($field->hidden) {
								$html .= $field->input;
							}else{
								//echo'<pre>';var_dump($field);
								$html .= $field->getControlGroup();
								
							}

							
						}

					}
					
					$html .='</div>
					<!-- /.form-horizontal -->
				</div>
				<!-- /.span12 -->

			</div>
			<!-- /.row-fluid -->
			
			<div class="row-fluid" style="text-align: center;">
				<hr>
				<a href="#" id="azura-setting-btn-save" class="btn btn-primary azura-setting-btn-save">Save</a>
				<a href="#" id="azura-setting-btn-cancel" class="btn btn-default azura-setting-btn-cancel">Cancel</a>
			</div>';

			$html .='
			<script>
				function jInsertIconClassValue(value, id) {
		        		var old_value = jQuery("#" + id).val();
		        		if (old_value != value) {
		        			var $elem = jQuery("#" + id);
		        			$elem.val(value);
		        			$elem.trigger("change");
		        			if (typeof($elem.get(0).onchange) === "function") {
		        				$elem.get(0).onchange();
		        			}
		        		}
		        }

	            jQuery(function($) {
	    			SqueezeBox.initialize({});
	    			SqueezeBox.assign($(\'a.modal_jform_azurafont\').get(), {
	    				parse: \'rel\'
	    			});
	    		});
            
            </script>';

			$html .='
			<script>
				jQuery(function($){
					$(\'.radio.btn-group label\').addClass(\'btn btn-small\');
					$(\'body\').on(\'click\',\'.btn-group label:not(.active)\',function()
					{
						var label = $(this);
						var input = $(\'#\' + label.attr(\'for\'));

						if (!input.prop(\'checked\')) {
							label.closest(\'.btn-group\').find(\'label\').removeClass(\'active btn-success btn-danger btn-primary\');
							if (input.val() == \'\') {
								label.addClass(\'active btn-primary\');
							} else if (input.val() == 0) {
								label.addClass(\'active btn-danger\');
							} else {
								label.addClass(\'active btn-success\');
							}
							input.prop(\'checked\', \'checked\');
						}
					});
					$(\'.btn-group input[checked=checked]\').each(function()
					{
						if ($(this).val() == \'\') {
							$(\'label[for=\' + $(this).attr(\'id\') + \']\').addClass(\'active btn-primary\');
						} else if ($(this).val() == 0) {
							$(\'label[for=\' + $(this).attr(\'id\') + \']\').addClass(\'active btn-danger\');
						} else {
							$(\'label[for=\' + $(this).attr(\'id\') + \']\').addClass(\'active btn-success\');
						}
					});
				});
            
            </script>';

			return $html;
	}

	protected function configAzuracontactform($data,$dataObject){
		

		$dataObject->formOption = JForm::getInstance('com_azurapagebuilder.page.optioncontactform', 'formoptioncontactform');

		
		// set attrs
		$elementAttrsFields = array("title","introduction","layout","receiveEmail","emailSubject","thanksMessage","showWebsite","sendAsCopy");
		foreach ($elementAttrsFields as $key => $attr) {
			$value = null;
			if(isset($dataObject->attrs->{$attr})){
				$value = $dataObject->attrs->{$attr};
			}
			$dataObject->formOption->setValue("{$attr}","elementAttrs", $value);
		}

		$html = '
			<h2><i class="fa fa-paper-plane-o"></i> Contact Form'.(!empty($dataObject->name)? ' - '.$dataObject->name : '').'</h2>
			<div class="row-fluid" style="padding-top:20px;">
				<div class="span5">
					<div class="input-prepend">
						<span class="add-on">Name</span>
						<input class="inputbox" name="elementName" placeholder="Element name" type="text" value="'.(isset($dataObject->name)? $dataObject->name : '').'">
					</div>
				</div>
				<div class="span7">
					<div class="form-horizontal">
						<div class="control-group elementPubLang">
							<div class="control-label">
								<label id="elementPubLang_published-lbl" for="elementPubLang_published">Published</label>
							</div>
							<div class="controls">
								<fieldset id="elementPubLang_published" class="radio btn-group btn-group-yesno">
									<input id="elementPubLang_published1" name="elementPubLang[published]" value="1" '.((isset($dataObject->published) && $dataObject->published == '1')? 'checked="checked"' : '').' type="radio">
									<label  for="elementPubLang_published1">Yes</label>
									<input id="elementPubLang_published0" name="elementPubLang[published]" value="0" '.((isset($dataObject->published) && $dataObject->published == '0')? 'checked="checked"' : '').' type="radio">
									<label for="elementPubLang_published0">No</label>
								</fieldset>
							</div>
						</div>
					</div>


				</div>
			</div>
			<!-- /.row-fluid -->
			<hr>
			<div class="row-fluid">
				<div class="span12">
					<div class="form-horizontal">';

					// attrs

					foreach ($dataObject->formOption->getFieldsets('elementAttrs') as $fieldsets => $fieldset) {
						$html .= '<div class="row-fluid">';
							$html .='<div class="span6">';
							$key = 1;
							$fields = $dataObject->formOption->getFieldset($fieldset->name);
						foreach($fields as $field){

							if ($field->hidden) {
								$html .= $field->input;
							}else{
								//echo'<pre>';var_dump($field);
								$html .= $field->getControlGroup();
								$key++;
							}

							if($key == 4 && count($fields) >4){
								$html .= '</div><div class="span6">';
							}
						}

							$html .='</div>';
						$html .='</div>';
					}

					
					$html .='</div>
					<!-- /.form-horizontal -->
				</div>
				<!-- /.span12 -->

			</div>
			<!-- /.row-fluid -->
			
			<div class="row-fluid" style="text-align: center;">
				<hr>
				<a href="#" id="azura-setting-btn-save" class="btn btn-primary azura-setting-btn-save">Save</a>
				<a href="#" id="azura-setting-btn-cancel" class="btn btn-default azura-setting-btn-cancel">Cancel</a>
			</div>';

			$html .='
			<script>
				jQuery(function($){
					$(\'.radio.btn-group label\').addClass(\'btn btn-small\');
					$(\'body\').on(\'click\',\'.btn-group label:not(.active)\',function()
					{
						var label = $(this);
						var input = $(\'#\' + label.attr(\'for\'));

						if (!input.prop(\'checked\')) {
							label.closest(\'.btn-group\').find(\'label\').removeClass(\'active btn-success btn-danger btn-primary\');
							if (input.val() == \'\') {
								label.addClass(\'active btn-primary\');
							} else if (input.val() == 0) {
								label.addClass(\'active btn-danger\');
							} else {
								label.addClass(\'active btn-success\');
							}
							input.prop(\'checked\', \'checked\');
						}
					});
					$(\'.btn-group input[checked=checked]\').each(function()
					{
						if ($(this).val() == \'\') {
							$(\'label[for=\' + $(this).attr(\'id\') + \']\').addClass(\'active btn-primary\');
						} else if ($(this).val() == 0) {
							$(\'label[for=\' + $(this).attr(\'id\') + \']\').addClass(\'active btn-danger\');
						} else {
							$(\'label[for=\' + $(this).attr(\'id\') + \']\').addClass(\'active btn-success\');
						}
					});
				});
            
            </script>';

			return $html;
	}

	protected function configAzurasuperslides($data,$dataObject){
		

		$dataObject->formOption = JForm::getInstance('com_azurapagebuilder.page.optionsuperslides', 'formoptionsuperslides');

		
		// set attrs
		$elementAttrsFields = array("id","class","play","animation","animationSpeed","animationEasing","pagination","slideContainerWrapper","slideContainer","slideItemWrapper","paginationContainer","navigationContainer","preserve");
		foreach ($elementAttrsFields as $key => $attr) {
			$value = null;
			if(isset($dataObject->attrs->{$attr})){
				$value = $dataObject->attrs->{$attr};
			}
			$dataObject->formOption->setValue("{$attr}","elementAttrs", $value);
		}

		$html = '
			<h2><i class="fa fa-sliders"></i> SuperSlides'.(!empty($dataObject->name)? ' - '.$dataObject->name : '').'</h2>
			<div class="row-fluid" style="padding-top:20px;">
				<div class="span5">
					<div class="input-prepend">
						<span class="add-on">Name</span>
						<input class="inputbox" name="elementName" placeholder="Element name" type="text" value="'.(isset($dataObject->name)? $dataObject->name : '').'">
					</div>
				</div>
				<div class="span7">
					<div class="form-horizontal">
						<div class="control-group elementPubLang">
							<div class="control-label">
								<label id="elementPubLang_published-lbl" for="elementPubLang_published">Published</label>
							</div>
							<div class="controls">
								<fieldset id="elementPubLang_published" class="radio btn-group btn-group-yesno">
									<input id="elementPubLang_published1" name="elementPubLang[published]" value="1" '.((isset($dataObject->published) && $dataObject->published == '1')? 'checked="checked"' : '').' type="radio">
									<label  for="elementPubLang_published1">Yes</label>
									<input id="elementPubLang_published0" name="elementPubLang[published]" value="0" '.((isset($dataObject->published) && $dataObject->published == '0')? 'checked="checked"' : '').' type="radio">
									<label for="elementPubLang_published0">No</label>
								</fieldset>
							</div>
						</div>
					</div>


				</div>
			</div>
			<!-- /.row-fluid -->
			<hr>
			<div class="row-fluid">
				<div class="span12">
					<div class="form-horizontal">';

					// attrs

					foreach ($dataObject->formOption->getFieldsets('elementAttrs') as $fieldsets => $fieldset) {
						$html .= '<div class="row-fluid">';
							$html .='<div class="span6">';
							$key = 1;
							$owlfields = $dataObject->formOption->getFieldset($fieldset->name);
						foreach($owlfields as $field){

							if ($field->hidden) {
								$html .= $field->input;
							}else{
								//echo'<pre>';var_dump($field);
								$html .= $field->getControlGroup();
								$key++;
							}

							if($key == 8 && count($owlfields) >8){
								$html .= '</div><div class="span6">';
							}
						}

							$html .='</div>';
						$html .='</div>';
					}
					
					$html .='</div>
					<!-- /.form-horizontal -->
				</div>
				<!-- /.span12 -->

			</div>
			<!-- /.row-fluid -->
			
			<div class="row-fluid" style="text-align: center;">
				<hr>
				<a href="#" id="azura-setting-btn-save" class="btn btn-primary azura-setting-btn-save">Save</a>
				<a href="#" id="azura-setting-btn-cancel" class="btn btn-default azura-setting-btn-cancel">Cancel</a>
			</div>';

			$html .='
			<script>
				jQuery(function($){
					$(\'.radio.btn-group label\').addClass(\'btn btn-small\');
					$(\'body\').on(\'click\',\'.btn-group label:not(.active)\',function()
					{
						var label = $(this);
						var input = $(\'#\' + label.attr(\'for\'));

						if (!input.prop(\'checked\')) {
							label.closest(\'.btn-group\').find(\'label\').removeClass(\'active btn-success btn-danger btn-primary\');
							if (input.val() == \'\') {
								label.addClass(\'active btn-primary\');
							} else if (input.val() == 0) {
								label.addClass(\'active btn-danger\');
							} else {
								label.addClass(\'active btn-success\');
							}
							input.prop(\'checked\', \'checked\');
						}
					});
					$(\'.btn-group input[checked=checked]\').each(function()
					{
						if ($(this).val() == \'\') {
							$(\'label[for=\' + $(this).attr(\'id\') + \']\').addClass(\'active btn-primary\');
						} else if ($(this).val() == 0) {
							$(\'label[for=\' + $(this).attr(\'id\') + \']\').addClass(\'active btn-danger\');
						} else {
							$(\'label[for=\' + $(this).attr(\'id\') + \']\').addClass(\'active btn-success\');
						}
					});
				});
            
            </script>';
			
			return $html;
	}

	protected function configAzurasuperslidesitem($data,$dataObject){

		$dataObject->formOption = JForm::getInstance('com_azurapagebuilder.page.optionsuperslidesitem', 'formoptionsuperslidesitem');
		$elementAttrsFields = array("id","class","bgImage","bgClass");
		foreach ($elementAttrsFields as $key => $attr) {
			$value = null;
			if(isset($dataObject->attrs->{$attr})){
				$value = $dataObject->attrs->{$attr};
			}
			$dataObject->formOption->setValue("{$attr}","elementAttrs", $value);
		}

		$html = '
			<h2><i class="fa fa-sliders"></i> Slide'.(!empty($dataObject->name)? ' - '.$dataObject->name : '').'</h2>
			<div class="row-fluid" style="padding-top:20px;">
				<div class="span5">
					<div class="input-prepend">
						<span class="add-on">Name</span>
						<input class="inputbox" name="elementName" placeholder="Element name" type="text" value="'.(isset($dataObject->name)? $dataObject->name : '').'">
					</div>
				</div>
				<div class="span7">
					<div class="form-horizontal">
						<div class="control-group elementPubLang">
							<div class="control-label">
								<label id="elementPubLang_published-lbl" for="elementPubLang_published">Published</label>
							</div>
							<div class="controls">
								<fieldset id="elementPubLang_published" class="radio btn-group btn-group-yesno">
									<input id="elementPubLang_published1" name="elementPubLang[published]" value="1" '.((isset($dataObject->published) && $dataObject->published == '1')? 'checked="checked"' : '').' type="radio">
									<label  for="elementPubLang_published1">Yes</label>
									<input id="elementPubLang_published0" name="elementPubLang[published]" value="0" '.((isset($dataObject->published) && $dataObject->published == '0')? 'checked="checked"' : '').' type="radio">
									<label for="elementPubLang_published0">No</label>
								</fieldset>
							</div>
						</div>
					</div>

				</div>
			</div>
			<!-- /.row-fluid -->
			<hr>
			<div class="row-fluid">
				<div class="span12">
					<div class="form-horizontal">';


					foreach ($dataObject->formOption->getFieldsets('elementAttrs') as $fieldsets => $fieldset) {
						foreach($dataObject->formOption->getFieldset($fieldset->name) as $field){

							if ($field->hidden) {
								$html .= $field->input;
							}else{
								//echo'<pre>';var_dump($field);
								$html .= $field->getControlGroup();
							}
						}
					}
					
					$html .='</div>
					<!-- /.form-horizontal -->
				</div>
				<!-- /.span12 -->

			</div>
			<!-- /.row-fluid -->
			
			<div class="row-fluid" style="text-align: center;">
				<hr>
				<a href="#" id="azura-setting-btn-save" class="btn btn-primary azura-setting-btn-save">Save</a>
				<a href="#" id="azura-setting-btn-cancel" class="btn btn-default azura-setting-btn-cancel">Cancel</a>
			</div>';

			$html .='
			<script>
				function jInsertFieldValue(value, id) {
		        		var old_value = jQuery("#" + id).val();
		        		if (old_value != value) {
		        			var $elem = jQuery("#" + id);
		        			$elem.val(value);
		        			$elem.trigger("change");
		        			if (typeof($elem.get(0).onchange) === "function") {
		        				$elem.get(0).onchange();
		        			}
		        		}
		        }

	            jQuery(function($) {
	    			SqueezeBox.initialize({});
	    			SqueezeBox.assign($(\'a.modal_jform_azuramedia\').get(), {
	    				parse: \'rel\'
	    			});
					
					
	                
	                jQuery(\'body\').on(\'change\',\'#elementAttrs_bgImage\', function(event){
	                    event.preventDefault();
	                    var value = event.currentTarget.value;
	                    
	                    jQuery(\'.fancybox-inner\').find(\'#elementAttrs_bgImage\').val(value);
	                });
	    		});
            
            </script>';

            $html .='
			<script>
				jQuery(function($){
					$(\'.radio.btn-group label\').addClass(\'btn btn-small\');
					$(\'body\').on(\'click\',\'.btn-group label:not(.active)\',function()
					{
						var label = $(this);
						var input = $(\'#\' + label.attr(\'for\'));

						if (!input.prop(\'checked\')) {
							label.closest(\'.btn-group\').find(\'label\').removeClass(\'active btn-success btn-danger btn-primary\');
							if (input.val() == \'\') {
								label.addClass(\'active btn-primary\');
							} else if (input.val() == 0) {
								label.addClass(\'active btn-danger\');
							} else {
								label.addClass(\'active btn-success\');
							}
							input.prop(\'checked\', \'checked\');
						}
					});
					$(\'.btn-group input[checked=checked]\').each(function()
					{
						if ($(this).val() == \'\') {
							$(\'label[for=\' + $(this).attr(\'id\') + \']\').addClass(\'active btn-primary\');
						} else if ($(this).val() == 0) {
							$(\'label[for=\' + $(this).attr(\'id\') + \']\').addClass(\'active btn-danger\');
						} else {
							$(\'label[for=\' + $(this).attr(\'id\') + \']\').addClass(\'active btn-success\');
						}
					});
				});
            
            </script>';

			return $html;
	}

	protected function configAzuramodule($data,$dataObject){

		$dataObject->formOption = JForm::getInstance('com_azurapagebuilder.page.optionmodule', 'formoptionmodule');
		$elementAttrsFields = array("id","class","moduleID","showTitle","layout");
		foreach ($elementAttrsFields as $key => $attr) {
			$value = null;
			if(isset($dataObject->attrs->{$attr})){
				$value = $dataObject->attrs->{$attr};
			}
			$dataObject->formOption->setValue("{$attr}","elementAttrs", $value);
		}

		$html = '
			<h2><i class="fa fa-qrcode"></i> Module'.(!empty($dataObject->name)? ' - '.$dataObject->name : '').'</h2>
			<div class="row-fluid" style="padding-top:20px;">
				<div class="span5">
					<div class="input-prepend">
						<span class="add-on">Name</span>
						<input class="inputbox" name="elementName" placeholder="Element name" type="text" value="'.(isset($dataObject->name)? $dataObject->name : '').'">
					</div>
				</div>
				<div class="span7">
					<div class="form-horizontal">
						<div class="control-group elementPubLang">
							<div class="control-label">
								<label id="elementPubLang_published-lbl" for="elementPubLang_published">Published</label>
							</div>
							<div class="controls">
								<fieldset id="elementPubLang_published" class="radio btn-group btn-group-yesno">
									<input id="elementPubLang_published1" name="elementPubLang[published]" value="1" '.((isset($dataObject->published) && $dataObject->published == '1')? 'checked="checked"' : '').' type="radio">
									<label  for="elementPubLang_published1">Yes</label>
									<input id="elementPubLang_published0" name="elementPubLang[published]" value="0" '.((isset($dataObject->published) && $dataObject->published == '0')? 'checked="checked"' : '').' type="radio">
									<label for="elementPubLang_published0">No</label>
								</fieldset>
							</div>
						</div>
					</div>

				</div>
			</div>
			<!-- /.row-fluid -->
			<hr>
			<div class="row-fluid">
				<div class="span12">
					<div class="form-horizontal">';


					foreach ($dataObject->formOption->getFieldsets('elementAttrs') as $fieldsets => $fieldset) {
						foreach($dataObject->formOption->getFieldset($fieldset->name) as $field){

							if ($field->hidden) {
								$html .= $field->input;
							}else{
								//echo'<pre>';var_dump($field);
								$html .= $field->getControlGroup();
							}
						}
					}
					
					$html .='</div>
					<!-- /.form-horizontal -->
				</div>
				<!-- /.span12 -->

			</div>
			<!-- /.row-fluid -->
			
			<div class="row-fluid" style="text-align: center;">
				<hr>
				<a href="#" id="azura-setting-btn-save" class="btn btn-primary azura-setting-btn-save">Save</a>
				<a href="#" id="azura-setting-btn-cancel" class="btn btn-default azura-setting-btn-cancel">Cancel</a>
			</div>';

            $html .='
			<script>
				jQuery(function($){
					$(\'.radio.btn-group label\').addClass(\'btn btn-small\');
					$(\'body\').on(\'click\',\'.btn-group label:not(.active)\',function()
					{
						var label = $(this);
						var input = $(\'#\' + label.attr(\'for\'));

						if (!input.prop(\'checked\')) {
							label.closest(\'.btn-group\').find(\'label\').removeClass(\'active btn-success btn-danger btn-primary\');
							if (input.val() == \'\') {
								label.addClass(\'active btn-primary\');
							} else if (input.val() == 0) {
								label.addClass(\'active btn-danger\');
							} else {
								label.addClass(\'active btn-success\');
							}
							input.prop(\'checked\', \'checked\');
						}
					});
					$(\'.btn-group input[checked=checked]\').each(function()
					{
						if ($(this).val() == \'\') {
							$(\'label[for=\' + $(this).attr(\'id\') + \']\').addClass(\'active btn-primary\');
						} else if ($(this).val() == 0) {
							$(\'label[for=\' + $(this).attr(\'id\') + \']\').addClass(\'active btn-danger\');
						} else {
							$(\'label[for=\' + $(this).attr(\'id\') + \']\').addClass(\'active btn-success\');
						}
					});
				});
            
            </script>';

			return $html;
	}

	protected function configAzuraportfolio($data,$dataObject){

		$dataObject->formOption = JForm::getInstance('com_azurapagebuilder.page.optionportfolio', 'formoptionportfolio');
		$elementAttrsFields = array("id","class","category","limit","order","orderDir","showFilter","layout");
		foreach ($elementAttrsFields as $key => $attr) {
			$value = null;
			if(isset($dataObject->attrs->{$attr})){
				$value = $dataObject->attrs->{$attr};
			}
			$dataObject->formOption->setValue("{$attr}","elementAttrs", $value);
		}

		$html = '
			<h2><i class="fa fa-briefcase"></i> Portfolio'.(!empty($dataObject->name)? ' - '.$dataObject->name : '').'</h2>
			<div class="row-fluid" style="padding-top:20px;">
				<div class="span5">
					<div class="input-prepend">
						<span class="add-on">Name</span>
						<input class="inputbox" name="elementName" placeholder="Element name" type="text" value="'.(isset($dataObject->name)? $dataObject->name : '').'">
					</div>
				</div>
				<div class="span7">
					<div class="form-horizontal">
						<div class="control-group elementPubLang">
							<div class="control-label">
								<label id="elementPubLang_published-lbl" for="elementPubLang_published">Published</label>
							</div>
							<div class="controls">
								<fieldset id="elementPubLang_published" class="radio btn-group btn-group-yesno">
									<input id="elementPubLang_published1" name="elementPubLang[published]" value="1" '.((isset($dataObject->published) && $dataObject->published == '1')? 'checked="checked"' : '').' type="radio">
									<label  for="elementPubLang_published1">Yes</label>
									<input id="elementPubLang_published0" name="elementPubLang[published]" value="0" '.((isset($dataObject->published) && $dataObject->published == '0')? 'checked="checked"' : '').' type="radio">
									<label for="elementPubLang_published0">No</label>
								</fieldset>
							</div>
						</div>
					</div>

				</div>
			</div>
			<!-- /.row-fluid -->
			<hr>
			<div class="row-fluid">
				<div class="span12">
					<div class="form-horizontal">';


					foreach ($dataObject->formOption->getFieldsets('elementAttrs') as $fieldsets => $fieldset) {
						foreach($dataObject->formOption->getFieldset($fieldset->name) as $field){

							if ($field->hidden) {
								$html .= $field->input;
							}else{
								//echo'<pre>';var_dump($field);
								$html .= $field->getControlGroup();
							}
						}
					}
					
					$html .='</div>
					<!-- /.form-horizontal -->
				</div>
				<!-- /.span12 -->

			</div>
			<!-- /.row-fluid -->
			
			<div class="row-fluid" style="text-align: center;">
				<hr>
				<a href="#" id="azura-setting-btn-save" class="btn btn-primary azura-setting-btn-save">Save</a>
				<a href="#" id="azura-setting-btn-cancel" class="btn btn-default azura-setting-btn-cancel">Cancel</a>
			</div>';

            $html .='
			<script>
				jQuery(function($){
					$(\'.radio.btn-group label\').addClass(\'btn btn-small\');
					$(\'body\').on(\'click\',\'.btn-group label:not(.active)\',function()
					{
						var label = $(this);
						var input = $(\'#\' + label.attr(\'for\'));

						if (!input.prop(\'checked\')) {
							label.closest(\'.btn-group\').find(\'label\').removeClass(\'active btn-success btn-danger btn-primary\');
							if (input.val() == \'\') {
								label.addClass(\'active btn-primary\');
							} else if (input.val() == 0) {
								label.addClass(\'active btn-danger\');
							} else {
								label.addClass(\'active btn-success\');
							}
							input.prop(\'checked\', \'checked\');
						}
					});
					$(\'.btn-group input[checked=checked]\').each(function()
					{
						if ($(this).val() == \'\') {
							$(\'label[for=\' + $(this).attr(\'id\') + \']\').addClass(\'active btn-primary\');
						} else if ($(this).val() == 0) {
							$(\'label[for=\' + $(this).attr(\'id\') + \']\').addClass(\'active btn-danger\');
						} else {
							$(\'label[for=\' + $(this).attr(\'id\') + \']\').addClass(\'active btn-success\');
						}
					});
				});
            
            </script>';

			return $html;
	}

	protected function configAzuraportfolioarticle($data,$dataObject){

		$dataObject->formOption = JForm::getInstance('com_azurapagebuilder.page.optionportfolioarticle', 'formoptionportfolioarticle');
		$elementAttrsFields = array("id","class","category","showFilter","layout");
		foreach ($elementAttrsFields as $key => $attr) {
			$value = null;
			if(isset($dataObject->attrs->{$attr})){
				$value = $dataObject->attrs->{$attr};
			}
			$dataObject->formOption->setValue("{$attr}","elementAttrs", $value);
		}

		$html = '
			<h2><i class="fa fa-briefcase"></i> Portfolio Article'.(!empty($dataObject->name)? ' - '.$dataObject->name : '').'</h2>
			<div class="row-fluid" style="padding-top:20px;">
				<div class="span5">
					<div class="input-prepend">
						<span class="add-on">Name</span>
						<input class="inputbox" name="elementName" placeholder="Element name" type="text" value="'.(isset($dataObject->name)? $dataObject->name : '').'">
					</div>
				</div>
				<div class="span7">
					<div class="form-horizontal">
						<div class="control-group elementPubLang">
							<div class="control-label">
								<label id="elementPubLang_published-lbl" for="elementPubLang_published">Published</label>
							</div>
							<div class="controls">
								<fieldset id="elementPubLang_published" class="radio btn-group btn-group-yesno">
									<input id="elementPubLang_published1" name="elementPubLang[published]" value="1" '.((isset($dataObject->published) && $dataObject->published == '1')? 'checked="checked"' : '').' type="radio">
									<label  for="elementPubLang_published1">Yes</label>
									<input id="elementPubLang_published0" name="elementPubLang[published]" value="0" '.((isset($dataObject->published) && $dataObject->published == '0')? 'checked="checked"' : '').' type="radio">
									<label for="elementPubLang_published0">No</label>
								</fieldset>
							</div>
						</div>
					</div>

				</div>
			</div>
			<!-- /.row-fluid -->
			<hr>
			<div class="row-fluid">
				<div class="span12">
					<div class="form-horizontal">';


					foreach ($dataObject->formOption->getFieldsets('elementAttrs') as $fieldsets => $fieldset) {
						foreach($dataObject->formOption->getFieldset($fieldset->name) as $field){

							if ($field->hidden) {
								$html .= $field->input;
							}else{
								//echo'<pre>';var_dump($field);
								$html .= $field->getControlGroup();
							}
						}
					}
					
					$html .='</div>
					<!-- /.form-horizontal -->
				</div>
				<!-- /.span12 -->

			</div>
			<!-- /.row-fluid -->
			
			<div class="row-fluid" style="text-align: center;">
				<hr>
				<a href="#" id="azura-setting-btn-save" class="btn btn-primary azura-setting-btn-save">Save</a>
				<a href="#" id="azura-setting-btn-cancel" class="btn btn-default azura-setting-btn-cancel">Cancel</a>
			</div>';

            $html .='
			<script>
				jQuery(function($){
					$(\'.radio.btn-group label\').addClass(\'btn btn-small\');
					$(\'body\').on(\'click\',\'.btn-group label:not(.active)\',function()
					{
						var label = $(this);
						var input = $(\'#\' + label.attr(\'for\'));

						if (!input.prop(\'checked\')) {
							label.closest(\'.btn-group\').find(\'label\').removeClass(\'active btn-success btn-danger btn-primary\');
							if (input.val() == \'\') {
								label.addClass(\'active btn-primary\');
							} else if (input.val() == 0) {
								label.addClass(\'active btn-danger\');
							} else {
								label.addClass(\'active btn-success\');
							}
							input.prop(\'checked\', \'checked\');
						}
					});
					$(\'.btn-group input[checked=checked]\').each(function()
					{
						if ($(this).val() == \'\') {
							$(\'label[for=\' + $(this).attr(\'id\') + \']\').addClass(\'active btn-primary\');
						} else if ($(this).val() == 0) {
							$(\'label[for=\' + $(this).attr(\'id\') + \']\').addClass(\'active btn-danger\');
						} else {
							$(\'label[for=\' + $(this).attr(\'id\') + \']\').addClass(\'active btn-success\');
						}
					});
				});
            
            </script>';

			return $html;
	}

	protected function configAzuraaccordion($data,$dataObject){
		

		$dataObject->formOption = JForm::getInstance('com_azurapagebuilder.page.optionaccordion', 'formoptionaccordion');

		// set attrs
		$elementAttrsFields = array("id","class","defaultActive","acctype","layout");
		foreach ($elementAttrsFields as $key => $attr) {
			$value = null;
			if(isset($dataObject->attrs->{$attr})){
				$value = $dataObject->attrs->{$attr};
			}
			$dataObject->formOption->setValue("{$attr}","elementAttrs", $value);
		}

		$html = '
			<h2><i class="fa fa-cogs"></i> Accordion'.(!empty($dataObject->name)? ' - '.$dataObject->name : '').'</h2>
			<div class="row-fluid" style="padding-top:20px;">
				<div class="span5">
					<div class="input-prepend">
						<span class="add-on">Name</span>
						<input class="inputbox" name="elementName" placeholder="Element name" type="text" value="'.(isset($dataObject->name)? $dataObject->name : '').'">
					</div>
				</div>
				<div class="span7">
					<div class="form-horizontal">
						<div class="control-group elementPubLang">
							<div class="control-label">
								<label id="elementPubLang_published-lbl" for="elementPubLang_published">Published</label>
							</div>
							<div class="controls">
								<fieldset id="elementPubLang_published" class="radio btn-group btn-group-yesno">
									<input id="elementPubLang_published1" name="elementPubLang[published]" value="1" '.((isset($dataObject->published) && $dataObject->published == '1')? 'checked="checked"' : '').' type="radio">
									<label  for="elementPubLang_published1">Yes</label>
									<input id="elementPubLang_published0" name="elementPubLang[published]" value="0" '.((isset($dataObject->published) && $dataObject->published == '0')? 'checked="checked"' : '').' type="radio">
									<label for="elementPubLang_published0">No</label>
								</fieldset>
							</div>
						</div>
					</div>


				</div>
			</div>
			<!-- /.row-fluid -->
			<hr>
			<div class="row-fluid">
				<div class="span12">
					<div class="form-horizontal">';
					// attrs

					foreach ($dataObject->formOption->getFieldsets('elementAttrs') as $fieldsets => $fieldset) {
						foreach($dataObject->formOption->getFieldset($fieldset->name) as $field){

							if ($field->hidden) {
								$html .= $field->input;
							}else{
								//echo'<pre>';var_dump($field);
								$html .= $field->getControlGroup();
							}
						}
					}
					
					$html .='</div>
					<!-- /.form-horizontal -->
				</div>
				<!-- /.span12 -->

			</div>
			<!-- /.row-fluid -->
			
			<div class="row-fluid" style="text-align: center;">
				<hr>
				<a href="#" id="azura-setting-btn-save" class="btn btn-primary azura-setting-btn-save">Save</a>
				<a href="#" id="azura-setting-btn-cancel" class="btn btn-default azura-setting-btn-cancel">Cancel</a>
			</div>';

			$html .='
			<script>
				jQuery(function($){
					$(\'.radio.btn-group label\').addClass(\'btn btn-small\');
					$(\'body\').on(\'click\',\'.btn-group label:not(.active)\',function()
					{
						var label = $(this);
						var input = $(\'#\' + label.attr(\'for\'));

						if (!input.prop(\'checked\')) {
							label.closest(\'.btn-group\').find(\'label\').removeClass(\'active btn-success btn-danger btn-primary\');
							if (input.val() == \'\') {
								label.addClass(\'active btn-primary\');
							} else if (input.val() == 0) {
								label.addClass(\'active btn-danger\');
							} else {
								label.addClass(\'active btn-success\');
							}
							input.prop(\'checked\', \'checked\');
						}
					});
					$(\'.btn-group input[checked=checked]\').each(function()
					{
						if ($(this).val() == \'\') {
							$(\'label[for=\' + $(this).attr(\'id\') + \']\').addClass(\'active btn-primary\');
						} else if ($(this).val() == 0) {
							$(\'label[for=\' + $(this).attr(\'id\') + \']\').addClass(\'active btn-danger\');
						} else {
							$(\'label[for=\' + $(this).attr(\'id\') + \']\').addClass(\'active btn-success\');
						}
					});
				});
            
            </script>';

			return $html;
	}

	protected function configAzuraaccordionitem($data,$dataObject){
		

		$dataObject->formOption = JForm::getInstance('com_azurapagebuilder.page.optionaccordionitem', 'formoptionaccordionitem');

		// set content
		$elementContentFields = array("accContent");
		if(count($elementContentFields) == 1) {
			$content = $elementContentFields[0];
			$value = '';
			if(isset($dataObject->content)){
				$value = $dataObject->content;
			}
			$dataObject->formOption->setValue("{$content}","elementContent", $value);
		}elseif(count($elementContentFields) > 1){
			foreach ($elementContentFields as $key => $content) {
				$value = null;
				if(isset($dataObject->content->{$attr})){
					$value = $dataObject->content->{$attr};
				}
				$dataObject->formOption->setValue("{$content}","elementContent", $value);
			}

		}
		// set attrs
		$elementAttrsFields = array("id","class","title","iconClass","layout");
		foreach ($elementAttrsFields as $key => $attr) {
			$value = null;
			if(isset($dataObject->attrs->{$attr})){
				$value = $dataObject->attrs->{$attr};
			}
			$dataObject->formOption->setValue("{$attr}","elementAttrs", $value);
		}

		$html = '
			<h2><i class="fa fa-cogs"></i> Accordion Item'.(!empty($dataObject->name)? ' - '.$dataObject->name : '').'</h2>
			<div class="row-fluid" style="padding-top:20px;">
				<div class="span5">
					<div class="input-prepend">
						<span class="add-on">Name</span>
						<input class="inputbox" name="elementName" placeholder="Element name" type="text" value="'.(isset($dataObject->name)? $dataObject->name : '').'">
					</div>
				</div>
				<div class="span7">
					<div class="form-horizontal">
						<div class="control-group elementPubLang">
							<div class="control-label">
								<label id="elementPubLang_published-lbl" for="elementPubLang_published">Published</label>
							</div>
							<div class="controls">
								<fieldset id="elementPubLang_published" class="radio btn-group btn-group-yesno">
									<input id="elementPubLang_published1" name="elementPubLang[published]" value="1" '.((isset($dataObject->published) && $dataObject->published == '1')? 'checked="checked"' : '').' type="radio">
									<label  for="elementPubLang_published1">Yes</label>
									<input id="elementPubLang_published0" name="elementPubLang[published]" value="0" '.((isset($dataObject->published) && $dataObject->published == '0')? 'checked="checked"' : '').' type="radio">
									<label for="elementPubLang_published0">No</label>
								</fieldset>
							</div>
						</div>
					</div>


				</div>
			</div>
			<!-- /.row-fluid -->
			<hr>
			<div class="row-fluid">
				<div class="span12">
					<div class="form-horizontal">';

					$html .= '<div class="row-fluid">';
							$html .='<div class="span6">';

					// attrs

					foreach ($dataObject->formOption->getFieldsets('elementAttrs') as $fieldsets => $fieldset) {
						
						$fields = $dataObject->formOption->getFieldset($fieldset->name);
						foreach($fields as $field){

							if ($field->hidden) {
								$html .= $field->input;
							}else{
								//echo'<pre>';var_dump($field);
								$html .= $field->getControlGroup();
								
							}
						}
					}
					

						$html .= '</div><div class="span6">';

					// content
					foreach ($dataObject->formOption->getFieldsets('elementContent') as $fieldsets => $fieldset) {
						foreach($dataObject->formOption->getFieldset($fieldset->name) as $field){

							if ($field->hidden) {
								$html .= $field->input;
							}else{
								//echo'<pre>';var_dump($field);
								$html .= $field->getControlGroup();
							}
						}
					}

							$html .='</div>';
						$html .='</div>';
					
					$html .='</div>
					<!-- /.form-horizontal -->
				</div>
				<!-- /.span12 -->

			</div>
			<!-- /.row-fluid -->
			
			<div class="row-fluid" style="text-align: center;">
				<hr>
				<a href="#" id="azura-setting-btn-save" class="btn btn-primary azura-setting-btn-save">Save</a>
				<a href="#" id="azura-setting-btn-cancel" class="btn btn-default azura-setting-btn-cancel">Cancel</a>
			</div>';

			$html .='
			<script>
				function jInsertIconClassValue(value, id) {
		        		var old_value = jQuery("#" + id).val();
		        		if (old_value != value) {
		        			var $elem = jQuery("#" + id);
		        			$elem.val(value);
		        			$elem.trigger("change");
		        			if (typeof($elem.get(0).onchange) === "function") {
		        				$elem.get(0).onchange();
		        			}
		        		}
		        }

	            jQuery(function($) {
	    			SqueezeBox.initialize({});
	    			SqueezeBox.assign($(\'a.modal_jform_azurafont\').get(), {
	    				parse: \'rel\'
	    			});
					
	    		});
            
            </script>';

			$html .='
			<script>
				jQuery(function($){
					$(\'.radio.btn-group label\').addClass(\'btn btn-small\');
					$(\'body\').on(\'click\',\'.btn-group label:not(.active)\',function()
					{
						var label = $(this);
						var input = $(\'#\' + label.attr(\'for\'));

						if (!input.prop(\'checked\')) {
							label.closest(\'.btn-group\').find(\'label\').removeClass(\'active btn-success btn-danger btn-primary\');
							if (input.val() == \'\') {
								label.addClass(\'active btn-primary\');
							} else if (input.val() == 0) {
								label.addClass(\'active btn-danger\');
							} else {
								label.addClass(\'active btn-success\');
							}
							input.prop(\'checked\', \'checked\');
						}
					});
					$(\'.btn-group input[checked=checked]\').each(function()
					{
						if ($(this).val() == \'\') {
							$(\'label[for=\' + $(this).attr(\'id\') + \']\').addClass(\'active btn-primary\');
						} else if ($(this).val() == 0) {
							$(\'label[for=\' + $(this).attr(\'id\') + \']\').addClass(\'active btn-danger\');
						} else {
							$(\'label[for=\' + $(this).attr(\'id\') + \']\').addClass(\'active btn-success\');
						}
					});
				});
            
            </script>';
			return $html;
	}

	protected function configAzuratabtoggle($data,$dataObject){
		

		$dataObject->formOption = JForm::getInstance('com_azurapagebuilder.page.optiontabtoggle', 'formoptiontabtoggle');

		// set attrs
		$elementAttrsFields = array("id","class","defaultActive","style","fade","layout");
		foreach ($elementAttrsFields as $key => $attr) {
			$value = null;
			if(isset($dataObject->attrs->{$attr})){
				$value = $dataObject->attrs->{$attr};
			}
			$dataObject->formOption->setValue("{$attr}","elementAttrs", $value);
		}

		$html = '
			<h2><i class="fa fa-cube"></i> Tab & Toggle'.(!empty($dataObject->name)? ' - '.$dataObject->name : '').'</h2>
			<div class="row-fluid" style="padding-top:20px;">
				<div class="span5">
					<div class="input-prepend">
						<span class="add-on">Name</span>
						<input class="inputbox" name="elementName" placeholder="Element name" type="text" value="'.(isset($dataObject->name)? $dataObject->name : '').'">
					</div>
				</div>
				<div class="span7">
					<div class="form-horizontal">
						<div class="control-group elementPubLang">
							<div class="control-label">
								<label id="elementPubLang_published-lbl" for="elementPubLang_published">Published</label>
							</div>
							<div class="controls">
								<fieldset id="elementPubLang_published" class="radio btn-group btn-group-yesno">
									<input id="elementPubLang_published1" name="elementPubLang[published]" value="1" '.((isset($dataObject->published) && $dataObject->published == '1')? 'checked="checked"' : '').' type="radio">
									<label  for="elementPubLang_published1">Yes</label>
									<input id="elementPubLang_published0" name="elementPubLang[published]" value="0" '.((isset($dataObject->published) && $dataObject->published == '0')? 'checked="checked"' : '').' type="radio">
									<label for="elementPubLang_published0">No</label>
								</fieldset>
							</div>
						</div>
					</div>


				</div>
			</div>
			<!-- /.row-fluid -->
			<hr>
			<div class="row-fluid">
				<div class="span12">
					<div class="form-horizontal">';
					// attrs

					foreach ($dataObject->formOption->getFieldsets('elementAttrs') as $fieldsets => $fieldset) {
						foreach($dataObject->formOption->getFieldset($fieldset->name) as $field){

							if ($field->hidden) {
								$html .= $field->input;
							}else{
								//echo'<pre>';var_dump($field);
								$html .= $field->getControlGroup();
							}
						}
					}
					
					$html .='</div>
					<!-- /.form-horizontal -->
				</div>
				<!-- /.span12 -->

			</div>
			<!-- /.row-fluid -->
			
			<div class="row-fluid" style="text-align: center;">
				<hr>
				<a href="#" id="azura-setting-btn-save" class="btn btn-primary azura-setting-btn-save">Save</a>
				<a href="#" id="azura-setting-btn-cancel" class="btn btn-default azura-setting-btn-cancel">Cancel</a>
			</div>';

			$html .='
			<script>
				jQuery(function($){
					$(\'.radio.btn-group label\').addClass(\'btn btn-small\');
					$(\'body\').on(\'click\',\'.btn-group label:not(.active)\',function()
					{
						var label = $(this);
						var input = $(\'#\' + label.attr(\'for\'));

						if (!input.prop(\'checked\')) {
							label.closest(\'.btn-group\').find(\'label\').removeClass(\'active btn-success btn-danger btn-primary\');
							if (input.val() == \'\') {
								label.addClass(\'active btn-primary\');
							} else if (input.val() == 0) {
								label.addClass(\'active btn-danger\');
							} else {
								label.addClass(\'active btn-success\');
							}
							input.prop(\'checked\', \'checked\');
						}
					});
					$(\'.btn-group input[checked=checked]\').each(function()
					{
						if ($(this).val() == \'\') {
							$(\'label[for=\' + $(this).attr(\'id\') + \']\').addClass(\'active btn-primary\');
						} else if ($(this).val() == 0) {
							$(\'label[for=\' + $(this).attr(\'id\') + \']\').addClass(\'active btn-danger\');
						} else {
							$(\'label[for=\' + $(this).attr(\'id\') + \']\').addClass(\'active btn-success\');
						}
					});
				});
            
            </script>';

			return $html;
	}

	protected function configAzuratabtoggleitem($data,$dataObject){
		

		$dataObject->formOption = JForm::getInstance('com_azurapagebuilder.page.optiontabtoggleitem', 'formoptiontabtoggleitem');

		// set content
		$elementContentFields = array("ttContent");
		if(count($elementContentFields) == 1) {
			$content = $elementContentFields[0];
			$value = '';
			if(isset($dataObject->content)){
				$value = $dataObject->content;
			}
			$dataObject->formOption->setValue("{$content}","elementContent", $value);
		}elseif(count($elementContentFields) > 1){
			foreach ($elementContentFields as $key => $content) {
				$value = null;
				if(isset($dataObject->content->{$attr})){
					$value = $dataObject->content->{$attr};
				}
				$dataObject->formOption->setValue("{$content}","elementContent", $value);
			}

		}
		// set attrs
		$elementAttrsFields = array("id","class","title","iconClass");
		foreach ($elementAttrsFields as $key => $attr) {
			$value = null;
			if(isset($dataObject->attrs->{$attr})){
				$value = $dataObject->attrs->{$attr};
			}
			$dataObject->formOption->setValue("{$attr}","elementAttrs", $value);
		}

		$html = '
			<h2><i class="fa fa-cube"></i> Tab & Toggle Item'.(!empty($dataObject->name)? ' - '.$dataObject->name : '').'</h2>
			<div class="row-fluid" style="padding-top:20px;">
				<div class="span5">
					<div class="input-prepend">
						<span class="add-on">Name</span>
						<input class="inputbox" name="elementName" placeholder="Element name" type="text" value="'.(isset($dataObject->name)? $dataObject->name : '').'">
					</div>
				</div>
				<div class="span7">
					<div class="form-horizontal">
						<div class="control-group elementPubLang">
							<div class="control-label">
								<label id="elementPubLang_published-lbl" for="elementPubLang_published">Published</label>
							</div>
							<div class="controls">
								<fieldset id="elementPubLang_published" class="radio btn-group btn-group-yesno">
									<input id="elementPubLang_published1" name="elementPubLang[published]" value="1" '.((isset($dataObject->published) && $dataObject->published == '1')? 'checked="checked"' : '').' type="radio">
									<label  for="elementPubLang_published1">Yes</label>
									<input id="elementPubLang_published0" name="elementPubLang[published]" value="0" '.((isset($dataObject->published) && $dataObject->published == '0')? 'checked="checked"' : '').' type="radio">
									<label for="elementPubLang_published0">No</label>
								</fieldset>
							</div>
						</div>
					</div>


				</div>
			</div>
			<!-- /.row-fluid -->
			<hr>
			<div class="row-fluid">
				<div class="span12">
					<div class="form-horizontal">';
						$html .= '<div class="row-fluid">';
							$html .='<div class="span6">';

					// attrs

					foreach ($dataObject->formOption->getFieldsets('elementAttrs') as $fieldsets => $fieldset) {
						
						$fields = $dataObject->formOption->getFieldset($fieldset->name);
						foreach($fields as $field){

							if ($field->hidden) {
								$html .= $field->input;
							}else{
								//echo'<pre>';var_dump($field);
								$html .= $field->getControlGroup();
								
							}
						}
					}
					

						$html .= '</div><div class="span6">';

					// content
					foreach ($dataObject->formOption->getFieldsets('elementContent') as $fieldsets => $fieldset) {
						foreach($dataObject->formOption->getFieldset($fieldset->name) as $field){

							if ($field->hidden) {
								$html .= $field->input;
							}else{
								//echo'<pre>';var_dump($field);
								$html .= $field->getControlGroup();
							}
						}
					}

							$html .='</div>';
						$html .='</div>';
				
					$html .='</div>
					<!-- /.form-horizontal -->
				</div>
				<!-- /.span12 -->

			</div>
			<!-- /.row-fluid -->
			
			<div class="row-fluid" style="text-align: center;">
				<hr>
				<a href="#" id="azura-setting-btn-save" class="btn btn-primary azura-setting-btn-save">Save</a>
				<a href="#" id="azura-setting-btn-cancel" class="btn btn-default azura-setting-btn-cancel">Cancel</a>
			</div>';

			$html .='
			<script>
				function jInsertIconClassValue(value, id) {
		        		var old_value = jQuery("#" + id).val();
		        		if (old_value != value) {
		        			var $elem = jQuery("#" + id);
		        			$elem.val(value);
		        			$elem.trigger("change");
		        			if (typeof($elem.get(0).onchange) === "function") {
		        				$elem.get(0).onchange();
		        			}
		        		}
		        }

	            jQuery(function($) {
	    			SqueezeBox.initialize({});
	    			SqueezeBox.assign($(\'a.modal_jform_azurafont\').get(), {
	    				parse: \'rel\'
	    			});
					
	    		});
            
            </script>';

			$html .='
			<script>
				jQuery(function($){
					$(\'.radio.btn-group label\').addClass(\'btn btn-small\');
					$(\'body\').on(\'click\',\'.btn-group label:not(.active)\',function()
					{
						var label = $(this);
						var input = $(\'#\' + label.attr(\'for\'));

						if (!input.prop(\'checked\')) {
							label.closest(\'.btn-group\').find(\'label\').removeClass(\'active btn-success btn-danger btn-primary\');
							if (input.val() == \'\') {
								label.addClass(\'active btn-primary\');
							} else if (input.val() == 0) {
								label.addClass(\'active btn-danger\');
							} else {
								label.addClass(\'active btn-success\');
							}
							input.prop(\'checked\', \'checked\');
						}
					});
					$(\'.btn-group input[checked=checked]\').each(function()
					{
						if ($(this).val() == \'\') {
							$(\'label[for=\' + $(this).attr(\'id\') + \']\').addClass(\'active btn-primary\');
						} else if ($(this).val() == 0) {
							$(\'label[for=\' + $(this).attr(\'id\') + \']\').addClass(\'active btn-danger\');
						} else {
							$(\'label[for=\' + $(this).attr(\'id\') + \']\').addClass(\'active btn-success\');
						}
					});
				});
            
            </script>';
			return $html;
	}

	protected function configAzurapopuplink($data,$dataObject){
		

		$dataObject->formOption = JForm::getInstance('com_azurapagebuilder.page.optionpopuplink', 'formoptionpopuplink');

		
		// set attrs
		$elementAttrsFields = array("id","class","title","tooltip","tooltipTitle","popup","popupLink","layout");
		foreach ($elementAttrsFields as $key => $attr) {
			$value = null;
			if(isset($dataObject->attrs->{$attr})){
				$value = $dataObject->attrs->{$attr};
			}
			$dataObject->formOption->setValue("{$attr}","elementAttrs", $value);
		}

		$html = '
			<h2><i class="fa fa-plus-square-o"></i> Popup Link'.(!empty($dataObject->name)? ' - '.$dataObject->name : '').'</h2>
			<div class="row-fluid" style="padding-top:20px;">
				<div class="span5">
					<div class="input-prepend">
						<span class="add-on">Name</span>
						<input class="inputbox" name="elementName" placeholder="Element name" type="text" value="'.(isset($dataObject->name)? $dataObject->name : '').'">
					</div>
				</div>
				<div class="span7">
					<div class="form-horizontal">
						<div class="control-group elementPubLang">
							<div class="control-label">
								<label id="elementPubLang_published-lbl" for="elementPubLang_published">Published</label>
							</div>
							<div class="controls">
								<fieldset id="elementPubLang_published" class="radio btn-group btn-group-yesno">
									<input id="elementPubLang_published1" name="elementPubLang[published]" value="1" '.((isset($dataObject->published) && $dataObject->published == '1')? 'checked="checked"' : '').' type="radio">
									<label  for="elementPubLang_published1">Yes</label>
									<input id="elementPubLang_published0" name="elementPubLang[published]" value="0" '.((isset($dataObject->published) && $dataObject->published == '0')? 'checked="checked"' : '').' type="radio">
									<label for="elementPubLang_published0">No</label>
								</fieldset>
							</div>
						</div>
					</div>


				</div>
			</div>
			<!-- /.row-fluid -->
			<hr>
			<div class="row-fluid">
				<div class="span12">
					<div class="form-horizontal">';

					// attrs

					foreach ($dataObject->formOption->getFieldsets('elementAttrs') as $fieldsets => $fieldset) {
						$html .= '<div class="row-fluid">';
							$html .='<div class="span6">';
							$key = 1;
							$fields = $dataObject->formOption->getFieldset($fieldset->name);
						foreach($fields as $field){

							if ($field->hidden) {
								$html .= $field->input;
							}else{
								//echo'<pre>';var_dump($field);
								$html .= $field->getControlGroup();
								$key++;
							}

							if($key == 5 && count($fields) >5){
								$html .= '</div><div class="span6">';
							}
						}

							$html .='</div>';
						$html .='</div>';
					}
					
					$html .='</div>
					<!-- /.form-horizontal -->
				</div>
				<!-- /.span12 -->

			</div>
			<!-- /.row-fluid -->
			
			<div class="row-fluid" style="text-align: center;">
				<hr>
				<a href="#" id="azura-setting-btn-save" class="btn btn-primary azura-setting-btn-save">Save</a>
				<a href="#" id="azura-setting-btn-cancel" class="btn btn-default azura-setting-btn-cancel">Cancel</a>
			</div>';

			$html .='
			<script>
				function jInsertFieldValue(value, id) {
		        		var old_value = jQuery("#" + id).val();
		        		if (old_value != value) {
		        			var $elem = jQuery("#" + id);
		        			$elem.val(value);
		        			$elem.trigger("change");
		        			if (typeof($elem.get(0).onchange) === "function") {
		        				$elem.get(0).onchange();
		        			}
		        		}
		        }

	            jQuery(function($) {
	    			SqueezeBox.initialize({});
	    			SqueezeBox.assign($(\'a.modal_jform_azuramedia\').get(), {
	    				parse: \'rel\'
	    			});
					
					
	                
	                // jQuery(\'body\').on(\'change\',\'#elementAttrs_popupLink\', function(event){
	                //     event.preventDefault();
	                //     var value = event.currentTarget.value;
	                    
	                //     jQuery(\'.fancybox-inner\').find(\'#elementAttrs_popupLink\').val(value);
	                // });
	    		});
            
            </script>';

			$html .='
			<script>
				jQuery(function($){
					$(\'.radio.btn-group label\').addClass(\'btn btn-small\');
					$(\'body\').on(\'click\',\'.btn-group label:not(.active)\',function()
					{
						var label = $(this);
						var input = $(\'#\' + label.attr(\'for\'));

						if (!input.prop(\'checked\')) {
							label.closest(\'.btn-group\').find(\'label\').removeClass(\'active btn-success btn-danger btn-primary\');
							if (input.val() == \'\') {
								label.addClass(\'active btn-primary\');
							} else if (input.val() == 0) {
								label.addClass(\'active btn-danger\');
							} else {
								label.addClass(\'active btn-success\');
							}
							input.prop(\'checked\', \'checked\');
						}
					});
					$(\'.btn-group input[checked=checked]\').each(function()
					{
						if ($(this).val() == \'\') {
							$(\'label[for=\' + $(this).attr(\'id\') + \']\').addClass(\'active btn-primary\');
						} else if ($(this).val() == 0) {
							$(\'label[for=\' + $(this).attr(\'id\') + \']\').addClass(\'active btn-danger\');
						} else {
							$(\'label[for=\' + $(this).attr(\'id\') + \']\').addClass(\'active btn-success\');
						}
					});
				});
            
            </script>';
			
			return $html;
	}

	protected function configAzuravideo($data,$dataObject){
		

		$dataObject->formOption = JForm::getInstance('com_azurapagebuilder.page.optionvideo', 'formoptionvideo');

		// set content
		$elementContentFields = array("link");
		if(count($elementContentFields) == 1) {
			$content = $elementContentFields[0];
			$value = '';
			if(isset($dataObject->content)){
				$value = $dataObject->content;
			}
			$dataObject->formOption->setValue("{$content}","elementContent", $value);
		}elseif(count($elementContentFields) > 1){
			foreach ($elementContentFields as $key => $content) {
				$value = null;
				if(isset($dataObject->content->{$attr})){
					$value = $dataObject->content->{$attr};
				}
				$dataObject->formOption->setValue("{$content}","elementContent", $value);
			}

		}
		// set attrs
		$elementAttrsFields = array("id","class","autoplay","loop","width","height","layout");
		foreach ($elementAttrsFields as $key => $attr) {
			$value = null;
			if(isset($dataObject->attrs->{$attr})){
				$value = $dataObject->attrs->{$attr};
			}
			$dataObject->formOption->setValue("{$attr}","elementAttrs", $value);
		}

		$html = '
			<h2><i class="fa fa-video-camera"></i> Video'.(!empty($dataObject->name)? ' - '.$dataObject->name : '').'</h2>
			<div class="row-fluid" style="padding-top:20px;">
				<div class="span5">
					<div class="input-prepend">
						<span class="add-on">Name</span>
						<input class="inputbox" name="elementName" placeholder="Element name" type="text" value="'.(isset($dataObject->name)? $dataObject->name : '').'">
					</div>
				</div>
				<div class="span7">
					<div class="form-horizontal">
						<div class="control-group elementPubLang">
							<div class="control-label">
								<label id="elementPubLang_published-lbl" for="elementPubLang_published">Published</label>
							</div>
							<div class="controls">
								<fieldset id="elementPubLang_published" class="radio btn-group btn-group-yesno">
									<input id="elementPubLang_published1" name="elementPubLang[published]" value="1" '.((isset($dataObject->published) && $dataObject->published == '1')? 'checked="checked"' : '').' type="radio">
									<label  for="elementPubLang_published1">Yes</label>
									<input id="elementPubLang_published0" name="elementPubLang[published]" value="0" '.((isset($dataObject->published) && $dataObject->published == '0')? 'checked="checked"' : '').' type="radio">
									<label for="elementPubLang_published0">No</label>
								</fieldset>
							</div>
						</div>
					</div>


				</div>
			</div>
			<!-- /.row-fluid -->
			<hr>
			<div class="row-fluid">
				<div class="span12">
					<div class="form-horizontal">';

					$html .= '<div class="row-fluid">';
						$html .='<div class="span6">';
					// content
					foreach ($dataObject->formOption->getFieldsets('elementContent') as $fieldsets => $fieldset) {
						foreach($dataObject->formOption->getFieldset($fieldset->name) as $field){

							if ($field->hidden) {
								$html .= $field->input;
							}else{
								//echo'<pre>';var_dump($field);
								$html .= $field->getControlGroup();
							}
						}
					}
					

					

						// attrs


					foreach ($dataObject->formOption->getFieldsets('elementAttrs') as $fieldsets => $fieldset) {
						$fields = $dataObject->formOption->getFieldset($fieldset->name);
						$key = 1;
						foreach($fields as $field){

							if ($field->hidden) {
								$html .= $field->input;
							}else{
								//echo'<pre>';var_dump($field);
								$html .= $field->getControlGroup();
								$key++;
							}

							if($key == 5 && count($fields) > 5){
								$html .='</div><div class="span6">';
							}
						}
					}

					$html .='</div>';

				$html .='</div>';
					
					$html .='</div>
					<!-- /.form-horizontal -->
				</div>
				<!-- /.span12 -->

			</div>
			<!-- /.row-fluid -->
			
			<div class="row-fluid" style="text-align: center;">
				<hr>
				<a href="#" id="azura-setting-btn-save" class="btn btn-primary azura-setting-btn-save">Save</a>
				<a href="#" id="azura-setting-btn-cancel" class="btn btn-default azura-setting-btn-cancel">Cancel</a>
			</div>';

			$html .='
			<script>
				jQuery(function($){
					$(\'.radio.btn-group label\').addClass(\'btn btn-small\');
					$(\'body\').on(\'click\',\'.btn-group label:not(.active)\',function()
					{
						var label = $(this);
						var input = $(\'#\' + label.attr(\'for\'));

						if (!input.prop(\'checked\')) {
							label.closest(\'.btn-group\').find(\'label\').removeClass(\'active btn-success btn-danger btn-primary\');
							if (input.val() == \'\') {
								label.addClass(\'active btn-primary\');
							} else if (input.val() == 0) {
								label.addClass(\'active btn-danger\');
							} else {
								label.addClass(\'active btn-success\');
							}
							input.prop(\'checked\', \'checked\');
						}
					});
					$(\'.btn-group input[checked=checked]\').each(function()
					{
						if ($(this).val() == \'\') {
							$(\'label[for=\' + $(this).attr(\'id\') + \']\').addClass(\'active btn-primary\');
						} else if ($(this).val() == 0) {
							$(\'label[for=\' + $(this).attr(\'id\') + \']\').addClass(\'active btn-danger\');
						} else {
							$(\'label[for=\' + $(this).attr(\'id\') + \']\').addClass(\'active btn-success\');
						}
					});
				});
            
            </script>';
			return $html;
	}

	protected function configAzuraclientsay($data,$dataObject){
		

		$dataObject->formOption = JForm::getInstance('com_azurapagebuilder.page.optionclientsay', 'formoptionclientsay');

		// set content
		$elementContentFields = array("comment");
		if(count($elementContentFields) == 1) {
			$content = $elementContentFields[0];
			$value = '';
			if(isset($dataObject->content)){
				$value = $dataObject->content;
			}
			$dataObject->formOption->setValue("{$content}","elementContent", $value);
		}elseif(count($elementContentFields) > 1){
			foreach ($elementContentFields as $key => $content) {
				$value = null;
				if(isset($dataObject->content->{$attr})){
					$value = $dataObject->content->{$attr};
				}
				$dataObject->formOption->setValue("{$content}","elementContent", $value);
			}

		}

		$elementAttrsFields = array("name","via","photo");
		foreach ($elementAttrsFields as $key => $attr) {
			$value = null;
			if(isset($dataObject->attrs->{$attr})){
				$value = $dataObject->attrs->{$attr};
			}
			$dataObject->formOption->setValue("{$attr}","elementAttrs", $value);
		}

		$html = '
			<h2><i class="fa fa-comment-o"></i> Client Say'.(!empty($dataObject->name)? ' - '.$dataObject->name : '').'</h2>
			<div class="row-fluid" style="padding-top:20px;">
				<div class="span5">
					<div class="input-prepend">
						<span class="add-on">Name</span>
						<input class="inputbox" name="elementName" placeholder="Element name" type="text" value="'.(isset($dataObject->name)? $dataObject->name : '').'">
					</div>
				</div>
				<div class="span7">
					<div class="form-horizontal">
						<div class="control-group elementPubLang">
							<div class="control-label">
								<label id="elementPubLang_published-lbl" for="elementPubLang_published">Published</label>
							</div>
							<div class="controls">
								<fieldset id="elementPubLang_published" class="radio btn-group btn-group-yesno">
									<input id="elementPubLang_published1" name="elementPubLang[published]" value="1" '.((isset($dataObject->published) && $dataObject->published == '1')? 'checked="checked"' : '').' type="radio">
									<label  for="elementPubLang_published1">Yes</label>
									<input id="elementPubLang_published0" name="elementPubLang[published]" value="0" '.((isset($dataObject->published) && $dataObject->published == '0')? 'checked="checked"' : '').' type="radio">
									<label for="elementPubLang_published0">No</label>
								</fieldset>
							</div>
						</div>
					</div>


				</div>
			</div>
			<!-- /.row-fluid -->
			<hr>
			<div class="row-fluid">
				<div class="span12">
					<div class="form-horizontal">';


					foreach ($dataObject->formOption->getFieldsets('elementAttrs') as $fieldsets => $fieldset) {
						foreach($dataObject->formOption->getFieldset($fieldset->name) as $field){

							if ($field->hidden) {
								$html .= $field->input;
							}else{
								//echo'<pre>';var_dump($field);
								$html .= $field->getControlGroup();
							}
						}
					}

					foreach ($dataObject->formOption->getFieldsets('elementContent') as $fieldsets => $fieldset) {
						foreach($dataObject->formOption->getFieldset($fieldset->name) as $field){

							if ($field->hidden) {
								$html .= $field->input;
							}else{
								//echo'<pre>';var_dump($field);
								$html .= $field->getControlGroup();
							}
						}
					}
					
					$html .='</div>
					<!-- /.form-horizontal -->
				</div>
				<!-- /.span12 -->

			</div>
			<!-- /.row-fluid -->
			
			<div class="row-fluid" style="text-align: center;">
				<hr>
				<a href="#" id="azura-setting-btn-save" class="btn btn-primary azura-setting-btn-save">Save</a>
				<a href="#" id="azura-setting-btn-cancel" class="btn btn-default azura-setting-btn-cancel">Cancel</a>
			</div>';

			$html .='
			<script>
				function jInsertFieldValue(value, id) {
		        		var old_value = jQuery("#" + id).val();
		        		if (old_value != value) {
		        			var $elem = jQuery("#" + id);
		        			$elem.val(value);
		        			$elem.trigger("change");
		        			if (typeof($elem.get(0).onchange) === "function") {
		        				$elem.get(0).onchange();
		        			}
		        		}
		        }

	            jQuery(function($) {
	    			SqueezeBox.initialize({});
	    			SqueezeBox.assign($(\'a.modal_jform_azuramedia\').get(), {
	    				parse: \'rel\'
	    			});
					
					
	                
	                jQuery(\'body\').on(\'change\',\'#elementAttrs_photo\', function(event){
	                    event.preventDefault();
	                    var value = event.currentTarget.value;
	                    
	                    jQuery(\'.fancybox-inner\').find(\'#elementAttrs_photo\').val(value);
	                });
	    		});

				jQuery(function($){
					$(\'.radio.btn-group label\').addClass(\'btn btn-small\');
					$(\'body\').on(\'click\',\'.btn-group label:not(.active)\',function()
					{
						var label = $(this);
						var input = $(\'#\' + label.attr(\'for\'));

						if (!input.prop(\'checked\')) {
							label.closest(\'.btn-group\').find(\'label\').removeClass(\'active btn-success btn-danger btn-primary\');
							if (input.val() == \'\') {
								label.addClass(\'active btn-primary\');
							} else if (input.val() == 0) {
								label.addClass(\'active btn-danger\');
							} else {
								label.addClass(\'active btn-success\');
							}
							input.prop(\'checked\', \'checked\');
						}
					});
					$(\'.btn-group input[checked=checked]\').each(function()
					{
						if ($(this).val() == \'\') {
							$(\'label[for=\' + $(this).attr(\'id\') + \']\').addClass(\'active btn-primary\');
						} else if ($(this).val() == 0) {
							$(\'label[for=\' + $(this).attr(\'id\') + \']\').addClass(\'active btn-danger\');
						} else {
							$(\'label[for=\' + $(this).attr(\'id\') + \']\').addClass(\'active btn-success\');
						}
					});
				});
            
            </script>';
			return $html;
	}

	protected function configAzurasocialbuttons($data,$dataObject){
		

		$dataObject->formOption = JForm::getInstance('com_azurapagebuilder.page.optionsocialbuttons', 'formoptionsocialbuttons');

		
		// set attrs
		$elementAttrsFields = array("wrapper","id","class","customStyle","layout");
		foreach ($elementAttrsFields as $key => $attr) {
			$value = null;
			if(isset($dataObject->attrs->{$attr})){
				$value = $dataObject->attrs->{$attr};
			}
			$dataObject->formOption->setValue("{$attr}","elementAttrs", $value);
		}

		$html = '
			<h2><i class="fa fa-comments-o"></i> Social Buttons'.(!empty($dataObject->name)? ' - '.$dataObject->name : '').'</h2>
			<div class="row-fluid" style="padding-top:20px;">
				<div class="span5">
					<div class="input-prepend">
						<span class="add-on">Name</span>
						<input class="inputbox" name="elementName" placeholder="Element name" type="text" value="'.(isset($dataObject->name)? $dataObject->name : '').'">
					</div>
				</div>
				<div class="span7">
					<div class="form-horizontal">
						<div class="control-group elementPubLang">
							<div class="control-label">
								<label id="elementPubLang_published-lbl" for="elementPubLang_published">Published</label>
							</div>
							<div class="controls">
								<fieldset id="elementPubLang_published" class="radio btn-group btn-group-yesno">
									<input id="elementPubLang_published1" name="elementPubLang[published]" value="1" '.((isset($dataObject->published) && $dataObject->published == '1')? 'checked="checked"' : '').' type="radio">
									<label  for="elementPubLang_published1">Yes</label>
									<input id="elementPubLang_published0" name="elementPubLang[published]" value="0" '.((isset($dataObject->published) && $dataObject->published == '0')? 'checked="checked"' : '').' type="radio">
									<label for="elementPubLang_published0">No</label>
								</fieldset>
							</div>
						</div>
					</div>


				</div>
			</div>
			<!-- /.row-fluid -->
			<hr>
			<div class="row-fluid">
				<div class="span12">
					<div class="form-horizontal">';

					// attrs

					foreach ($dataObject->formOption->getFieldsets('elementAttrs') as $fieldsets => $fieldset) {
						foreach($dataObject->formOption->getFieldset($fieldset->name) as $field){

							if ($field->hidden) {
								$html .= $field->input;
							}else{
								//echo'<pre>';var_dump($field);
								$html .= $field->getControlGroup();
							}
						}
					}
					
					$html .='</div>
					<!-- /.form-horizontal -->
				</div>
				<!-- /.span12 -->

			</div>
			<!-- /.row-fluid -->
			
			<div class="row-fluid" style="text-align: center;">
				<hr>
				<a href="#" id="azura-setting-btn-save" class="btn btn-primary azura-setting-btn-save">Save</a>
				<a href="#" id="azura-setting-btn-cancel" class="btn btn-default azura-setting-btn-cancel">Cancel</a>
			</div>';


			$html .='
			<script>
				jQuery(function($){
					$(\'.radio.btn-group label\').addClass(\'btn btn-small\');
					$(\'body\').on(\'click\',\'.btn-group label:not(.active)\',function()
					{
						var label = $(this);
						var input = $(\'#\' + label.attr(\'for\'));

						if (!input.prop(\'checked\')) {
							label.closest(\'.btn-group\').find(\'label\').removeClass(\'active btn-success btn-danger btn-primary\');
							if (input.val() == \'\') {
								label.addClass(\'active btn-primary\');
							} else if (input.val() == 0) {
								label.addClass(\'active btn-danger\');
							} else {
								label.addClass(\'active btn-success\');
							}
							input.prop(\'checked\', \'checked\');
						}
					});
					$(\'.btn-group input[checked=checked]\').each(function()
					{
						if ($(this).val() == \'\') {
							$(\'label[for=\' + $(this).attr(\'id\') + \']\').addClass(\'active btn-primary\');
						} else if ($(this).val() == 0) {
							$(\'label[for=\' + $(this).attr(\'id\') + \']\').addClass(\'active btn-danger\');
						} else {
							$(\'label[for=\' + $(this).attr(\'id\') + \']\').addClass(\'active btn-success\');
						}
					});
				});
            
            </script>';

			return $html;
	}

	protected function configAzurasocialbuttonsbutton($data,$dataObject){
		

		$dataObject->formOption = JForm::getInstance('com_azurapagebuilder.page.optionsocialbuttonsbutton', 'formoptionsocialbuttonsbutton');

		
		// set attrs
		$elementAttrsFields = array("id","class","link","target","iconClass","text","tooltip", "tooltipTitle" ,"backgroundColor","customStyle");
		foreach ($elementAttrsFields as $key => $attr) {
			$value = null;
			if(isset($dataObject->attrs->{$attr})){
				$value = $dataObject->attrs->{$attr};
			}
			$dataObject->formOption->setValue("{$attr}","elementAttrs", $value);
		}

		$html = '
			<h2><i class="fa fa-comments-o"></i> Button'.(!empty($dataObject->name)? ' - '.$dataObject->name : '').'</h2>
			<div class="row-fluid" style="padding-top:20px;">
				<div class="span5">
					<div class="input-prepend">
						<span class="add-on">Name</span>
						<input class="inputbox" name="elementName" placeholder="Element name" type="text" value="'.(isset($dataObject->name)? $dataObject->name : '').'">
					</div>
				</div>
				<div class="span7">
					<div class="form-horizontal">
						<div class="control-group elementPubLang">
							<div class="control-label">
								<label id="elementPubLang_published-lbl" for="elementPubLang_published">Published</label>
							</div>
							<div class="controls">
								<fieldset id="elementPubLang_published" class="radio btn-group btn-group-yesno">
									<input id="elementPubLang_published1" name="elementPubLang[published]" value="1" '.((isset($dataObject->published) && $dataObject->published == '1')? 'checked="checked"' : '').' type="radio">
									<label  for="elementPubLang_published1">Yes</label>
									<input id="elementPubLang_published0" name="elementPubLang[published]" value="0" '.((isset($dataObject->published) && $dataObject->published == '0')? 'checked="checked"' : '').' type="radio">
									<label for="elementPubLang_published0">No</label>
								</fieldset>
							</div>
						</div>
					</div>


				</div>
			</div>
			<!-- /.row-fluid -->
			<hr>
			<div class="row-fluid">
				<div class="span12">
					<div class="form-horizontal">';

					$html .= '<div class="row-fluid">';
						$html .='<div class="span6">';
						// attrs

					foreach ($dataObject->formOption->getFieldsets('elementAttrs') as $fieldsets => $fieldset) {
						$key = 1;
						$fields = $dataObject->formOption->getFieldset($fieldset->name);
						foreach($fields as $field){

							if ($field->hidden) {
								$html .= $field->input;
							}else{
								//echo'<pre>';var_dump($field);
								$html .= $field->getControlGroup();
								$key++;
							}

							if($key == 7 && count($fields) > 7){
								$html .='</div><div class="span6">';
							}
						}
					}


					$html .='</div>';

				$html .='</div>';
					
					$html .='</div>
					<!-- /.form-horizontal -->
				</div>
				<!-- /.span12 -->

			</div>
			<!-- /.row-fluid -->
			
			<div class="row-fluid" style="text-align: center;">
				<hr>
				<a href="#" id="azura-setting-btn-save" class="btn btn-primary azura-setting-btn-save">Save</a>
				<a href="#" id="azura-setting-btn-cancel" class="btn btn-default azura-setting-btn-cancel">Cancel</a>
			</div>';

			$html .='
			<link rel="stylesheet" href="'.JURI::root(true).'/media/jui/css/jquery.minicolors.css" type="text/css" />
			<script src="'.JURI::root(true).'/media/system/js/html5fallback.js" type="text/javascript"></script>
			<script src="'.JURI::root(true).'/media/jui/js/jquery.minicolors.min.js" type="text/javascript"></script>
			<script>
				jQuery(document).ready(function (){
					jQuery(\'.minicolors\').each(function() {
						jQuery(this).minicolors({
							control: jQuery(this).attr(\'data-control\') || \'hue\',
							position: jQuery(this).attr(\'data-position\') || \'right\',
							theme: \'bootstrap\'
						});
					});
				});
</script>';

			$html .='
			<script>

				function jInsertIconClassValue(value, id) {
		        		var old_value = jQuery("#" + id).val();
		        		if (old_value != value) {
		        			var $elem = jQuery("#" + id);
		        			$elem.val(value);
		        			$elem.trigger("change");
		        			if (typeof($elem.get(0).onchange) === "function") {
		        				$elem.get(0).onchange();
		        			}
		        		}
		        }

	            jQuery(function($) {
	    			SqueezeBox.initialize({});
	    			SqueezeBox.assign($(\'a.modal_jform_azurafont\').get(), {
	    				parse: \'rel\'
	    			});
					
	    		});
            
            </script>';

			$html .='
			<script>
				jQuery(function($){
					$(\'.radio.btn-group label\').addClass(\'btn btn-small\');
					$(\'body\').on(\'click\',\'.btn-group label:not(.active)\',function()
					{
						var label = $(this);
						var input = $(\'#\' + label.attr(\'for\'));

						if (!input.prop(\'checked\')) {
							label.closest(\'.btn-group\').find(\'label\').removeClass(\'active btn-success btn-danger btn-primary\');
							if (input.val() == \'\') {
								label.addClass(\'active btn-primary\');
							} else if (input.val() == 0) {
								label.addClass(\'active btn-danger\');
							} else {
								label.addClass(\'active btn-success\');
							}
							input.prop(\'checked\', \'checked\');
						}
					});
					$(\'.btn-group input[checked=checked]\').each(function()
					{
						if ($(this).val() == \'\') {
							$(\'label[for=\' + $(this).attr(\'id\') + \']\').addClass(\'active btn-primary\');
						} else if ($(this).val() == 0) {
							$(\'label[for=\' + $(this).attr(\'id\') + \']\').addClass(\'active btn-danger\');
						} else {
							$(\'label[for=\' + $(this).attr(\'id\') + \']\').addClass(\'active btn-success\');
						}
					});
				});
            
            </script>';

			return $html;
	}

	protected function configAzuragmap($data,$dataObject){
		

		$dataObject->formOption = JForm::getInstance('com_azurapagebuilder.page.optiongmap', 'formoptiongmap');

		// set attrs
		$elementAttrsFields = array("id","class","gmapLat","gmapLog","gmapPanControl","gmapZoomControl","gmapTypeControl","gmapStreetviewControl","gmapScrollWheel","gmapZoom","gmapTypeId");
		foreach ($elementAttrsFields as $key => $attr) {
			$value = null;
			if(isset($dataObject->attrs->{$attr})){
				$value = $dataObject->attrs->{$attr};
			}
			$dataObject->formOption->setValue("{$attr}","elementAttrs", $value);
		}

		$html = '
			<h2><i class="fa fa-map-marker"></i> Google Map'.(!empty($dataObject->name)? ' - '.$dataObject->name : '').'</h2>
			<div class="row-fluid" style="padding-top:20px;">
				<div class="span5">
					<div class="input-prepend">
						<span class="add-on">Name</span>
						<input class="inputbox" name="elementName" placeholder="Element name" type="text" value="'.(isset($dataObject->name)? $dataObject->name : '').'">
					</div>
				</div>
				<div class="span7">
					<div class="form-horizontal">
						<div class="control-group elementPubLang">
							<div class="control-label">
								<label id="elementPubLang_published-lbl" for="elementPubLang_published">Published</label>
							</div>
							<div class="controls">
								<fieldset id="elementPubLang_published" class="radio btn-group btn-group-yesno">
									<input id="elementPubLang_published1" name="elementPubLang[published]" value="1" '.((isset($dataObject->published) && $dataObject->published == '1')? 'checked="checked"' : '').' type="radio">
									<label  for="elementPubLang_published1">Yes</label>
									<input id="elementPubLang_published0" name="elementPubLang[published]" value="0" '.((isset($dataObject->published) && $dataObject->published == '0')? 'checked="checked"' : '').' type="radio">
									<label for="elementPubLang_published0">No</label>
								</fieldset>
							</div>
						</div>
					</div>


				</div>
			</div>
			<!-- /.row-fluid -->
			<hr>
			<div class="row-fluid">
				<div class="span12">
					<div class="form-horizontal">';

					$html .= '<div class="row-fluid">';
						$html .='<div class="span6">';
						// attrs

					foreach ($dataObject->formOption->getFieldsets('elementAttrs') as $fieldsets => $fieldset) {
						$key = 1;
						$fields = $dataObject->formOption->getFieldset($fieldset->name);
						foreach($fields as $field){

							if ($field->hidden) {
								$html .= $field->input;
							}else{
								//echo'<pre>';var_dump($field);
								$html .= $field->getControlGroup();
								$key++;
							}

							if($key == 8 && count($fields) > 8){
								$html .='</div><div class="span6">';
							}
						}
					}


					$html .='</div>';

				$html .='</div>';
					
					$html .='</div>
					<!-- /.form-horizontal -->
				</div>
				<!-- /.span12 -->

			</div>
			<!-- /.row-fluid -->
			
			<div class="row-fluid" style="text-align: center;">
				<hr>
				<a href="#" id="azura-setting-btn-save" class="btn btn-primary azura-setting-btn-save">Save</a>
				<a href="#" id="azura-setting-btn-cancel" class="btn btn-default azura-setting-btn-cancel">Cancel</a>
			</div>';

			

			$html .='
			<script>

				function jInsertIconClassValue(value, id) {
		        		var old_value = jQuery("#" + id).val();
		        		if (old_value != value) {
		        			var $elem = jQuery("#" + id);
		        			$elem.val(value);
		        			$elem.trigger("change");
		        			if (typeof($elem.get(0).onchange) === "function") {
		        				$elem.get(0).onchange();
		        			}
		        		}
		        }

	            jQuery(function($) {
	    			SqueezeBox.initialize({});
	    			SqueezeBox.assign($(\'a.modal_jform_azuragmapselect\').get(), {
	    				parse: \'rel\'
	    			});
					
	    		});
            
            </script>';

			$html .='
			<script>
				jQuery(function($){
					$(\'.radio.btn-group label\').addClass(\'btn btn-small\');
					$(\'body\').on(\'click\',\'.btn-group label:not(.active)\',function()
					{
						var label = $(this);
						var input = $(\'#\' + label.attr(\'for\'));

						if (!input.prop(\'checked\')) {
							label.closest(\'.btn-group\').find(\'label\').removeClass(\'active btn-success btn-danger btn-primary\');
							if (input.val() == \'\') {
								label.addClass(\'active btn-primary\');
							} else if (input.val() == 0) {
								label.addClass(\'active btn-danger\');
							} else {
								label.addClass(\'active btn-success\');
							}
							input.prop(\'checked\', \'checked\');
						}
					});
					$(\'.btn-group input[checked=checked]\').each(function()
					{
						if ($(this).val() == \'\') {
							$(\'label[for=\' + $(this).attr(\'id\') + \']\').addClass(\'active btn-primary\');
						} else if ($(this).val() == 0) {
							$(\'label[for=\' + $(this).attr(\'id\') + \']\').addClass(\'active btn-danger\');
						} else {
							$(\'label[for=\' + $(this).attr(\'id\') + \']\').addClass(\'active btn-success\');
						}
					});
				});
            
            </script>';
			return $html;
	}

	protected function configAzurabuttonlink($data,$dataObject){
		

		$dataObject->formOption = JForm::getInstance('com_azurapagebuilder.page.optionbuttonlink', 'formoptionbuttonlink');
		$elementAttrsFields = array("id","class","text","image","link","title","target");
		foreach ($elementAttrsFields as $key => $attr) {
			$value = null;
			if(isset($dataObject->attrs->{$attr})){
				$value = $dataObject->attrs->{$attr};
			}
			$dataObject->formOption->setValue("{$attr}","elementAttrs", $value);
		}

		$html = '
			<h2><i class="fa fa-link"></i> Link Button'.(!empty($dataObject->name)? ' - '.$dataObject->name : '').'</h2>
			<div class="row-fluid" style="padding-top:20px;">
				<div class="span5">
					<div class="input-prepend">
						<span class="add-on">Name</span>
						<input class="inputbox" name="elementName" placeholder="Element name" type="text" value="'.(isset($dataObject->name)? $dataObject->name : '').'">
					</div>
				</div>
				<div class="span7">
					<div class="form-horizontal">
						<div class="control-group elementPubLang">
							<div class="control-label">
								<label id="elementPubLang_published-lbl" for="elementPubLang_published">Published</label>
							</div>
							<div class="controls">
								<fieldset id="elementPubLang_published" class="radio btn-group btn-group-yesno">
									<input id="elementPubLang_published1" name="elementPubLang[published]" value="1" '.((isset($dataObject->published) && $dataObject->published == '1')? 'checked="checked"' : '').' type="radio">
									<label  for="elementPubLang_published1">Yes</label>
									<input id="elementPubLang_published0" name="elementPubLang[published]" value="0" '.((isset($dataObject->published) && $dataObject->published == '0')? 'checked="checked"' : '').' type="radio">
									<label for="elementPubLang_published0">No</label>
								</fieldset>
							</div>
						</div>
					</div>


				</div>
			</div>
			<!-- /.row-fluid -->
			<hr>
			<div class="row-fluid">
				<div class="span12">
					<div class="form-horizontal">';


					foreach ($dataObject->formOption->getFieldsets('elementAttrs') as $fieldsets => $fieldset) {
						foreach($dataObject->formOption->getFieldset($fieldset->name) as $field){

							if ($field->hidden) {
								$html .= $field->input;
							}else{
								//echo'<pre>';var_dump($field);
								$html .= $field->getControlGroup();
							}
						}
					}
					
					$html .='</div>
					<!-- /.form-horizontal -->
				</div>
				<!-- /.span12 -->

			</div>
			<!-- /.row-fluid -->
			
			<div class="row-fluid" style="text-align: center;">
				<hr>
				<a href="#" id="azura-setting-btn-save" class="btn btn-primary azura-setting-btn-save">Save</a>
				<a href="#" id="azura-setting-btn-cancel" class="btn btn-default azura-setting-btn-cancel">Cancel</a>
			</div>';

			$html .='
			<script>
				function jInsertFieldValue(value, id) {
		        		var old_value = jQuery("#" + id).val();
		        		if (old_value != value) {
		        			var $elem = jQuery("#" + id);
		        			$elem.val(value);
		        			$elem.trigger("change");
		        			if (typeof($elem.get(0).onchange) === "function") {
		        				$elem.get(0).onchange();
		        			}
		        		}
		        }

	            jQuery(function($) {
	    			SqueezeBox.initialize({});
	    			SqueezeBox.assign($(\'a.modal_jform_azuramedia\').get(), {
	    				parse: \'rel\'
	    			});
					
	    		});

				jQuery(function($){
					//$(\'*[rel=tooltip]\').tooltip();

					// Turn radios into btn-group
					$(\'.radio.btn-group label\').addClass(\'btn btn-small\');
					$(\'body\').on(\'click\',\'.btn-group label:not(.active)\',function()
					{
						var label = $(this);
						var input = $(\'#\' + label.attr(\'for\'));

						if (!input.prop(\'checked\')) {
							label.closest(\'.btn-group\').find(\'label\').removeClass(\'active btn-success btn-danger btn-primary\');
							if (input.val() == \'\') {
								label.addClass(\'active btn-primary\');
							} else if (input.val() == 0) {
								label.addClass(\'active btn-danger\');
							} else {
								label.addClass(\'active btn-success\');
							}
							input.prop(\'checked\', \'checked\');
						}
					});
					$(\'.btn-group input[checked=checked]\').each(function()
					{
						if ($(this).val() == \'\') {
							$(\'label[for=\' + $(this).attr(\'id\') + \']\').addClass(\'active btn-primary\');
						} else if ($(this).val() == 0) {
							$(\'label[for=\' + $(this).attr(\'id\') + \']\').addClass(\'active btn-danger\');
						} else {
							$(\'label[for=\' + $(this).attr(\'id\') + \']\').addClass(\'active btn-success\');
						}
					});
				});
            
            </script>';
			return $html;
	}

	protected function configAzurabscarousel($data,$dataObject){
		

		$dataObject->formOption = JForm::getInstance('com_azurapagebuilder.page.optionbscarousel', 'formoptionbscarousel');

		
		// set attrs
		$elementAttrsFields = array("id","class","interval","pause","wrap","navigation","pagination","layout");
		foreach ($elementAttrsFields as $key => $attr) {
			$value = null;
			if(isset($dataObject->attrs->{$attr})){
				$value = $dataObject->attrs->{$attr};
			}
			$dataObject->formOption->setValue("{$attr}","elementAttrs", $value);
		}

		$html = '
			<h2><i class="fa fa-sliders"></i> Bootstrap Carousel Slider'.(!empty($dataObject->name)? ' - '.$dataObject->name : '').'</h2>
			<div class="row-fluid" style="padding-top:20px;">
				<div class="span5">
					<div class="input-prepend">
						<span class="add-on">Name</span>
						<input class="inputbox" name="elementName" placeholder="Element name" type="text" value="'.(isset($dataObject->name)? $dataObject->name : '').'">
					</div>
				</div>
				<div class="span7">
					<div class="form-horizontal">
						<div class="control-group elementPubLang">
							<div class="control-label">
								<label id="elementPubLang_published-lbl" for="elementPubLang_published">Published</label>
							</div>
							<div class="controls">
								<fieldset id="elementPubLang_published" class="radio btn-group btn-group-yesno">
									<input id="elementPubLang_published1" name="elementPubLang[published]" value="1" '.((isset($dataObject->published) && $dataObject->published == '1')? 'checked="checked"' : '').' type="radio">
									<label  for="elementPubLang_published1">Yes</label>
									<input id="elementPubLang_published0" name="elementPubLang[published]" value="0" '.((isset($dataObject->published) && $dataObject->published == '0')? 'checked="checked"' : '').' type="radio">
									<label for="elementPubLang_published0">No</label>
								</fieldset>
							</div>
						</div>
					</div>


				</div>
			</div>
			<!-- /.row-fluid -->
			<hr>
			<div class="row-fluid">
				<div class="span12">
					<div class="form-horizontal">';

					// attrs

					foreach ($dataObject->formOption->getFieldsets('elementAttrs') as $fieldsets => $fieldset) {
						$html .= '<div class="row-fluid">';
							$html .='<div class="span6">';
							$key = 1;
							$owlfields = $dataObject->formOption->getFieldset($fieldset->name);
						foreach($owlfields as $field){

							if ($field->hidden) {
								$html .= $field->input;
							}else{
								//echo'<pre>';var_dump($field);
								$html .= $field->getControlGroup();
								$key++;
							}

							if($key == 8 && count($owlfields) >8){
								$html .= '</div><div class="span6">';
							}
						}

							$html .='</div>';
						$html .='</div>';
					}
					
					$html .='</div>
					<!-- /.form-horizontal -->
				</div>
				<!-- /.span12 -->

			</div>
			<!-- /.row-fluid -->
			
			<div class="row-fluid" style="text-align: center;">
				<hr>
				<a href="#" id="azura-setting-btn-save" class="btn btn-primary azura-setting-btn-save">Save</a>
				<a href="#" id="azura-setting-btn-cancel" class="btn btn-default azura-setting-btn-cancel">Cancel</a>
			</div>';

			$html .='
			<script>
				jQuery(function($){

					// Turn radios into btn-group
					$(\'.radio.btn-group label\').addClass(\'btn btn-small\');
					$(\'body\').on(\'click\',\'.btn-group label:not(.active)\',function()
					{
						var label = $(this);
						var input = $(\'#\' + label.attr(\'for\'));

						if (!input.prop(\'checked\')) {
							label.closest(\'.btn-group\').find(\'label\').removeClass(\'active btn-success btn-danger btn-primary\');
							if (input.val() == \'\') {
								label.addClass(\'active btn-primary\');
							} else if (input.val() == 0) {
								label.addClass(\'active btn-danger\');
							} else {
								label.addClass(\'active btn-success\');
							}
							input.prop(\'checked\', \'checked\');
						}
					});
					$(\'.btn-group input[checked=checked]\').each(function()
					{
						if ($(this).val() == \'\') {
							$(\'label[for=\' + $(this).attr(\'id\') + \']\').addClass(\'active btn-primary\');
						} else if ($(this).val() == 0) {
							$(\'label[for=\' + $(this).attr(\'id\') + \']\').addClass(\'active btn-danger\');
						} else {
							$(\'label[for=\' + $(this).attr(\'id\') + \']\').addClass(\'active btn-success\');
						}
					});
				});
            
            </script>';
			
			return $html;
	}

	protected function configAzurabscarouselitem($data,$dataObject){
		

		$dataObject->formOption = JForm::getInstance('com_azurapagebuilder.page.optionbscarouselitem', 'formoptionbscarouselitem');

		// set content
		$elementContentFields = array("caption");
		if(count($elementContentFields) == 1) {
			$content = $elementContentFields[0];
			$value = '';
			if(isset($dataObject->content)){
				$value = $dataObject->content;
			}
			$dataObject->formOption->setValue("{$content}","elementContent", $value);
		}elseif(count($elementContentFields) > 1){
			foreach ($elementContentFields as $key => $content) {
				$value = null;
				if(isset($dataObject->content->{$attr})){
					$value = $dataObject->content->{$attr};
				}
				$dataObject->formOption->setValue("{$content}","elementContent", $value);
			}

		}
		// set attrs
		$elementAttrsFields = array("id","class","image","layout");
		foreach ($elementAttrsFields as $key => $attr) {
			$value = null;
			if(isset($dataObject->attrs->{$attr})){
				$value = $dataObject->attrs->{$attr};
			}
			$dataObject->formOption->setValue("{$attr}","elementAttrs", $value);
		}

		$html = '
			<h2><i class="fa fa-sliders"></i> Slide'.(!empty($dataObject->name)? ' - '.$dataObject->name : '').'</h2>
			<div class="row-fluid" style="padding-top:20px;">
				<div class="span5">
					<div class="input-prepend">
						<span class="add-on">Name</span>
						<input class="inputbox" name="elementName" placeholder="Element name" type="text" value="'.(isset($dataObject->name)? $dataObject->name : '').'">
					</div>
				</div>
				<div class="span7">
					<div class="form-horizontal">
						<div class="control-group elementPubLang">
							<div class="control-label">
								<label id="elementPubLang_published-lbl" for="elementPubLang_published">Published</label>
							</div>
							<div class="controls">
								<fieldset id="elementPubLang_published" class="radio btn-group btn-group-yesno">
									<input id="elementPubLang_published1" name="elementPubLang[published]" value="1" '.((isset($dataObject->published) && $dataObject->published == '1')? 'checked="checked"' : '').' type="radio">
									<label  for="elementPubLang_published1">Yes</label>
									<input id="elementPubLang_published0" name="elementPubLang[published]" value="0" '.((isset($dataObject->published) && $dataObject->published == '0')? 'checked="checked"' : '').' type="radio">
									<label for="elementPubLang_published0">No</label>
								</fieldset>
							</div>
						</div>
					</div>


				</div>
			</div>
			<!-- /.row-fluid -->
			<hr>
			<div class="row-fluid">
				<div class="span12">
					<div class="form-horizontal">';

					$html .= '<div class="row-fluid">';
						$html .='<div class="span6">';
						// attrs

					foreach ($dataObject->formOption->getFieldsets('elementAttrs') as $fieldsets => $fieldset) {
						foreach($dataObject->formOption->getFieldset($fieldset->name) as $field){

							if ($field->hidden) {
								$html .= $field->input;
							}else{
								//echo'<pre>';var_dump($field);
								$html .= $field->getControlGroup();
							}
						}
					}

					$html .='</div><div class="span6">';

					// content
					foreach ($dataObject->formOption->getFieldsets('elementContent') as $fieldsets => $fieldset) {
						foreach($dataObject->formOption->getFieldset($fieldset->name) as $field){

							if ($field->hidden) {
								$html .= $field->input;
							}else{
								//echo'<pre>';var_dump($field);
								$html .= $field->getControlGroup();
							}
						}
					}

					$html .='</div>';

				$html .='</div>';
					
					$html .='</div>
					<!-- /.form-horizontal -->
				</div>
				<!-- /.span12 -->

			</div>
			<!-- /.row-fluid -->
			
			<div class="row-fluid" style="text-align: center;">
				<hr>
				<a href="#" id="azura-setting-btn-save" class="btn btn-primary azura-setting-btn-save">Save</a>
				<a href="#" id="azura-setting-btn-cancel" class="btn btn-default azura-setting-btn-cancel">Cancel</a>
			</div>';
			$html .='
			<script>
				function jInsertFieldValue(value, id) {
		        		var old_value = jQuery("#" + id).val();
		        		if (old_value != value) {
		        			var $elem = jQuery("#" + id);
		        			$elem.val(value);
		        			$elem.trigger("change");
		        			if (typeof($elem.get(0).onchange) === "function") {
		        				$elem.get(0).onchange();
		        			}
		        		}
		        }

	            jQuery(function($) {
	    			SqueezeBox.initialize({});
	    			SqueezeBox.assign($(\'a.modal_jform_azuramedia\').get(), {
	    				parse: \'rel\'
	    			});
					
					
	                
	                // jQuery(\'body\').on(\'change\',\'#elementAttrs_src\', function(event){
	                //     event.preventDefault();
	                //     var value = event.currentTarget.value;
	                    
	                //     jQuery(\'.fancybox-inner\').find(\'#elementAttrs_src\').val(value);
	                // });
	    		});
            
            </script>';

			$html .='
			<script>
				jQuery(function($){
					

					// Turn radios into btn-group
					$(\'.radio.btn-group label\').addClass(\'btn btn-small\');
					$(\'body\').on(\'click\',\'.btn-group label:not(.active)\',function()
					{
						var label = $(this);
						var input = $(\'#\' + label.attr(\'for\'));

						if (!input.prop(\'checked\')) {
							label.closest(\'.btn-group\').find(\'label\').removeClass(\'active btn-success btn-danger btn-primary\');
							if (input.val() == \'\') {
								label.addClass(\'active btn-primary\');
							} else if (input.val() == 0) {
								label.addClass(\'active btn-danger\');
							} else {
								label.addClass(\'active btn-success\');
							}
							input.prop(\'checked\', \'checked\');
						}
					});
					$(\'.btn-group input[checked=checked]\').each(function()
					{
						if ($(this).val() == \'\') {
							$(\'label[for=\' + $(this).attr(\'id\') + \']\').addClass(\'active btn-primary\');
						} else if ($(this).val() == 0) {
							$(\'label[for=\' + $(this).attr(\'id\') + \']\').addClass(\'active btn-danger\');
						} else {
							$(\'label[for=\' + $(this).attr(\'id\') + \']\').addClass(\'active btn-success\');
						}
					});
				});
            
            </script>';
			return $html;
	}

	protected function configAzuraalert($data,$dataObject){
		

		$dataObject->formOption = JForm::getInstance('com_azurapagebuilder.page.optionalert', 'formoptionalert');

		// set content
		$elementContentFields = array("message");
		if(count($elementContentFields) == 1) {
			$content = $elementContentFields[0];
			$value = '';
			if(isset($dataObject->content)){
				$value = $dataObject->content;
			}
			$dataObject->formOption->setValue("{$content}","elementContent", $value);
		}elseif(count($elementContentFields) > 1){
			foreach ($elementContentFields as $key => $content) {
				$value = null;
				if(isset($dataObject->content->{$attr})){
					$value = $dataObject->content->{$attr};
				}
				$dataObject->formOption->setValue("{$content}","elementContent", $value);
			}

		}
		// set attrs
		$elementAttrsFields = array("id","class","type","closebtn","fadeeffect","layout");
		foreach ($elementAttrsFields as $key => $attr) {
			$value = null;
			if(isset($dataObject->attrs->{$attr})){
				$value = $dataObject->attrs->{$attr};
			}
			$dataObject->formOption->setValue("{$attr}","elementAttrs", $value);
		}

		$html = '
			<h2><i class="fa fa-bullhorn"></i> Alert'.(!empty($dataObject->name)? ' - '.$dataObject->name : '').'</h2>
			<div class="row-fluid" style="padding-top:20px;">
				<div class="span5">
					<div class="input-prepend">
						<span class="add-on">Name</span>
						<input class="inputbox" name="elementName" placeholder="Element name" type="text" value="'.(isset($dataObject->name)? $dataObject->name : '').'">
					</div>
				</div>
				<div class="span7">
					<div class="form-horizontal">
						<div class="control-group elementPubLang">
							<div class="control-label">
								<label id="elementPubLang_published-lbl" for="elementPubLang_published">Published</label>
							</div>
							<div class="controls">
								<fieldset id="elementPubLang_published" class="radio btn-group btn-group-yesno">
									<input id="elementPubLang_published1" name="elementPubLang[published]" value="1" '.((isset($dataObject->published) && $dataObject->published == '1')? 'checked="checked"' : '').' type="radio">
									<label  for="elementPubLang_published1">Yes</label>
									<input id="elementPubLang_published0" name="elementPubLang[published]" value="0" '.((isset($dataObject->published) && $dataObject->published == '0')? 'checked="checked"' : '').' type="radio">
									<label for="elementPubLang_published0">No</label>
								</fieldset>
							</div>
						</div>
					</div>


				</div>
			</div>
			<!-- /.row-fluid -->
			<hr>
			<div class="row-fluid">
				<div class="span12">
					<div class="form-horizontal">';

					$html .= '<div class="row-fluid">';
						$html .='<div class="span6">';
						// attrs

					foreach ($dataObject->formOption->getFieldsets('elementAttrs') as $fieldsets => $fieldset) {
						foreach($dataObject->formOption->getFieldset($fieldset->name) as $field){

							if ($field->hidden) {
								$html .= $field->input;
							}else{
								//echo'<pre>';var_dump($field);
								$html .= $field->getControlGroup();
							}
						}
					}

					$html .='</div><div class="span6">';

					// content
					foreach ($dataObject->formOption->getFieldsets('elementContent') as $fieldsets => $fieldset) {
						foreach($dataObject->formOption->getFieldset($fieldset->name) as $field){

							if ($field->hidden) {
								$html .= $field->input;
							}else{
								//echo'<pre>';var_dump($field);
								$html .= $field->getControlGroup();
							}
						}
					}

					$html .='</div>';

				$html .='</div>';
					
					$html .='</div>
					<!-- /.form-horizontal -->
				</div>
				<!-- /.span12 -->

			</div>
			<!-- /.row-fluid -->
			
			<div class="row-fluid" style="text-align: center;">
				<hr>
				<a href="#" id="azura-setting-btn-save" class="btn btn-primary azura-setting-btn-save">Save</a>
				<a href="#" id="azura-setting-btn-cancel" class="btn btn-default azura-setting-btn-cancel">Cancel</a>
			</div>';
			

			$html .='
			<script>
				jQuery(function($){
					

					// Turn radios into btn-group
					$(\'.radio.btn-group label\').addClass(\'btn btn-small\');
					$(\'body\').on(\'click\',\'.btn-group label:not(.active)\',function()
					{
						var label = $(this);
						var input = $(\'#\' + label.attr(\'for\'));

						if (!input.prop(\'checked\')) {
							label.closest(\'.btn-group\').find(\'label\').removeClass(\'active btn-success btn-danger btn-primary\');
							if (input.val() == \'\') {
								label.addClass(\'active btn-primary\');
							} else if (input.val() == 0) {
								label.addClass(\'active btn-danger\');
							} else {
								label.addClass(\'active btn-success\');
							}
							input.prop(\'checked\', \'checked\');
						}
					});
					$(\'.btn-group input[checked=checked]\').each(function()
					{
						if ($(this).val() == \'\') {
							$(\'label[for=\' + $(this).attr(\'id\') + \']\').addClass(\'active btn-primary\');
						} else if ($(this).val() == 0) {
							$(\'label[for=\' + $(this).attr(\'id\') + \']\').addClass(\'active btn-danger\');
						} else {
							$(\'label[for=\' + $(this).attr(\'id\') + \']\').addClass(\'active btn-success\');
						}
					});
				});
            
            </script>';
			return $html;
	}

	protected function configAzuraprogress($data,$dataObject){
		

		$dataObject->formOption = JForm::getInstance('com_azurapagebuilder.page.optionprogress', 'formoptionprogress');

		
		// set attrs
		$elementAttrsFields = array("id","class","value","title","type","striped","animated","aschild","customstyle");
		foreach ($elementAttrsFields as $key => $attr) {
			$value = null;
			if(isset($dataObject->attrs->{$attr})){
				$value = $dataObject->attrs->{$attr};
			}
			$dataObject->formOption->setValue("{$attr}","elementAttrs", $value);
		}

		$html = '
			<h2><i class="fa fa-tachometer"></i> Progress'.(!empty($dataObject->name)? ' - '.$dataObject->name : '').'</h2>
			<div class="row-fluid" style="padding-top:20px;">
				<div class="span5">
					<div class="input-prepend">
						<span class="add-on">Name</span>
						<input class="inputbox" name="elementName" placeholder="Element name" type="text" value="'.(isset($dataObject->name)? $dataObject->name : '').'">
					</div>
				</div>
				<div class="span7">
					<div class="form-horizontal">
						<div class="control-group elementPubLang">
							<div class="control-label">
								<label id="elementPubLang_published-lbl" for="elementPubLang_published">Published</label>
							</div>
							<div class="controls">
								<fieldset id="elementPubLang_published" class="radio btn-group btn-group-yesno">
									<input id="elementPubLang_published1" name="elementPubLang[published]" value="1" '.((isset($dataObject->published) && $dataObject->published == '1')? 'checked="checked"' : '').' type="radio">
									<label  for="elementPubLang_published1">Yes</label>
									<input id="elementPubLang_published0" name="elementPubLang[published]" value="0" '.((isset($dataObject->published) && $dataObject->published == '0')? 'checked="checked"' : '').' type="radio">
									<label for="elementPubLang_published0">No</label>
								</fieldset>
							</div>
						</div>
					</div>


				</div>
			</div>
			<!-- /.row-fluid -->
			<hr>
			<div class="row-fluid">
				<div class="span12">
					<div class="form-horizontal">';

					// attrs

					foreach ($dataObject->formOption->getFieldsets('elementAttrs') as $fieldsets => $fieldset) {
						$html .= '<div class="row-fluid">';
							$html .='<div class="span6">';
							$key = 1;
							$owlfields = $dataObject->formOption->getFieldset($fieldset->name);
						foreach($owlfields as $field){

							if ($field->hidden) {
								$html .= $field->input;
							}else{
								//echo'<pre>';var_dump($field);
								$html .= $field->getControlGroup();
								$key++;
							}

							if($key == 6 && count($owlfields) >6){
								$html .= '</div><div class="span6">';
							}
						}

							$html .='</div>';
						$html .='</div>';
					}
					
					$html .='</div>
					<!-- /.form-horizontal -->
				</div>
				<!-- /.span12 -->

			</div>
			<!-- /.row-fluid -->
			
			<div class="row-fluid" style="text-align: center;">
				<hr>
				<a href="#" id="azura-setting-btn-save" class="btn btn-primary azura-setting-btn-save">Save</a>
				<a href="#" id="azura-setting-btn-cancel" class="btn btn-default azura-setting-btn-cancel">Cancel</a>
			</div>';

			$html .='
			<script>
				jQuery(function($){
					$(\'.radio.btn-group label\').addClass(\'btn btn-small\');
					$(\'body\').on(\'click\',\'.btn-group label:not(.active)\',function()
					{
						var label = $(this);
						var input = $(\'#\' + label.attr(\'for\'));

						if (!input.prop(\'checked\')) {
							label.closest(\'.btn-group\').find(\'label\').removeClass(\'active btn-success btn-danger btn-primary\');
							if (input.val() == \'\') {
								label.addClass(\'active btn-primary\');
							} else if (input.val() == 0) {
								label.addClass(\'active btn-danger\');
							} else {
								label.addClass(\'active btn-success\');
							}
							input.prop(\'checked\', \'checked\');
						}
					});
					$(\'.btn-group input[checked=checked]\').each(function()
					{
						if ($(this).val() == \'\') {
							$(\'label[for=\' + $(this).attr(\'id\') + \']\').addClass(\'active btn-primary\');
						} else if ($(this).val() == 0) {
							$(\'label[for=\' + $(this).attr(\'id\') + \']\').addClass(\'active btn-danger\');
						} else {
							$(\'label[for=\' + $(this).attr(\'id\') + \']\').addClass(\'active btn-success\');
						}
					});
				});
            
            </script>';
			
			return $html;
	}

	protected function configAzurabxslider($data,$dataObject){
		

		$dataObject->formOption = JForm::getInstance('com_azurapagebuilder.page.optionbxslider', 'formoptionbxslider');

		
		// set attrs
		$elementAttrsFields = array("id","class","mode","speed","slideMargin","startSlide","randomStart","slideSelector","infiniteLoop","hideControlOnEnd","easing","captions","ticker","tickerHover","adaptiveHeight","adaptiveHeightSpeed","video","responsive","useCSS","preloadImages","touchEnabled","swipeThreshold","oneToOneTouch","preventDefaultSwipeX","preventDefaultSwipeY","pager","pagerType","pagerShortSeparator","pagerSelector","pagerCustom","buildPager","controls","nextText","prevText","nextSelector","prevSelector","autoControls","startText","stopText","autoControlsCombine","autoControlsSelector","auto","pause","autoStart","autoDirection","autoHover","autoDelay","minSlides","maxSlides","moveSlides","slideWidth","layout");
		foreach ($elementAttrsFields as $key => $attr) {
			$value = null;
			if(isset($dataObject->attrs->{$attr})){
				$value = $dataObject->attrs->{$attr};
			}
			$dataObject->formOption->setValue("{$attr}","elementAttrs", $value);
		}

		$html = '
			<h2><i class="fa fa-sliders"></i> BxSlider'.(!empty($dataObject->name)? ' - '.$dataObject->name : '').'</h2>
			<div class="row-fluid" style="padding-top:20px;">
				<div class="span5">
					<div class="input-prepend">
						<span class="add-on">Name</span>
						<input class="inputbox" name="elementName" placeholder="Element name" type="text" value="'.(isset($dataObject->name)? $dataObject->name : '').'">
					</div>
				</div>
				<div class="span7">
					<div class="form-horizontal">
						<div class="control-group elementPubLang">
							<div class="control-label">
								<label id="elementPubLang_published-lbl" for="elementPubLang_published">Published</label>
							</div>
							<div class="controls">
								<fieldset id="elementPubLang_published" class="radio btn-group btn-group-yesno">
									<input id="elementPubLang_published1" name="elementPubLang[published]" value="1" '.((isset($dataObject->published) && $dataObject->published == '1')? 'checked="checked"' : '').' type="radio">
									<label  for="elementPubLang_published1">Yes</label>
									<input id="elementPubLang_published0" name="elementPubLang[published]" value="0" '.((isset($dataObject->published) && $dataObject->published == '0')? 'checked="checked"' : '').' type="radio">
									<label for="elementPubLang_published0">No</label>
								</fieldset>
							</div>
						</div>
					</div>


				</div>
			</div>
			<!-- /.row-fluid -->
			<hr>
			<div class="row-fluid">
				<div class="span12">
					<div class="form-horizontal">';

					// attrs

					foreach ($dataObject->formOption->getFieldsets('elementAttrs') as $fieldsets => $fieldset) {
						$html .= '<div class="row-fluid">';
							$html .='<div class="span6">';
							$key = 1;
							$owlfields = $dataObject->formOption->getFieldset($fieldset->name);
						foreach($owlfields as $field){

							if ($field->hidden) {
								$html .= $field->input;
							}else{
								//echo'<pre>';var_dump($field);
								$html .= $field->getControlGroup();
								$key++;
							}

							if($key == 27 && count($owlfields) >27){
								$html .= '</div><div class="span6">';
							}
						}

							$html .='</div>';
						$html .='</div>';
					}
					
					$html .='</div>
					<!-- /.form-horizontal -->
				</div>
				<!-- /.span12 -->

			</div>
			<!-- /.row-fluid -->
			
			<div class="row-fluid" style="text-align: center;">
				<hr>
				<a href="#" id="azura-setting-btn-save" class="btn btn-primary azura-setting-btn-save">Save</a>
				<a href="#" id="azura-setting-btn-cancel" class="btn btn-default azura-setting-btn-cancel">Cancel</a>
			</div>';

			$html .='
			<script>
				jQuery(function($){

					// Turn radios into btn-group
					$(\'.radio.btn-group label\').addClass(\'btn btn-small\');
					$(\'body\').on(\'click\',\'.btn-group label:not(.active)\',function()
					{
						var label = $(this);
						var input = $(\'#\' + label.attr(\'for\'));

						if (!input.prop(\'checked\')) {
							label.closest(\'.btn-group\').find(\'label\').removeClass(\'active btn-success btn-danger btn-primary\');
							if (input.val() == \'\') {
								label.addClass(\'active btn-primary\');
							} else if (input.val() == 0) {
								label.addClass(\'active btn-danger\');
							} else {
								label.addClass(\'active btn-success\');
							}
							input.prop(\'checked\', \'checked\');
						}
					});
					$(\'.btn-group input[checked=checked]\').each(function()
					{
						if ($(this).val() == \'\') {
							$(\'label[for=\' + $(this).attr(\'id\') + \']\').addClass(\'active btn-primary\');
						} else if ($(this).val() == 0) {
							$(\'label[for=\' + $(this).attr(\'id\') + \']\').addClass(\'active btn-danger\');
						} else {
							$(\'label[for=\' + $(this).attr(\'id\') + \']\').addClass(\'active btn-success\');
						}
					});
				});
            
            </script>';
			
			return $html;
	}


	protected function configAzurabxslideritem($data,$dataObject){
		

		$dataObject->formOption = JForm::getInstance('com_azurapagebuilder.page.optionbxslideritem', 'formoptionbxslideritem');
		$elementAttrsFields = array("id","class","slideimage","layout");
		foreach ($elementAttrsFields as $key => $attr) {
			$value = null;
			if(isset($dataObject->attrs->{$attr})){
				$value = $dataObject->attrs->{$attr};
			}
			$dataObject->formOption->setValue("{$attr}","elementAttrs", $value);
		}

		$html = '
			<h2><i class="fa fa-sliders"></i> Slide'.(!empty($dataObject->name)? ' - '.$dataObject->name : '').'</h2>
			<div class="row-fluid" style="padding-top:20px;">
				<div class="span5">
					<div class="input-prepend">
						<span class="add-on">Name</span>
						<input class="inputbox" name="elementName" placeholder="Element name" type="text" value="'.(isset($dataObject->name)? $dataObject->name : '').'">
					</div>
				</div>
				<div class="span7">
					<div class="form-horizontal">
						<div class="control-group elementPubLang">
							<div class="control-label">
								<label id="elementPubLang_published-lbl" for="elementPubLang_published">Published</label>
							</div>
							<div class="controls">
								<fieldset id="elementPubLang_published" class="radio btn-group btn-group-yesno">
									<input id="elementPubLang_published1" name="elementPubLang[published]" value="1" '.((isset($dataObject->published) && $dataObject->published == '1')? 'checked="checked"' : '').' type="radio">
									<label  for="elementPubLang_published1">Yes</label>
									<input id="elementPubLang_published0" name="elementPubLang[published]" value="0" '.((isset($dataObject->published) && $dataObject->published == '0')? 'checked="checked"' : '').' type="radio">
									<label for="elementPubLang_published0">No</label>
								</fieldset>
							</div>
						</div>
					</div>

				</div>
			</div>
			<!-- /.row-fluid -->
			<hr>
			<div class="row-fluid">
				<div class="span12">
					<div class="form-horizontal">';


					foreach ($dataObject->formOption->getFieldsets('elementAttrs') as $fieldsets => $fieldset) {
						foreach($dataObject->formOption->getFieldset($fieldset->name) as $field){

							if ($field->hidden) {
								$html .= $field->input;
							}else{
								//echo'<pre>';var_dump($field);
								$html .= $field->getControlGroup();
							}
						}
					}
					
					$html .='</div>
					<!-- /.form-horizontal -->
				</div>
				<!-- /.span12 -->

			</div>
			<!-- /.row-fluid -->
			
			<div class="row-fluid" style="text-align: center;">
				<hr>
				<a href="#" id="azura-setting-btn-save" class="btn btn-primary azura-setting-btn-save">Save</a>
				<a href="#" id="azura-setting-btn-cancel" class="btn btn-default azura-setting-btn-cancel">Cancel</a>
			</div>';

			$html .='
			<script>
				function jInsertFieldValue(value, id) {
		        		var old_value = jQuery("#" + id).val();
		        		if (old_value != value) {
		        			var $elem = jQuery("#" + id);
		        			$elem.val(value);
		        			$elem.trigger("change");
		        			if (typeof($elem.get(0).onchange) === "function") {
		        				$elem.get(0).onchange();
		        			}
		        		}
		        }

	            jQuery(function($) {
	    			SqueezeBox.initialize({});
	    			SqueezeBox.assign($(\'a.modal_jform_azuramedia\').get(), {
	    				parse: \'rel\'
	    			});
					
					
	                
	                jQuery(\'body\').on(\'change\',\'#elementAttrs_slideImage\', function(event){
	                    event.preventDefault();
	                    var value = event.currentTarget.value;
	                    
	                    jQuery(\'.fancybox-inner\').find(\'#elementAttrs_slideImage\').val(value);
	                });
	    		});
            
            </script>';

            $html .='
			<script>
				jQuery(function($){

					// Turn radios into btn-group
					$(\'.radio.btn-group label\').addClass(\'btn btn-small\');
					$(\'body\').on(\'click\',\'.btn-group label:not(.active)\',function()
					{
						var label = $(this);
						var input = $(\'#\' + label.attr(\'for\'));

						if (!input.prop(\'checked\')) {
							label.closest(\'.btn-group\').find(\'label\').removeClass(\'active btn-success btn-danger btn-primary\');
							if (input.val() == \'\') {
								label.addClass(\'active btn-primary\');
							} else if (input.val() == 0) {
								label.addClass(\'active btn-danger\');
							} else {
								label.addClass(\'active btn-success\');
							}
							input.prop(\'checked\', \'checked\');
						}
					});
					$(\'.btn-group input[checked=checked]\').each(function()
					{
						if ($(this).val() == \'\') {
							$(\'label[for=\' + $(this).attr(\'id\') + \']\').addClass(\'active btn-primary\');
						} else if ($(this).val() == 0) {
							$(\'label[for=\' + $(this).attr(\'id\') + \']\').addClass(\'active btn-danger\');
						} else {
							$(\'label[for=\' + $(this).attr(\'id\') + \']\').addClass(\'active btn-success\');
						}
					});
				});
            
            </script>';

			return $html;
	}
}


