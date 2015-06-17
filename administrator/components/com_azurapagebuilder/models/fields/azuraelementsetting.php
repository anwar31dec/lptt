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
 
class JFormFieldAzuraElementSetting extends JFormField {
 
        protected $type = 'AzuraElementSetting';
 
        // getLabel() left out
 
        public function getInput() {
                return '';
        }

        public function getSetting(){

        	return $settings;
        }
}