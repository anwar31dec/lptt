<?php
/**
 * @package Azura Joomla Pagebuilder
 * @author Cththemes - www.cththemes.com
 * @date: 15-07-2014
 *
 * @copyright  Copyright ( C ) 2014 cththemes.com . All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die();

class CthShortcodes {

	private static $_instance;

    public $_headerStyles = array();

    public $_headerScripts = array();

    public $_footerScripts = array();

    public $_footerScriptsScript = '';

	private $document;

	public function __construct(){
        //require_once (JPATH_SITE.'/components/com_k2/helpers/route.php');
	}

	final public static function getInstance()
    {
        if( !self::$_instance ){
            self::$_instance = new self();
            //self::getInstance()->getDocument();
            //self::getInstance()->getDocument()->cthshortcodes = self::getInstance();
        } 
        return self::$_instance;
    }

    public static function getDocument($key=false)
    {
        self::getInstance()->document = JFactory::getDocument();
        $doc = self::getInstance()->document;
        if( is_string($key) ) return $doc->$key;

        return $doc;
    }

    public static function templateName()
    {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select("template")->from("#__template_styles")->where('client_id = 0 AND home = 1');
        $db->setQuery($query);
        $template = $db->loadResult();

        //echo'<pre>';var_dump($template);die;

        return $template;

        //return JFactory::getApplication('site')->getTemplate();
    }

    public static function templatePath()
    {
        
        return  JPATH_ROOT.'/templates/' . self::getInstance()->templateName();
    }

    public static function templateUri(){
        return JURI::root(true).'/templates/'.self::getInstance()->templateName();
    }

    public static function importShortcode($paths)
    {
        if( is_array($paths) ) foreach((array) $paths as $file) self::_importShortcode( $file );
        else self::_importShortcode( $paths );
        return self::getInstance();
    }

    private static function _importShortcode($shortcode)
    {

        $templateShortcode  = self::getInstance()->templatePath().'/html/com_azurapagebuilder/plugin/shortcodes/'. $shortcode;
        $pluginShortcode = JPATH_PLUGINS.'/system/cthshortcodes/shortcodes/' . $shortcode;

        if( file_exists( $templateShortcode ) && !is_dir( $templateShortcode ) ){
            require_once $templateShortcode;
        }
        if( file_exists( $pluginShortcode ) && !is_dir( $pluginShortcode ) ){
            require_once $pluginShortcode;
        }
        return self::getInstance();
    }

    public static function importShortcodes()
    {

        $shortcodes = array();

        $templateShortcodes = glob( self::getInstance()->templatePath().'/html/com_azurapagebuilder/plugin/shortcodes/*.php' );
        $pluginShortcodes = glob( JPATH_PLUGINS.'/system/cthshortcodes/shortcodes/*.php');

        foreach((array) $templateShortcodes as $value)  $shortcodes[] =  basename($value);

        foreach((array) $pluginShortcodes as $value)  $shortcodes[] =   basename($value);

        $shortcodes = array_unique($shortcodes);

        require_once JPATH_PLUGINS.'/system/cthshortcodes/core/wp_shortcodes.php';

        foreach($shortcodes as $shortcode  ) self::getInstance()->importShortcode($shortcode);

        return self::getInstance();
    }

    public static function importScripts($pageid = 0){
        if($pageid == 0){
            return self::getInstance();
        }

        $azuraParams = JComponentHelper::getParams('com_azurapagebuilder');




        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select("customCssLinks, customJsLinks, customJsButtonLinks, customJsBottomScript")->from("#__azurapagebuilder_pages")->where('id='.(int)$pageid);
        $db->setQuery($query);
        $result = $db->loadObject();

        $customCssLinks = array();

        if(!empty($result->customCssLinks)){
            $customCssLinks = explode(",", $result->customCssLinks);
        }

        $customJsLinks = array();

        if(!empty($result->customJsLinks)){
            $customJsLinks = explode(",", $result->customJsLinks);
        }



        $customJsButtonLinks = array();

        if(!empty($result->customJsButtonLinks)){
            $customJsButtonLinks = explode(",", $result->customJsButtonLinks);
        }

        $customJsBottomScript = array();

        if(!empty($result->customJsBottomScript)){
            $customJsBottomScript = $result->customJsBottomScript;
        }

        //echo'<pre>';var_dump($customJsButtonLinks);die;

        $themePath = JPATH_THEMES.'/'.JFactory::getApplication()->getTemplate();
        $themeLink = JURI::root(true).'/templates/'.JFactory::getApplication()->getTemplate();

        if(!empty($customCssLinks)){

            foreach ($customCssLinks as $css) {
                $css = trim($css);
                if(file_exists($themePath.$css)){
                    self::getInstance()->_headerStyles[] = $themeLink.$css;
                }elseif(file_exists($themePath.'/css/'.$css)){
                    self::getInstance()->_headerStyles[] = $themeLink.'/css/'.$css;
                }elseif(file_exists($themePath.'/stylesheet/'.$css)){
                    self::getInstance()->_headerStyles[] = $themeLink.'/stylesheet/'.$css;
                }
                
            }
        }

        if(!empty($customJsLinks)){
            foreach ($customJsLinks as $js) {
                $js = trim($js);
                if(file_exists($themePath.$js)){
                    self::getInstance()->_headerScripts[] = $themeLink.$js;
                }elseif(file_exists($themePath.'/js/'.$js)){
                    self::getInstance()->_headerScripts[] = $themeLink.'/js/'.$js;
                }elseif(file_exists($themePath.'/javascript/'.$js)){
                    self::getInstance()->_headerScripts[] = $themeLink.'/javascript/'.$js;
                }
            }
        }

        if(!empty($customJsButtonLinks)){
            foreach ($customJsButtonLinks as $js) {
                $js = trim($js);
                if(file_exists($themePath.$js)){
                    self::getInstance()->_footerScripts[] = $themeLink.$js;
                }elseif(file_exists($themePath.'/js/'.$js)){
                    self::getInstance()->_footerScripts[] = $themeLink.'/js/'.$js;
                }elseif(file_exists($themePath.'/javascript/'.$js)){
                    self::getInstance()->_footerScripts[] = $themeLink.'/javascript/'.$js;
                }
            }
        }

        if (!empty($customJsBottomScript)) {
            self::getInstance()->_footerScriptsScript .= $customJsBottomScript;
        }

        $bslinkpre = JURI::root(true).'/plugins/system/cthshortcodes/assets/bootstrap/';
        $assetLink = JURI::root(true).'/plugins/system/cthshortcodes/assets/';

        // css links in reversed order

        if($azuraParams->get('useawesome') == '1'){
            array_unshift(self::getInstance()->_headerStyles, $assetLink.'awesomefonts/css/font-awesome.min.css');

        }

        
        if($azuraParams->get('useazuracss') == '1'){
            array_unshift(self::getInstance()->_headerStyles, JURI::root(true).'/components/com_azurapagebuilder/assets/css/azp-elements.css');
            array_unshift(self::getInstance()->_headerStyles, JURI::root(true).'/components/com_azurapagebuilder/assets/css/azp-framework.css');
        }

        // animations css 
        //array_unshift(self::getInstance()->_headerStyles, $assetLink.'animations/css/animations.min.css');


        if($azuraParams->get('useflexslider') == '1'){
            array_unshift(self::getInstance()->_headerStyles, $assetLink.'flexslider/flexslider.css');
        }

        if($azuraParams->get('usebxslider') == '1'){
            array_unshift(self::getInstance()->_headerStyles, $assetLink.'bxslider/jquery.bxslider.css');
        }

        if($azuraParams->get('useowlcarousel') == '1'){
            array_unshift(self::getInstance()->_headerStyles, $assetLink.'owl-carousel/owl.transitions.css');
            array_unshift(self::getInstance()->_headerStyles, $assetLink.'owl-carousel/owl.theme.css');
            array_unshift(self::getInstance()->_headerStyles, $assetLink.'owl-carousel/owl.carousel.css');
        }

        $bscss = $azuraParams->get('usebootstrapcss');
        if($bscss !== 'none'){
            $bstheme = $azuraParams->get('usebootstraptheme');
            if($bstheme !== 'none'){
                array_unshift(self::getInstance()->_headerStyles, $bslinkpre.$bstheme.'/css/bootstrap-theme.min.css');
            }
            if($bscss != 'bs23'){
            	array_unshift(self::getInstance()->_headerStyles, $bslinkpre.'migrate.css');
            }
            array_unshift(self::getInstance()->_headerStyles, $bslinkpre.$bscss.'/css/bootstrap.min.css');
        }
 
        // js links in reversed order

        if($azuraParams->get('useazurajs') == '1'){
            array_unshift(self::getInstance()->_headerScripts, JURI::root(true).'/components/com_azurapagebuilder/assets/js/azp-framework.js');
        }

        // animations and appear js
        //array_unshift(self::getInstance()->_headerScripts, $assetLink.'animations/js/animations.js');
        //array_unshift(self::getInstance()->_headerScripts, $assetLink.'animations/js/appear.min.js');

        if($azuraParams->get('usecountto') == '1'){
            array_unshift(self::getInstance()->_headerScripts, $assetLink.'countTo/jquery.countTo.js');
        }

        if($azuraParams->get('useflexslider') == '1'){
            array_unshift(self::getInstance()->_headerScripts, $assetLink.'flexslider/jquery.flexslider-min.js');
        }

        if($azuraParams->get('usebxslider') == '1'){
            array_unshift(self::getInstance()->_headerScripts, $assetLink.'bxslider/jquery.bxslider.min.js');
        }

        if($azuraParams->get('useowlcarousel') == '1'){
            array_unshift(self::getInstance()->_headerScripts, $assetLink.'owl-carousel/owl.carousel.min.js');
        }

        if($azuraParams->get('usewaypoints') == '1'){
            array_unshift(self::getInstance()->_headerScripts, $assetLink.'waypoints/waypoints.min.js');
        }

        if($azuraParams->get('useeasing') == '1'){
            array_unshift(self::getInstance()->_headerScripts, $assetLink.'easing/jquery.easing.1.3.js');
        }

        $bsjs = $azuraParams->get('usebootstrapjs');
        if($bsjs !== 'none'){
			
            array_unshift(self::getInstance()->_headerScripts, $bslinkpre.$bscss.'/js/bootstrap.js');
			
			if($bsjs != 'bs23'){
            	//array_unshift(self::getInstance()->_headerScripts, $assetLink.'jquery/v1.11.1/jquery.v1.11.1.min.js');
            }
        }

        return self::getInstance();
    }

    public static function importScriptsFrontEdit(){

        array_push(self::getInstance()->_headerStyles, JURI::root(true).'/components/com_azurapagebuilder/assets/css/azp-front-edit-style.css');

        array_push(self::getInstance()->_headerScripts, JURI::root(true).'/components/com_azurapagebuilder/assets/js/azp-front-edit-script.js');

        return self::getInstance();
    }

    public static function loadModule($id){
        require_once JPATH_PLUGINS.'/system/cthshortcodes/core/cthmodulehelper.php';
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select("*")->from("#__modules")->where('id='.(int)$id);
        $db->setQuery($query);
        $result = $db->loadObject();
        $title = $result->title;
        $mod = $result->module;
        $module = CthModuleHelper::getModule( $mod, $title );
        //echo'<pre>';var_dump($module);die;
        $module->content = CthModuleHelper::renderModule( $module,array('style'=>'none'));
        return $module;
    }

    public static function getK2Items($catid, $limit='All', $order='created', $orderDir='ASC', $addFields = '',$child = '0'){
        //static $itemArray = array();
        //echo'<pre>';var_dump($child);die;
        if($child == '1'){
            return self::getK2ItemsChild($catid, $limit, $order, $orderDir, $addFields);
        }
        $order = 'a.'.$order;
        if((int)$limit){
            $limit = (int) $limit;
        }else{
            $limit = 'All';
        }
        $db =  JFactory::getDbo();
        $query=$db->getQuery(true);
        $where = array('a.published=1','a.trash=0');
        if($catid!=0){
            $where[]='catid='.(int)$catid;
        }
        $query  ->select('a.id,a.title,a.alias,a.extra_fields,a.introtext,a.fulltext,a.catid,c.alias as categoryalias,c.name as c_name, c.description as c_desc')
                ->select('a.created,a.created_by,a.created_by_alias,a.ordering,a.image_caption,a.image_credits,a.params');
       if(!empty($addFields)){
        $query  ->select($addFields);
       }

        $query  ->from('#__k2_items AS a')
                ->join('INNER', '#__k2_categories AS c ON (a.catid = c.id)')
                ->where($where)
                ->order($db->escape($order . ' ' . $orderDir));
        $db     ->setQuery($query,0,$limit);

        //$return = $db->loadObjectList();

        return $db->loadObjectList();
    }

    public static function getK2ItemsChild($catid, $limit = 'All', $order = 'created', $orderDir='ASC',$addFields=''){
        $catarray = self::getK2CategoryChildren($catid);
        array_unshift($catarray, $catid);

        $catsitemsarray = array();

        foreach ($catarray as $cat) {
            $catitemsarray = self::getK2Items($cat,'All',$order,$orderDir);
            foreach ($catitemsarray as $item) {
                $itempush = array();
                $itempush['id'] = $item->id;
                $itempush['catname'] = $item->c_name;
                $itempush['catalias'] = $item->categoryalias;
                array_push($catsitemsarray, $itempush);
            }
        }

        $return = array();

        if(is_numeric($limit)){
            for ($i=0; $i < $limit ; $i++) { 
                if($i < count($catsitemsarray)){
                    $itemreturn = self::getK2Item($catsitemsarray[$i]['id']);
                    $itemreturn->catname = $catsitemsarray[$i]['catname'];
                    $itemreturn->catalias = $catsitemsarray[$i]['catalias'];
                    array_push($return, $itemreturn);
                }
            }
        }else{
            foreach ($catsitemsarray as $value) {
                $itemreturn = self::getK2Item($value['id']);
                $itemreturn->catname = $value['catname'];
                $itemreturn->catalias = $value['catalias'];
                array_push($return, $itemreturn);
            }
        }

        return $return;
    }

    public static function getK2Item($id,$addFields = ''){
        
        $db =  JFactory::getDbo();
        $query=$db->getQuery(true);
        $where = array('a.published=1','a.trash=0');
        if($id!=0){
            $where[]='id='.(int)$id;
        }
        $query  ->select('a.id,a.title,a.alias,a.extra_fields,a.introtext,a.fulltext,a.catid')
                ->select('a.created,a.modified,a.created_by,a.created_by_alias,a.ordering,a.image_caption,a.image_credits,a.params');
       if(!empty($addFields)){
                $query  ->select($addFields);
       }

        $query  ->from('#__k2_items AS a')

                ->where($where);
                //->join('INNER', '#__k2_categories AS c ON (a.catid = c.id)');
        $db     ->setQuery($query);

        $item = $db->loadObject();
        require_once (JPATH_SITE.'/components/com_k2/helpers/route.php');
        require_once JPATH_BASE.'/components/com_k2/models/item.php';
        require_once JPATH_BASE.'/components/com_k2/helpers/permissions.php';

        JTable::addIncludePath(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_k2'.DS.'tables');

        $K2ModelItem = new K2ModelItem;


        $item = $K2ModelItem->prepareItem($item, 'category', 'itemlist');

        return $item;
    }

    public static function getK2Cat($catid=0){
        $db =  JFactory::getDbo();
        $query=$db->getQuery(true);
        //$where = array('a.id=1');
        if((int)$catid!=0){
            $where ='a.id='.(int)$catid;
        }
        $query      ->select('a.id,a.name,a.alias,a.description')
            ->from('#__k2_categories AS a')
            ->where($where)
            ->order('a.ordering ASC');
        $db->setQuery($query,0,1);

        return $db->loadObject();
    }

    public static function k2CatHasChildren($id)
    {

        $mainframe = JFactory::getApplication();
        $user = JFactory::getUser();
        $aid = (int)$user->get('aid');
        $id = (int)$id;
        $db = JFactory::getDBO();
        $query = "SELECT * FROM #__k2_categories  WHERE parent={$id} AND published=1 AND trash=0 ";
        if (K2_JVERSION != '15')
        {
            $query .= " AND access IN(".implode(',', $user->getAuthorisedViewLevels()).") ";
            if ($mainframe->getLanguageFilter())
            {
                $languageTag = JFactory::getLanguage()->getTag();
                $query .= " AND language IN (".$db->Quote($languageTag).", ".$db->Quote('*').") ";
            }

        }
        else
        {
            $query .= " AND access <= {$aid}";
        }

        $db->setQuery($query);
        $rows = $db->loadObjectList();
        if ($db->getErrorNum())
        {
            echo $db->stderr();
            return false;
        }

        if (count($rows))
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    public static function getK2CategoryChildren($catid)
    {

        static $array = array();
        $mainframe = JFactory::getApplication();
        $user = JFactory::getUser();
        $aid = (int)$user->get('aid');
        $catid = (int)$catid;
        $db = JFactory::getDBO();
        $query = "SELECT * FROM #__k2_categories WHERE parent={$catid} AND published=1 AND trash=0 ";
        if (K2_JVERSION != '15')
        {
            $query .= " AND access IN(".implode(',', $user->getAuthorisedViewLevels()).") ";
            if ($mainframe->getLanguageFilter())
            {
                $languageTag = JFactory::getLanguage()->getTag();
                $query .= " AND language IN (".$db->Quote($languageTag).", ".$db->Quote('*').") ";
            }
        }
        else
        {
            $query .= " AND access <= {$aid}";
        }
        $query .= " ORDER BY ordering ";

        $db->setQuery($query);
        $rows = $db->loadObjectList();
        if ($db->getErrorNum())
        {
            echo $db->stderr();
            return false;
        }
        foreach ($rows as $row)
        {
            array_push($array, $row->id);
            if (self::k2CatHasChildren($row->id))
            {
                self::getK2CategoryChildren($row->id);
            }
        }
        return $array;
    }


    public static function getK2ItemTagsFilter($item,$implode = " ",$ucf = false){
        require_once JPATH_BASE.'/components/com_k2/models/item.php';

        $K2ModelItem = new K2ModelItem;

        $tags = array();
        $itemTags = $K2ModelItem->getItemTags($item->id);
        if(count($itemTags)) {
            foreach ($itemTags as $tag) {
                $tagName = str_replace(" ", "-", $tag->name);
                if($ucf === true){
                    $tags[] = ucfirst($tagName);
                }else{
                    $tags[] = strtolower($tagName);
                }
                
            }
        }

        $filter = implode($implode, $tags);

        return $filter;
    }

    public static function getK2TagsFilter($items){

        $catTags = array();

        $allTags = array();

        $tags = array();

        if(count($items)){


            require_once JPATH_BASE.'/components/com_k2/models/item.php';

            $K2ModelItem = new K2ModelItem;

            foreach ($items as $item) {
                $catTags[] = $K2ModelItem->getItemTags($item->id);
            }
            
            if(!empty($catTags)){
                foreach ($catTags as $catTag) {
                    if (!empty($catTag)) {
                        foreach ($catTag as $tag) {
                            $allTags[] = $tag->name;
                        }
                    }
                }
            }

            $tags = array_unique($allTags);
        }
        return $tags;
    }

    public static function getK2ItemLink($id,$alias,$catid,$categoryalias){
        require_once (JPATH_SITE.'/components/com_k2/helpers/route.php');
        return urldecode(JRoute::_(K2HelperRoute::getItemRoute($id.':'.urlencode($alias), $catid.':'.urlencode($categoryalias))));
    }


    public static function addShortcodeTemplate($file){
		jimport( 'joomla.filesystem.file' );
		$tempOverride 		= self::getInstance()->templatePath().'/html/com_azurapagebuilder/plugin/shortcodes_template/'.$file.'.php';
		$tempBase 	= JPATH_PLUGINS . '/system/cthshortcodes/shortcodes_template/'.$file.'.php';
		if(JFile::exists($tempOverride)){
			return $tempOverride;
		}else if(JFile::exists($tempBase)){
			return $tempBase;
		}else{
			return false;
		}
	}

    public static function renderElementOptions($optionFormName = '', $dataObject, $data = null){
        //return $optionFormName;

        // azura element option plugin
        // Import the appropriate plugin group.
        JPluginHelper::importPlugin('azura');

        // Get the dispatcher.
        $dispatcher = JEventDispatcher::getInstance();

        $dispatcher->trigger('onAzuraBeforePrepareElementForm');

        $dataObject->formOption = JForm::getInstance('com_azurapagebuilder.element.'.$optionFormName, $optionFormName);

        

        // Trigger the element form preparation event.
        $results = $dispatcher->trigger('onAzuraPrepareElementForm', array($dataObject->formOption, $dataObject));

        // Check for errors encountered while preparing the form.
        if (count($results) && in_array(false, $results, true))
        {
            // Get the last error.
            $error = $dispatcher->getError();

            if (!($error instanceof Exception))
            {
                throw new Exception($error);
            }
        }


        // settings

        $eleSettings = array();

        foreach ($dataObject->formOption->getFieldsets('elementSettings') as $fieldsets => $fieldset) {
            foreach($dataObject->formOption->getFieldset($fieldset->name) as $field){

                //$eleSettings[$field->name] = $field->getAttribute('setting');

                preg_match('/elementSettings\[(.+)\]/', $field->name, $matches);

                if(count($matches) > 1){
                    $sett = $matches[1];
                    $eleSettings[$sett] = $field->getAttribute('setting');
                }

            }
        }

        $showStyleTab = 'true';
        if(isset($eleSettings['showStyleTab'])){
            $showStyleTab = $eleSettings['showStyleTab'];
        }

        $showAnimationTab = 'true';
        if(isset($eleSettings['showAnimationTab'])){
            $showAnimationTab = $eleSettings['showAnimationTab'];
        }

        $numberLeftSettings = 'all';
        if(isset($eleSettings['numberLeftSettings'])){
            if(is_numeric($eleSettings['numberLeftSettings'])){
                $numberLeftSettings = (int)$eleSettings['numberLeftSettings'];
            }
        }

        $contentFirst = 'true';
        if(isset($eleSettings['contentFirst'])){
            $contentFirst = $eleSettings['contentFirst'];
        }

        $html = '
            <h2 class="element_config_header"><i class="fa fa-cog"></i> Config-'. substr($dataObject->type, 5).(!empty($dataObject->name)? ': '.$dataObject->name : '').'</h2>
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
            <!-- /.row-fluid -->';

            if($showStyleTab == 'true' || $showAnimationTab == 'true'){
                $html .= '<div id="azp_tabs" class="ui-tabs ui-widget ui-widget-content">
                 <ul id="azp_setting_tabs_ul" class="ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-widget-header">
                    <li class="ui-state-default ui-corner-top ui-tabs-active ui-state-active"><a class="ui-tabs-anchor" href="#azp_tab_option">Options</a></li>';
                 if($showStyleTab == 'true'){
                    $html .='<li class="ui-state-default ui-corner-top"><a class="ui-tabs-anchor" href="#azp_tab_style">Styles</a></li>';
                 }
                 if($showAnimationTab == 'true'){
                    $html .='<li class="ui-state-default ui-corner-top"><a class="ui-tabs-anchor" href="#azp_tab_animation">Animation</a></li>';
                 }  
                    
                $html .='</ul>';
            }else{
                $html .='<hr>';
            }

            // if($showStyleTab == 'true'){
            //     $html .= '<div id="azp_tabs" class="ui-tabs ui-widget ui-widget-content">
            //      <ul id="azp_setting_tabs_ul" class="ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-widget-header">
            //         <li class="ui-state-default ui-corner-top ui-tabs-active ui-state-active"><a class="ui-tabs-anchor" href="#azp_tab_option">Options</a></li>
            //         <li class="ui-state-default ui-corner-top"><a class="ui-tabs-anchor" href="#azp_tab_style">Styles</a></li>
            //     </ul>';
            // }else{
            //     $html .='<hr>';
            // }

            
                $html .='<div class="row-fluid azp_setting_tabs_content ui-tabs-panel" id="azp_tab_option">
                    <div class="span12">';
                    if($dataObject->type == 'AzuraHtml'){
                        $html .='<iframe class="AzuraHtml-editor" src="'.JURI::base().'index.php?option=com_azurapagebuilder&task=edit.getEditor&tmpl=component" width="100%" height="370"></iframe>';
                    }else{
                        $html .= '<div class="form-vertical">';

                        // content
                        $eleSettingContent = '';

                        foreach ($dataObject->formOption->getFieldsets('elementContent') as $fieldsets => $fieldset) {
                            foreach($dataObject->formOption->getFieldset($fieldset->name) as $field){

                                preg_match('/elementContent\[(.+)\]/', $field->name, $matches);

                                if(count($matches) > 1){
                                    $attr = $matches[1];

                                    if(isset($dataObject->content)){
                                        //$value = $dataObject->attrs->{$content};
                                        $field->setValue(rawurldecode($dataObject->content));
                                    }
                                }

                                if ($field->hidden) {
                                    $eleSettingContent .= $field->input;
                                }else{
                                    //echo'<pre>';var_dump($field->name);
                                    $eleSettingContent .= $field->getControlGroup();
                                }
                            }
                        }

                        // attrs
                        $fieldsetsTotal = count($dataObject->formOption->getFieldsets('elementAttrs'));
                        if($fieldsetsTotal > 0){
                            $fieldsetKey = 1;
                            foreach ($dataObject->formOption->getFieldsets('elementAttrs') as $fieldsets => $fieldset) {
                                if($numberLeftSettings != 'all'){
                                    $html .= '<div class="row-fluid">';
                                        $html .='<div class="span6">';
                                        $key = 0;
                                }

                                if($contentFirst == 'true' && $fieldsetKey == 1){
                                    $html .= $eleSettingContent;
                                }
                                

                                $fields = $dataObject->formOption->getFieldset($fieldset->name);

                                foreach($fields as $field){

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
                                        if($numberLeftSettings != 'all'){
                                            $key++;
                                        }
                                        
                                    }

                                    if($numberLeftSettings != 'all'){
                                        if($key == $numberLeftSettings && count($fields) >= $numberLeftSettings){
                                            $html .= '</div><div class="span6">';
                                        }
                                    }
                                }

                                if($contentFirst != 'true' && $fieldsetKey == $fieldsetsTotal){
                                    $html .= $eleSettingContent;
                                }


                                if($numberLeftSettings != 'all'){
                                        $html .='</div>';
                                    $html .='</div>';
                                }

                                $fieldsetKey++;
                            }
                        }else{
                            $html .= $eleSettingContent;
                        }
                        
                        $html .='</div>
                        <!-- /.form-horizontal -->';
                    }

                $html .='
                    </div>
                    <!-- /.span12 -->

                </div>
                <!-- /.row-fluid -->';

                if($showStyleTab == 'true'){
                    $html .='<div class="row-fluid azp_setting_tabs_content ui-tabs-panel" id="azp_tab_style">
                    '.self::renderElementStyle($dataObject->attrs).'

                    

                    </div>

                    <!-- /.row-fluid -->';
                }

                if($showAnimationTab == 'true'){
                    $html .='<div class="row-fluid azp_setting_tabs_content ui-tabs-panel" id="azp_tab_animation">
                    '.self::renderElementAnimation($dataObject->attrs).'

                    

                    </div>

                    <!-- /.row-fluid -->';
                }

            if($showStyleTab == 'true' || $showAnimationTab == 'true'){
            $html .='</div>

            <!-- /#azp_Tabs -->';
                }

             
            $html .='
            
            <div class="row-fluid" style="text-align: center;">
                <hr>
                <a href="#" id="azura-setting-btn-save" class="btn btn-primary azp_btn-primary azura-setting-btn-save">Save</a>
                <a href="#" id="azura-setting-btn-cancel" class="btn btn-default azp_btn-default azura-setting-btn-cancel">Close</a>
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
                    SqueezeBox.assign($(\'a.modal_jform_azuragmapselect\').get(), {
                        parse: \'rel\'
                    });
                    
                }); 

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

                jQuery(document).ready(function($) {';
                    if($showStyleTab == 'true'|| $showAnimationTab == 'true'){
                        //$html .='$( "#azp_tabs" ).tabs();';

                        $html .='

                            
                            $(".azp_setting_tabs_content").hide(); 
                            $("ul#azp_setting_tabs_ul li:first").addClass("ui-tabs-active ui-state-active").show();
                            $(".azp_setting_tabs_content:first").show(); 

                            
                            $("ul#azp_setting_tabs_ul li").click(function() {

                                $("ul#azp_setting_tabs_ul li").removeClass("ui-tabs-active ui-state-active"); 
                                $(this).addClass("ui-tabs-active ui-state-active"); 
                                $(".azp_setting_tabs_content").hide(); 

                                var activeTab = $(this).find("a").attr("href"); 
                                $(activeTab).fadeIn(); 
                                return false;
                            });

                        ';
                    }

                $html .='$(\'.radio.btn-group label\').addClass(\'btn btn-small\');
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

    public static function renderElementStyle($styleAttrs){

        $margin_top = '';
        $margin_right = '';
        $margin_bottom = '';
        $margin_left = '';

        $border_top_width = '';
        $border_right_width = '';
        $border_bottom_width = '';
        $border_left_width = '';

        $padding_top = '';
        $padding_right = '';
        $padding_bottom = '';
        $padding_left = '';

        // margin

        if(isset($styleAttrs->margin_top)){
            $margin_top = $styleAttrs->margin_top;
        }
        if(isset($styleAttrs->margin_right)){
            $margin_right = $styleAttrs->margin_right;
        }
        if(isset($styleAttrs->margin_bottom)){
            $margin_bottom = $styleAttrs->margin_bottom;
        }
        if(isset($styleAttrs->margin_left)){
            $margin_left = $styleAttrs->margin_left;
        }

        //border

        if(isset($styleAttrs->border_top_width)){
            $border_top_width = $styleAttrs->border_top_width;
        }
        if(isset($styleAttrs->border_right_width)){
            $border_right_width = $styleAttrs->border_right_width;
        }
        if(isset($styleAttrs->border_bottom_width)){
            $border_bottom_width = $styleAttrs->border_bottom_width;
        }
        if(isset($styleAttrs->border_left_width)){
            $border_left_width = $styleAttrs->border_left_width;
        }

        //padding

        if(isset($styleAttrs->padding_top)){
            $padding_top = $styleAttrs->padding_top;
        }
        if(isset($styleAttrs->padding_right)){
            $padding_right = $styleAttrs->padding_right;
        }
        if(isset($styleAttrs->padding_bottom)){
            $padding_bottom = $styleAttrs->padding_bottom;
        }
        if(isset($styleAttrs->padding_left)){
            $padding_left = $styleAttrs->padding_left;
        }

        $eleStyleAddForm = JForm::getInstance('com_azurapagebuilder.page.optionelementstyle', 'formoptionelementstyle');

        $elementAttrsFields = array("border_color","border_style","background_color","background_image","background_repeat","background_attachment","background_size","additional_style","simplified");
        foreach ($elementAttrsFields as $key => $attr) {
            $value = null;
            if(isset($styleAttrs->{$attr})){
                $value = $styleAttrs->{$attr};
            }
            $eleStyleAddForm->setValue("{$attr}","elementAttrs", $value);
        }

        $simplifiedField = $eleStyleAddForm->getField('simplified',"elementAttrs")->value;


        $html = '<div class="azp_layout-onion'.(($simplifiedField == '1')? ' azp_simplified ': ' ').'span7">';
            $html .= '<div class="azp_margin">';
                $html .= '<label>margin</label>';
                $html .= '<input name="elementAttrs[margin_top]" data-name="margin-top" class="azp_top" placeholder="-" value="'.$margin_top.'" data-attribute="margin" type="text">';
                $html .= '<input name="elementAttrs[margin_right]" data-name="margin-right" class="azp_right" placeholder="-" data-attribute="margin" value="'.$margin_right.'" type="text">';
                $html .= '<input name="elementAttrs[margin_bottom]" data-name="margin-bottom" class="azp_bottom" placeholder="-" data-attribute="margin" value="'.$margin_bottom.'" type="text">';
                $html .= '<input name="elementAttrs[margin_left]" data-name="margin-left" class="azp_left" placeholder="-" data-attribute="margin" value="'.$margin_left.'" type="text"> ';     
                $html .= '<div class="azp_border">';
                    $html .= '<label>border</label>';
                    $html .= '<input name="elementAttrs[border_top_width]" data-name="border-width-top" class="azp_top" placeholder="-" data-attribute="border" value="'.$border_top_width.'" type="text">';
                    $html .= '<input name="elementAttrs[border_right_width]" data-name="border-width-right" class="azp_right" placeholder="-" data-attribute="border" value="'.$border_right_width.'" type="text">';
                    $html .= '<input name="elementAttrs[border_bottom_width]" data-name="border-width-bottom" class="azp_bottom" placeholder="-" data-attribute="border" value="'.$border_bottom_width.'" type="text">';
                    $html .= '<input name="elementAttrs[border_left_width]" data-name="border-width-left" class="azp_left" placeholder="-" data-attribute="border" value="'.$border_left_width.'" type="text">    ';      
                    $html .= '<div class="azp_padding">';
                        $html .= '<label>padding</label>';
                        $html .= '<input name="elementAttrs[padding_top]" data-name="padding-top" class="azp_top" placeholder="-" data-attribute="padding" value="'.$padding_top.'" type="text">';
                        $html .= '<input name="elementAttrs[padding_right]" data-name="padding-right" class="azp_right" placeholder="-" data-attribute="padding" value="'.$padding_right.'" type="text">';
                        $html .= '<input name="elementAttrs[padding_bottom]" data-name="padding-bottom" class="azp_bottom" placeholder="-" data-attribute="padding" value="'.$padding_bottom.'" type="text">';
                        $html .= '<input name="elementAttrs[padding_left]" data-name="padding-left" class="azp_left" placeholder="-" data-attribute="padding" value="'.$padding_left.'" type="text">  ';            
                        $html .= '<div class="azp_content">Azura</div>   ';       
                    $html .= '</div>      ';
                $html .= '</div>    ';
            $html .= '</div>';
        $html .= '</div>';

        $html .= '<!-- /.span7 -->';

        

        $html .= '<div class="span5 azp_settings">    ';

            $html .= '<div class="form-vertical">    ';
                           
            


        foreach ($eleStyleAddForm->getFieldsets('elementAttrs') as $fieldsets => $fieldset) {

            if($fieldset->name == 'elementAttrsStyle'){
                foreach($eleStyleAddForm->getFieldset($fieldset->name) as $field){

                    if ($field->hidden) {
                        $html .= $field->input;
                    }else{
                        //echo'<pre>';var_dump($field);
                        $html .= $field->getControlGroup();
                    }
                }
            }
        }

        


            $html .= '</div>';

            $html .= '<!-- /.form-vertical -->';
                           
        $html .= '</div>';

        $html .= '<!-- /.span5 -->';

         $html .=' 
            <script>

                jQuery(document).ready(function (){                  
                    
                    //jQuery("body").on("change","#elementAttrs_simplified", function(event){
                    jQuery("#elementAttrs_simplified").change(function(event){
                        jQuery(".azp_layout-onion").toggleClass("azp_simplified");
                    });
                    jQuery("body").on("blur",".azp_top",function(event){
                        event.preventDefault();
                        var azp_layout_onion = jQuery(this).closest(".azp_layout-onion");
                        if(azp_layout_onion.is(".azp_simplified")){
                            var val = jQuery(this).val();
                            jQuery(this).closest("div").children("input").val(val);
                        }
                        
                    });

                        //event.preventDefault();
                        //var value = event.currentTarget.value;
                        
                        //var azp_layout_onion = jQuery(".azp_layout-onion");

                        //if(azp_layout_onion.hasClass("azp_simplified")){
                        //    azp_layout_onion.removeClass("azp_simplified");
                        //}else{
                        //    azp_layout_onion.addClass("azp_simplified");
                        //}

                        //console.log(jQuery(".azp_layout-onion"));

                        //jQuery("body").on("blur",".azp_top",function(event){
                        //    event.preventDefault();
                        //    var val = jQuery(this).val();
                        //    jQuery(this).closest("div").children("input").val(val);
                        //});
                    //});
                });
            
            </script>';

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


        return $html;

    }

    public static function renderElementAnimation($styleAttrs){

        $eleStyleAddForm = JForm::getInstance('com_azurapagebuilder.page.optionelementanimation', 'formoptionelementanimation');

        $elementAttrsFields = array("animation","trigger","animationtype","animationdelay");
        foreach ($elementAttrsFields as $key => $attr) {
            $value = null;
            if(isset($styleAttrs->{$attr})){
                $value = $styleAttrs->{$attr};
            }
            $eleStyleAddForm->setValue("{$attr}","elementAttrs", $value);
        }

        $html = '<div class="span12">';

            $html .= '<div class="form-vertical">';
                           
            


        foreach ($eleStyleAddForm->getFieldsets('elementAttrs') as $fieldsets => $fieldset) {

            if($fieldset->name == 'elementAttrsAnimation'){
                foreach($eleStyleAddForm->getFieldset($fieldset->name) as $field){

                    if ($field->hidden) {
                        $html .= $field->input;
                    }else{
                        //echo'<pre>';var_dump($field);
                        $html .= $field->getControlGroup();
                    }
                }
            }
        }

        


            $html .= '</div>';

            $html .= '<!-- /.form-horizontal -->';
                           
        $html .= '</div>';

        $html .= '<!-- /.span12 -->';


        return $html;

    }


    public static function parseStyle($styleArr = array()){
        if(empty($styleArr)){
            return '';
        }
        else {
            //echo '<pre>';var_dump($styleArr);die;
            //margin
            $margin_style = '';
            if(!empty($styleArr['margin_top'])||!empty($styleArr['margin_right'])||!empty($styleArr['margin_bottom'])||!empty($styleArr['margin_left'])){
                if($styleArr['simplified'] == '1'){
                    if(!preg_match('/\d*\.?\d*\s*(%|in|cm|mm|em|ex|pt|pc|px)$/i', $styleArr['margin_top'])){
                        $margin_style = 'margin: '.$styleArr['margin_top'].'px;';
                    }else{
                        $margin_style = 'margin: '.$styleArr['margin_top'].';';
                    }
                }else{
                    if(!empty($styleArr['margin_top'])){
                        if(!preg_match('/\d*\.?\d*\s*(%|in|cm|mm|em|ex|pt|pc|px)$/i', $styleArr['margin_top'])){
                            $margin_style = 'margin-top: '.$styleArr['margin_top'].'px;';
                        }else{
                            $margin_style = 'margin-top: '.$styleArr['margin_top'].';';
                        }
                    }

                    if(!empty($styleArr['margin_right'])){
                        if(!preg_match('/\d*\.?\d*\s*(%|in|cm|mm|em|ex|pt|pc|px)$/i', $styleArr['margin_right'])){
                            $margin_style .= 'margin-right: '.$styleArr['margin_right'].'px;';
                        }else{
                            $margin_style .= 'margin-right: '.$styleArr['margin_right'].';';
                        }
                    }

                    if(!empty($styleArr['margin_bottom'])){
                        if(!preg_match('/\d*\.?\d*\s*(%|in|cm|mm|em|ex|pt|pc|px)$/i', $styleArr['margin_bottom'])){
                            $margin_style .= 'margin-bottom: '.$styleArr['margin_bottom'].'px;';
                        }else{
                            $margin_style .= 'margin-bottom: '.$styleArr['margin_bottom'].';';
                        }
                    }

                    if(!empty($styleArr['margin_left'])){
                        if(!preg_match('/\d*\.?\d*\s*(%|in|cm|mm|em|ex|pt|pc|px)$/i', $styleArr['margin_left'])){
                            $margin_style .= 'margin-left: '.$styleArr['margin_left'].'px;';
                        }else{
                            $margin_style .= 'margin-left: '.$styleArr['margin_left'].';';
                        }
                    }
                }
            }

            //padding
            $padding_style = '';
            if(!empty($styleArr['padding_top'])||!empty($styleArr['padding_right'])||!empty($styleArr['padding_bottom'])||!empty($styleArr['padding_left'])){
                if($styleArr['simplified'] == '1'){
                    if(!preg_match('/\d*\.?\d*\s*(%|in|cm|mm|em|ex|pt|pc|px)$/i', $styleArr['padding_top'])){
                        $padding_style = 'padding: '.$styleArr['padding_top'].'px;';
                    }else{
                        $padding_style = 'padding: '.$styleArr['padding_top'].';';
                    }
                }else{
                    if(!empty($styleArr['padding_top'])){
                        if(!preg_match('/\d*\.?\d*\s*(%|in|cm|mm|em|ex|pt|pc|px)$/i', $styleArr['padding_top'])){
                            $padding_style = 'padding-top: '.$styleArr['padding_top'].'px;';
                        }else{
                            $padding_style = 'padding-top: '.$styleArr['padding_top'].';';
                        }
                    }

                    if(!empty($styleArr['padding_right'])){
                        if(!preg_match('/\d*\.?\d*\s*(%|in|cm|mm|em|ex|pt|pc|px)$/i', $styleArr['padding_right'])){
                            $padding_style .= 'padding-right: '.$styleArr['padding_right'].'px;';
                        }else{
                            $padding_style .= 'padding-right: '.$styleArr['padding_right'].';';
                        }
                    }

                    if(!empty($styleArr['padding_bottom'])){
                        if(!preg_match('/\d*\.?\d*\s*(%|in|cm|mm|em|ex|pt|pc|px)$/i', $styleArr['padding_bottom'])){
                            $padding_style .= 'padding-bottom: '.$styleArr['padding_bottom'].'px;';
                        }else{
                            $padding_style .= 'padding-bottom: '.$styleArr['padding_bottom'].';';
                        }
                    }

                    if(!empty($styleArr['padding_left'])){
                        if(!preg_match('/\d*\.?\d*\s*(%|in|cm|mm|em|ex|pt|pc|px)$/i', $styleArr['padding_left'])){
                            $padding_style .= 'padding-left: '.$styleArr['padding_left'].'px;';
                        }else{
                            $padding_style .= 'padding-left: '.$styleArr['padding_left'].';';
                        }
                    }
                }
            }

            //border
            $border_width_style = '';
            if(!empty($styleArr['border_top_width'])||!empty($styleArr['border_right_width'])||!empty($styleArr['border_bottom_width'])||!empty($styleArr['border_left_width'])){
                if($styleArr['simplified'] == '1'){
                    if(!preg_match('/\d*\.?\d*\s*(%|in|cm|mm|em|ex|pt|pc|px)$/i', $styleArr['border_top_width'])){
                        $border_width_style = 'border-width: '.$styleArr['border_top_width'].'px;';
                    }else{
                        $border_width_style = 'border-width: '.$styleArr['border_top_width'].';';
                    }
                }else{
                    if(!empty($styleArr['border_top_width'])){
                        if(!preg_match('/\d*\.?\d*\s*(%|in|cm|mm|em|ex|pt|pc|px)$/i', $styleArr['border_top_width'])){
                            $border_width_style = 'border-top-width: '.$styleArr['border_top_width'].'px;';
                        }else{
                            $border_width_style = 'border-top-width: '.$styleArr['border_top_width'].';';
                        }
                    }

                    if(!empty($styleArr['border_right_width'])){
                        if(!preg_match('/\d*\.?\d*\s*(%|in|cm|mm|em|ex|pt|pc|px)$/i', $styleArr['border_right_width'])){
                            $border_width_style .= 'border-right-width: '.$styleArr['border_right_width'].'px;';
                        }else{
                            $border_width_style .= 'border-right-width: '.$styleArr['border_right_width'].';';
                        }
                    }

                    if(!empty($styleArr['border_bottom_width'])){
                        if(!preg_match('/\d*\.?\d*\s*(%|in|cm|mm|em|ex|pt|pc|px)$/i', $styleArr['border_bottom_width'])){
                            $border_width_style .= 'border-bottom-width: '.$styleArr['border_bottom_width'].'px;';
                        }else{
                            $border_width_style .= 'border-bottom-width: '.$styleArr['border_bottom_width'].';';
                        }
                    }

                    if(!empty($styleArr['border_left_width'])){
                        if(!preg_match('/\d*\.?\d*\s*(%|in|cm|mm|em|ex|pt|pc|px)$/i', $styleArr['border_left_width'])){
                            $border_width_style .= 'border-left-width: '.$styleArr['border_left_width'].'px;';
                        }else{
                            $border_width_style .= 'border-left-width: '.$styleArr['border_left_width'].';';
                        }
                    }
                }
            }

            if(!empty($styleArr['border_color'])){
                $border_color = 'border-color: '.$styleArr['border_color'].';';
            }else{
                $border_color = '';
            }



            if(!empty($styleArr['border_style'])){
                $border_style_style = 'border-style: '.$styleArr['border_style'].';';
            }else{
                $border_style_style = '';
            }

            $border_style = $border_color. ' '.$border_width_style .' '. $border_style_style;

            // background;

            if(!empty($styleArr['background_color'])){
                $background_color = 'background-color: '.$styleArr['background_color'].';';
            }else{
                $background_color = '';
            }

            if(!empty($styleArr['background_image'])){
                $background_image = 'background-image: url(\''.JURI::root(true).'/'.$styleArr['background_image'].'\');';
            }else{
                $background_image = '';
            }

            if(!empty($styleArr['background_repeat'])){
                $background_repeat_style = 'background-repeat: '.$styleArr['background_repeat'].';';
                
            }else{
                $background_repeat_style = '';
            }

            if(!empty($styleArr['background_attachment'])){
                $background_attachment_style = 'background-attachment: '.$styleArr['background_attachment'].';';
                
            }else{
                $background_attachment_style = '';
            }

            if(!empty($styleArr['background_size'])){
                $background_size_style = '-webkit-background-size: '.$styleArr['background_size'].'; -moz-background-size: '.$styleArr['background_size'].';-o-background-size: '.$styleArr['background_size'].';background-size: '.$styleArr['background_size'].';';
                
            }else{
                $background_size_style = '';
            }

            $background_style = $background_color .' '.$background_image . ' '.$background_repeat_style. ' '.$background_attachment_style. ' '.$background_size_style;

            // additional style
            if(!empty($styleArr['additional_style'])){
                $additional_style = $styleArr['additional_style'];
            }else{
                $additional_style ='';
            }

            $return = array();

            $return['margin'] = $margin_style;
            $return['padding'] = $padding_style;
            $return['border'] = $border_style;
            $return['background'] = $background_style;
            $return['additional_style'] = $additional_style;

            return $return;
        }
    }


}