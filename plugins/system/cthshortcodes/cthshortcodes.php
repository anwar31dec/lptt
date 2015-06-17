<?php
/**
 * @package Azura Joomla Pagebuilder
 * @author Cththemes - www.cththemes.com
 * @date: 15-07-2014
 *
 * @copyright  Copyright ( C ) 2014 cththemes.com . All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */
    //no direct accees
    defined ('_JEXEC') or die ('resticted aceess');

    jimport( 'joomla.event.plugin' );

    class  plgSystemCthShortcodes extends JPlugin
    {
        protected $autoloadLanguage = true;
        
        function onAfterInitialise()
        {
            // $cthshortcodesClass = JPATH_PLUGINS.'/system/cthshortcodes/core/cthshortcodes.php';
            // if (file_exists($cthshortcodesClass)) {
            //     require_once $cthshortcodesClass;
            //     CthShortcodes::getInstance()->importShortcodes();
            // }

        }

    

        
        function onContentPrepare($context, &$article)
        {
            $app = JFactory::getApplication();

            $doc = JFactory::getDocument();

            if ($app->getName() != 'site' || $doc->getType() !== 'html')
            {
                return true;
            }

            $userDef =  ( $context == 'com_content.article' ) ||
                        ( $context == 'com_content.category' ) ||
                        ( $context == 'com_content.featured' ) ||
                        ( $context == 'mod_custom.content' )||
                        ( $context == 'com_k2.category' )||
                        ( $context == 'com_k2.item' );

            if( $userDef ) {
                $article->text = do_shortcode($article->text);
            }
 
        }

        function onAfterRoute(){



             $cthshortcodesClass = JPATH_PLUGINS.'/system/cthshortcodes/core/cthshortcodes.php';
            if (file_exists($cthshortcodesClass)) {
                require_once $cthshortcodesClass;
                CthShortcodes::getInstance()->importShortcodes();
            }

            $app = JFactory::getApplication();

            $doc = JFactory::getDocument();


            if ($app->getName() != 'site' || $doc->getType() !== 'html')
            {
                return true;
            }

            $input = $app->input;

            if($input->get('option') == 'com_azurapagebuilder' && ($input->get('view') == 'page'|| $input->get('view') == 'edit')){
                CthShortcodes::getInstance()->importScripts($input->get('id'));
            }

            if($input->get('option') == 'com_azurapagebuilder' && $input->get('view') == 'edit'){
                CthShortcodes::getInstance()->importScriptsFrontEdit();
            }

        }


        function onAfterRender()
        {
            $app = JFactory::getApplication();

            $doc = JFactory::getDocument();

            if ($app->getName() != 'site' || $doc->getType() !== 'html')
            {
                return true;
            }

            $data =  $app->getBody();

            if($this->params->get('useAnywhere','0') == '1'){
                
                $data = do_shortcode($data);
                
            }

            $headerStyles    = CthShortcodes::getInstance()->_headerStyles;
            $headerScripts     = CthShortcodes::getInstance()->_headerScripts;
            $footerScripts     = CthShortcodes::getInstance()->_footerScripts;
            $footerScriptsScript     = CthShortcodes::getInstance()->_footerScriptsScript;

            
            $headerStyles    = array_unique($headerStyles);
            $headerScripts     = array_unique($headerScripts);
            $footerScripts    = array_unique($footerScripts);

            $header_styles_scripts  = '';

            foreach ($headerStyles as $style)
            {
                $header_styles_scripts .= '<link rel="stylesheet" href="' . $style . '" />'. "\n"; 
            }     

            foreach ($headerScripts as $script)
            {
                $header_styles_scripts .= '<script type="text/javascript" src="' . $script . '"></script>'. "\n"; 
            }    

            $data = str_replace('</head>', $header_styles_scripts . "\n</head>", $data);

            $footer_scripts = '';

            foreach ($footerScripts as $script)
            {
                $footer_scripts .= '<script type="text/javascript" src="' . $script . '"></script>'. "\n"; 
            }  

            $footer_scripts .= '<script type="text/javascript">'.$footerScriptsScript.'</script>';  

            $data = str_replace('</body>', $footer_scripts . "\n</body>", $data);

            $app->setBody($data);
        }

}